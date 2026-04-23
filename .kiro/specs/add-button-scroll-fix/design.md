# Add Button Scroll Fix — Bugfix Design

## Overview

On mobile (viewport ≤ 1023px), tapping the "Add" button on a product card triggers two competing scroll operations inside the AJAX handler in `public/assets/website/JS/cart-operations.js`:

1. The `success` callback calls `scrollQtyBoxIntoViewNearest(qtyBoxNode)` via a double `requestAnimationFrame`, which scrolls the page upward toward the newly-rendered qty box.
2. The `complete` callback calls `maybeCorrectScrollAfterAdd(scrollYAtTap)`, which detects the drift and scrolls back down to the original position.

The result is a visible up-then-down scroll jump before the quantity controls appear.

**Fix strategy**: On mobile (`needsCartScrollLock() === true`), skip both scroll operations entirely. The qty box replaces the Add button in-place, so it is already visible — no scroll is needed. On desktop the existing behaviour is unchanged. The `lockBodyScrollMobile` / `unlockBodyScrollMobile` mechanism used by the increment/decrement buttons is not touched.

---

## Glossary

- **Bug_Condition (C)**: The condition that triggers the bug — a mobile user taps the "Add" button, causing `scrollQtyBoxIntoViewNearest` and `maybeCorrectScrollAfterAdd` to fire in sequence and produce a scroll jump.
- **Property (P)**: The desired outcome when the bug condition holds — the page scroll position must not change at all during or after the AJAX operation.
- **Preservation**: All existing behaviours that must remain unchanged by the fix, including desktop cart-panel opening, increment/decrement scroll-lock, sidebar Add buttons, variant modal, and cart badge/sidebar refresh.
- **`needsCartScrollLock()`**: Helper in `cart-operations.js` that returns `true` when `window.matchMedia('(max-width: 1023.98px)').matches` — used to gate mobile-only logic.
- **`scrollQtyBoxIntoViewNearest(el)`**: Helper that calls `el.scrollIntoView({ block: 'nearest' })` — the first scroll trigger in the bug.
- **`maybeCorrectScrollAfterAdd(yAnchor)`**: Helper that compares current `scrollY` to the saved anchor and scrolls back if drift > 14 px — the second scroll trigger in the bug.
- **`scrollYAtTap`**: The `window.pageYOffset` value captured at the moment the Add button is tapped, used as the anchor for `maybeCorrectScrollAfterAdd`.
- **`lockBodyScrollMobile` / `unlockBodyScrollMobile`**: Body-freeze mechanism used exclusively by increment/decrement buttons — must not be altered.

---

## Bug Details

### Bug Condition

The bug manifests when a mobile user (viewport ≤ 1023px) taps the "Add" button on a product card outside the cart sidebar. The AJAX `success` callback unconditionally calls `scrollQtyBoxIntoViewNearest` twice via nested `requestAnimationFrame`, and the `complete` callback unconditionally calls `maybeCorrectScrollAfterAdd`. On mobile, these two operations conflict and produce a visible scroll jump.

**Formal Specification:**
```
FUNCTION isBugCondition(input)
  INPUT: input — a click event on an .add-btn element
  OUTPUT: boolean

  RETURN needsCartScrollLock() = true
         AND isCartSidebarButton(input.target) = false
         AND input.target matches '.add-btn'
END FUNCTION
```

### Examples

- **Typical case**: User is scrolled 400 px down on the product listing page on a phone. They tap "Add". The page scrolls up ~80 px (scrollIntoView), then snaps back down 80 px (maybeCorrectScrollAfterAdd). Expected: page stays at 400 px throughout.
- **Near top of page**: User is at scrollY ≈ 0. scrollIntoView has no visible effect, but maybeCorrectScrollAfterAdd still fires and may cause a micro-jitter. Expected: no movement.
- **Product card near bottom of viewport**: scrollIntoView with `block: 'nearest'` may scroll the page upward to bring the qty box fully into view even though it was already visible. Expected: no movement.
- **Desktop (≥ 1024px)**: Neither scroll helper is gated by viewport — both fire normally. Expected: unchanged behaviour (scrollIntoView brings qty box into view, maybeCorrectScrollAfterAdd is a no-op because drift is 0 on desktop).

---

## Expected Behavior

### Preservation Requirements

**Unchanged Behaviors:**
- Desktop users (viewport ≥ 1024px) must continue to see `scrollQtyBoxIntoViewNearest` called and the side cart panel opened via `openCart()` after a successful Add.
- `maybeCorrectScrollAfterAdd` must continue to fire on desktop (it is a no-op there but must not be removed).
- Increment (`+`) and decrement (`−`) buttons must continue to use `lockBodyScrollMobile` / `unlockBodyScrollMobile` exactly as before — this code path is not touched.
- Tapping "Add" inside `#cartSidebar` or `#cartSidebar2` must continue to work without any page-level scroll intervention (already guarded by `isCartSidebarButton`).
- The variant selection modal must continue to open without scroll interference when a product has multiple variants.
- Cart count badge, mini-cart sidebar refresh (`loadSideCartItems`), and qty-box DOM replacement must all continue to work on every viewport.

**Scope:**
All inputs that do NOT satisfy `isBugCondition` — desktop Add clicks, increment/decrement clicks, sidebar Add clicks, variant modal triggers, and all non-Add interactions — must be completely unaffected by this fix.

---

## Hypothesized Root Cause

Based on the confirmed root cause analysis:

1. **Unconditional `scrollQtyBoxIntoViewNearest` call on mobile**: The double-`requestAnimationFrame` block in the `success` callback calls `scrollQtyBoxIntoViewNearest(qtyBoxNode)` regardless of viewport. On mobile, `scrollIntoView` causes the browser to scroll the document to bring the element into view, even though the qty box replaced the Add button in-place and is already visible.

2. **Unconditional `maybeCorrectScrollAfterAdd` call on mobile**: The `complete` callback calls `maybeCorrectScrollAfterAdd(scrollYAtTap)` regardless of viewport. This function detects the drift introduced by step 1 and issues a counter-scroll, producing the second half of the jump.

3. **No mobile guard on either call**: Neither the `requestAnimationFrame` block nor the `maybeCorrectScrollAfterAdd` call checks `needsCartScrollLock()`. Adding that guard to both is the minimal, targeted fix.

4. **`scrollIntoView` behaviour on iOS/Android**: Mobile browsers implement `scrollIntoView` aggressively — even `block: 'nearest'` can scroll the page if the element is partially outside the visible area after a DOM replacement, which is why the jump is consistently reproducible.

---

## Correctness Properties

Property 1: Bug Condition — No Scroll on Mobile Add

_For any_ click event on an `.add-btn` element where `isBugCondition` returns true (mobile viewport, outside cart sidebar), the fixed AJAX handler SHALL complete the add-to-cart operation and replace the button with quantity controls without changing `window.pageYOffset` at any point during or after the operation.

**Validates: Requirements 2.1, 2.2**

Property 2: Preservation — Desktop and Non-Add Behaviour Unchanged

_For any_ input where `isBugCondition` returns false (desktop Add clicks, increment/decrement clicks, sidebar Add clicks, or any other interaction), the fixed code SHALL produce exactly the same observable behaviour as the original code, preserving all scroll handling, DOM updates, and cart state changes.

**Validates: Requirements 3.1, 3.2, 3.3, 3.4, 3.5**

---

## Fix Implementation

### Changes Required

**File**: `public/assets/website/JS/cart-operations.js`

**Function**: The `success` callback and `complete` callback inside the `$(document).on('click', '.add-btn', ...)` handler.

**Specific Changes**:

1. **Guard `scrollQtyBoxIntoViewNearest` with `needsCartScrollLock()` check** (in `success` callback):
   - Wrap the existing double-`requestAnimationFrame` block that calls `scrollQtyBoxIntoViewNearest(qtyBoxNode)` in an `if (!needsCartScrollLock())` condition.
   - On mobile the block is skipped entirely; on desktop it runs as before.

2. **Guard `maybeCorrectScrollAfterAdd` with `needsCartScrollLock()` check** (in `complete` callback):
   - Wrap the `maybeCorrectScrollAfterAdd(scrollYAtTap)` call in an `if (!needsCartScrollLock())` condition.
   - On mobile the call is skipped; on desktop it runs as before (and remains a no-op since no scroll drift occurs on desktop).

3. **No other changes**:
   - `lockBodyScrollMobile` / `unlockBodyScrollMobile` in the increment/decrement handlers are not touched.
   - `isCartSidebarButton` guard already present on the scroll-anchor capture is not changed.
   - `preventQtyInputFocusScroll`, DOM replacement, cart badge update, `loadSideCartItems`, and `openCart` calls are not changed.

**Resulting pseudocode for the patched section:**
```
// success callback (after DOM replacement)
IF NOT needsCartScrollLock() THEN
  requestAnimationFrame(function()
    scrollQtyBoxIntoViewNearest(qtyBoxNode)
    requestAnimationFrame(function()
      scrollQtyBoxIntoViewNearest(qtyBoxNode)
    )
  )
END IF

// complete callback
IF NOT needsCartScrollLock() THEN
  maybeCorrectScrollAfterAdd(scrollYAtTap)
END IF
```

---

## Testing Strategy

### Validation Approach

The testing strategy follows a two-phase approach: first, surface counterexamples that demonstrate the bug on the unfixed code, then verify the fix works correctly and preserves all existing behaviour.

### Exploratory Bug Condition Checking

**Goal**: Surface counterexamples that demonstrate the scroll jump BEFORE implementing the fix. Confirm the root cause (unconditional scroll calls on mobile) and rule out alternative causes.

**Test Plan**: Write unit tests that mock `needsCartScrollLock()` to return `true`, simulate a click on `.add-btn`, stub the AJAX `success` and `complete` callbacks, and assert that `scrollQtyBoxIntoViewNearest` and `maybeCorrectScrollAfterAdd` are called. Run these tests against the UNFIXED code to observe that both functions fire unconditionally on mobile.

**Test Cases**:
1. **Mobile Add — scrollIntoView fires**: Mock mobile viewport, simulate Add click, assert `scrollQtyBoxIntoViewNearest` is called (will pass on unfixed code, confirming the bug trigger).
2. **Mobile Add — maybeCorrectScrollAfterAdd fires**: Mock mobile viewport, simulate Add click, assert `maybeCorrectScrollAfterAdd` is called (will pass on unfixed code, confirming the second trigger).
3. **Mobile Add — scroll position changes**: Mock mobile viewport, set `window.pageYOffset` to 400, simulate Add click with AJAX success, assert `window.scrollTo` or `scrollIntoView` was invoked (demonstrates the scroll jump).
4. **Edge case — scrollY near 0**: Mock mobile viewport with `scrollYAtTap = 0`, simulate Add click, assert `maybeCorrectScrollAfterAdd` still fires (may cause micro-jitter even at top).

**Expected Counterexamples**:
- `scrollQtyBoxIntoViewNearest` is invoked on mobile even though the qty box is already in the viewport.
- `maybeCorrectScrollAfterAdd` is invoked on mobile and issues a `window.scrollTo` call when drift > 14 px.

### Fix Checking

**Goal**: Verify that for all inputs where the bug condition holds, the fixed handler does not call either scroll function.

**Pseudocode:**
```
FOR ALL input WHERE isBugCondition(input) DO
  result := addBtnHandler_fixed(input)
  ASSERT scrollQtyBoxIntoViewNearest was NOT called
  ASSERT maybeCorrectScrollAfterAdd was NOT called
  ASSERT window.pageYOffset is unchanged
END FOR
```

### Preservation Checking

**Goal**: Verify that for all inputs where the bug condition does NOT hold, the fixed handler produces the same behaviour as the original.

**Pseudocode:**
```
FOR ALL input WHERE NOT isBugCondition(input) DO
  ASSERT addBtnHandler_original(input) produces same scroll calls as addBtnHandler_fixed(input)
END FOR
```

**Testing Approach**: Property-based testing is recommended for preservation checking because:
- It generates many combinations of viewport widths, scroll positions, and button contexts automatically.
- It catches edge cases (e.g., viewport exactly at 1023 px, scrollY = 0, sidebar buttons) that manual tests might miss.
- It provides strong guarantees that non-mobile paths are unaffected across the full input domain.

**Test Cases**:
1. **Desktop Add — scrollIntoView preserved**: Mock desktop viewport (`needsCartScrollLock() = false`), simulate Add click, assert `scrollQtyBoxIntoViewNearest` IS called (preservation of existing desktop behaviour).
2. **Desktop Add — maybeCorrectScrollAfterAdd preserved**: Mock desktop viewport, simulate Add click, assert `maybeCorrectScrollAfterAdd` IS called.
3. **Increment/decrement — lock mechanism unchanged**: Simulate increment/decrement clicks on mobile, assert `lockBodyScrollMobile` and `unlockBodyScrollMobile` are called as before.
4. **Sidebar Add — no scroll intervention**: Mock mobile viewport, simulate Add click inside `#cartSidebar2`, assert neither scroll function is called (already guarded by `isCartSidebarButton`).
5. **Cart badge and sidebar refresh preserved**: On both mobile and desktop, assert `loadSideCartItems` and `.cart-count` update are called after a successful Add.

### Unit Tests

- Test that `scrollQtyBoxIntoViewNearest` is NOT called when `needsCartScrollLock()` returns `true`.
- Test that `maybeCorrectScrollAfterAdd` is NOT called when `needsCartScrollLock()` returns `true`.
- Test that both functions ARE called when `needsCartScrollLock()` returns `false` (desktop).
- Test edge cases: viewport exactly at 1023 px (mobile), 1024 px (desktop).
- Test that `lockBodyScrollMobile` / `unlockBodyScrollMobile` are unaffected by the fix.

### Property-Based Tests

- Generate random viewport widths ≤ 1023 px and assert that no scroll functions are invoked on Add.
- Generate random viewport widths ≥ 1024 px and assert that scroll functions are invoked on Add (preservation).
- Generate random `scrollYAtTap` values (0–5000) on mobile and assert `window.pageYOffset` is unchanged after Add.
- Generate random button contexts (inside/outside sidebar) and assert `isCartSidebarButton` guard is respected independently of the new fix.

### Integration Tests

- Full mobile flow: load product listing page at scrollY 400 px, tap Add, assert scroll position remains 400 px and qty controls appear in place.
- Full desktop flow: tap Add, assert side cart panel opens and qty controls appear.
- Increment/decrement on mobile: assert body-lock mechanism fires and scroll is restored correctly (regression check).
- Sidebar Add on mobile: assert no page scroll occurs and cart updates correctly.
- Variant modal: assert modal opens without scroll jump on mobile.
