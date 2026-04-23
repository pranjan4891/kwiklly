# Implementation Plan

- [ ] 1. Write bug condition exploration test
  - **Property 1: Bug Condition** - Mobile Add Triggers Scroll Functions
  - **CRITICAL**: This test MUST FAIL on unfixed code — failure confirms the bug exists
  - **DO NOT attempt to fix the test or the code when it fails**
  - **NOTE**: This test encodes the expected behavior — it will validate the fix when it passes after implementation
  - **GOAL**: Surface counterexamples that demonstrate both scroll functions fire unconditionally on mobile
  - **Scoped PBT Approach**: Scope the property to the concrete failing case — `needsCartScrollLock() = true`, Add button outside sidebar, any `scrollYAtTap` value
  - Mock `needsCartScrollLock()` to return `true` (mobile viewport ≤ 1023px)
  - Stub the AJAX `success` and `complete` callbacks to fire synchronously
  - Assert that `scrollQtyBoxIntoViewNearest` is NOT called (from Bug Condition in design: `isBugCondition` holds when `needsCartScrollLock() = true` AND `isCartSidebarButton = false`)
  - Assert that `maybeCorrectScrollAfterAdd` is NOT called
  - Assert that `window.pageYOffset` is unchanged after the operation
  - Run test on UNFIXED code — both `scrollQtyBoxIntoViewNearest` and `maybeCorrectScrollAfterAdd` will be called, causing the test to FAIL
  - **EXPECTED OUTCOME**: Test FAILS (this is correct — it proves the bug exists)
  - Document counterexamples found, e.g. "scrollQtyBoxIntoViewNearest called on mobile Add at scrollY=400" and "maybeCorrectScrollAfterAdd called on mobile Add, issuing window.scrollTo"
  - Mark task complete when test is written, run, and failure is documented
  - _Requirements: 1.1, 1.2_

- [ ] 2. Write preservation property tests (BEFORE implementing fix)
  - **Property 2: Preservation** - Desktop and Non-Add Behaviour Unchanged
  - **IMPORTANT**: Follow observation-first methodology
  - Observe: on UNFIXED code with `needsCartScrollLock() = false` (desktop), `scrollQtyBoxIntoViewNearest` IS called after a successful Add
  - Observe: on UNFIXED code with `needsCartScrollLock() = false` (desktop), `maybeCorrectScrollAfterAdd` IS called in the complete callback
  - Observe: increment/decrement clicks on mobile call `lockBodyScrollMobile` and `unlockBodyScrollMobile` — unaffected by the Add handler
  - Observe: Add click inside `#cartSidebar2` on mobile does not trigger page-level scroll (already guarded by `isCartSidebarButton`)
  - Observe: `loadSideCartItems` and `.cart-count` update are called on both mobile and desktop after a successful Add
  - Write property-based tests: for all viewport widths ≥ 1024px, `scrollQtyBoxIntoViewNearest` IS called and `maybeCorrectScrollAfterAdd` IS called after Add (from Preservation Requirements in design)
  - Write property-based tests: for all viewport widths ≤ 1023px with `isCartSidebarButton = true`, neither scroll function is called (existing sidebar guard is preserved)
  - Write property-based tests: for random `scrollYAtTap` values on desktop, `maybeCorrectScrollAfterAdd` receives the correct anchor value
  - Verify all tests PASS on UNFIXED code
  - **EXPECTED OUTCOME**: Tests PASS (this confirms baseline behavior to preserve)
  - Mark task complete when tests are written, run, and passing on unfixed code
  - _Requirements: 3.1, 3.2, 3.3, 3.4, 3.5_

- [ ] 3. Fix for mobile Add button scroll jump

  - [ ] 3.1 Implement the fix in `public/assets/website/JS/cart-operations.js`
    - In the `success` callback of the `.add-btn` click handler, wrap the double-`requestAnimationFrame` block that calls `scrollQtyBoxIntoViewNearest(qtyBoxNode)` with `if (!needsCartScrollLock()) { ... }`
    - In the `complete` callback of the same handler, wrap the `maybeCorrectScrollAfterAdd(scrollYAtTap)` call with `if (!needsCartScrollLock()) { ... }`
    - No other changes — `lockBodyScrollMobile`/`unlockBodyScrollMobile` in increment/decrement handlers are not touched; `isCartSidebarButton` guard on scroll-anchor capture is not changed; DOM replacement, cart badge, `loadSideCartItems`, and `openCart` calls are not changed
    - _Bug_Condition: `isBugCondition(input)` where `needsCartScrollLock() = true` AND `isCartSidebarButton(input.target) = false` AND `input.target` matches `.add-btn`_
    - _Expected_Behavior: `scrollQtyBoxIntoViewNearest` and `maybeCorrectScrollAfterAdd` are NOT called when `needsCartScrollLock()` returns `true`; both ARE called when it returns `false`_
    - _Preservation: Desktop Add (viewport ≥ 1024px) continues to call both scroll helpers; increment/decrement body-lock mechanism is unchanged; sidebar Add, variant modal, cart badge, and sidebar refresh are all unaffected_
    - _Requirements: 2.1, 2.2, 3.1, 3.2, 3.3, 3.4, 3.5_

  - [ ] 3.2 Verify bug condition exploration test now passes
    - **Property 1: Expected Behavior** - Mobile Add Does Not Trigger Scroll Functions
    - **IMPORTANT**: Re-run the SAME test from task 1 — do NOT write a new test
    - The test from task 1 asserts that `scrollQtyBoxIntoViewNearest` and `maybeCorrectScrollAfterAdd` are NOT called when `needsCartScrollLock()` returns `true`
    - When this test passes, it confirms the fix correctly gates both scroll operations behind `if (!needsCartScrollLock())`
    - Run bug condition exploration test from step 1
    - **EXPECTED OUTCOME**: Test PASSES (confirms bug is fixed)
    - _Requirements: 2.1, 2.2_

  - [ ] 3.3 Verify preservation tests still pass
    - **Property 2: Preservation** - Desktop and Non-Add Behaviour Unchanged
    - **IMPORTANT**: Re-run the SAME tests from task 2 — do NOT write new tests
    - Run preservation property tests from step 2
    - **EXPECTED OUTCOME**: Tests PASS (confirms no regressions)
    - Confirm desktop Add still calls both scroll helpers, increment/decrement lock mechanism is unchanged, sidebar Add is unaffected, and cart badge/sidebar refresh work on all viewports

- [ ] 4. Checkpoint — Ensure all tests pass
  - Ensure all tests pass; ask the user if any questions arise
