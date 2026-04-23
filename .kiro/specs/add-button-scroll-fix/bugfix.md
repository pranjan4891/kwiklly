# Bugfix Requirements Document

## Introduction

On mobile view (viewport width ≤ 1023px), tapping the "Add" button on a product card causes an unwanted two-phase scroll: the page first scrolls upward, then scrolls back downward before the quantity controls appear. This happens because the AJAX success handler calls `scrollQtyBoxIntoViewNearest()` — which scrolls the newly-rendered qty box into view — and then the `complete` callback calls `maybeCorrectScrollAfterAdd()` to restore the original scroll position. These two competing scroll operations produce the visible up-then-down jump. The fix must eliminate the scroll entirely: tapping "Add" should add the item to the mini cart and replace the button with quantity controls without moving the page at all.

## Bug Analysis

### Current Behavior (Defect)

1.1 WHEN a user on mobile (viewport ≤ 1023px) taps the "Add" button on a product card THEN the system scrolls the page upward immediately after the AJAX response is received (triggered by `scrollQtyBoxIntoViewNearest` in the success callback)

1.2 WHEN a user on mobile (viewport ≤ 1023px) taps the "Add" button on a product card THEN the system scrolls the page back downward after the upward scroll (triggered by `maybeCorrectScrollAfterAdd` in the complete callback), resulting in a visible up-then-down scroll sequence before the quantity controls are shown

### Expected Behavior (Correct)

2.1 WHEN a user on mobile (viewport ≤ 1023px) taps the "Add" button on a product card THEN the system SHALL add the item to the mini cart and replace the "Add" button with quantity controls without scrolling the page in any direction

2.2 WHEN a user on mobile (viewport ≤ 1023px) taps the "Add" button on a product card THEN the system SHALL keep the page scroll position at the exact position it was at the moment of the tap, both during and after the AJAX operation completes

### Unchanged Behavior (Regression Prevention)

3.1 WHEN a user on desktop (viewport ≥ 1024px) taps the "Add" button on a product card THEN the system SHALL CONTINUE TO add the item to the cart and open the desktop side cart panel as before

3.2 WHEN a user on mobile taps the "+" (increment) or "−" (decrement) quantity buttons THEN the system SHALL CONTINUE TO update the cart quantity and preserve the scroll position using the existing `lockBodyScrollMobile` / `unlockBodyScrollMobile` mechanism

3.3 WHEN a user on mobile taps the "Add" button inside the mobile cart sidebar (`#cartSidebar2`) THEN the system SHALL CONTINUE TO operate without applying any page-level scroll lock or correction (sidebar scroll context is separate)

3.4 WHEN a user on mobile taps the "Add" button on a product with multiple variants THEN the system SHALL CONTINUE TO open the variant selection modal without scrolling the page

3.5 WHEN the cart is updated via the "Add" button on any viewport THEN the system SHALL CONTINUE TO update the cart count badge, refresh the mini cart sidebar contents, and replace the "Add" button with quantity controls (`−`, qty input, `+`)
