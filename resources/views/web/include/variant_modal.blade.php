<style>
/* Modal Background and Shadow */
.custom-modal {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    border: none;
}

/* Header Styling */
.custom-modal-header {
    background: linear-gradient(90deg, #4caf50, #81c784);
    color: white;
    border-bottom: none;
}

/* Header Title */
.custom-modal-header .modal-title {
    font-weight: 600;
    font-size: 1.25rem;
}

/* Body Styling */
.modal-body {
    padding: 2rem;
    background-color: #f9f9f9;
}

/* Variant List (unused grid pattern — modal list scoped below) */
.unit-list .variant-item {
    padding: 0.8rem 1.2rem;
    border: 1px solid #ddd;
    border-radius: 8px;
    background-color: white;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 500;
}

/* Hover Effect */
.unit-list .variant-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    border-color: #4caf50;
    background-color: #e8f5e9;
}

/* Loading Text Styling */
.unit-list p {
    font-style: italic;
    color: #999;
}


/* ===============================
   BASE MODAL CENTERING (ALL DEVICES)
================================ */
#productModal.modal.show {
  display: flex !important;
  align-items: center;
  justify-content: center;
}

/* ===============================
   MODAL DIALOG SIZE
================================ */
#productModal .modal-dialog {
  width: 100%;
  max-width: 420px;
  margin: 12px;
}

/* ===============================
   MODAL CONTENT
================================ */
#productModal .modal-content {
  border-radius: 16px;
  border: none;
  overflow: hidden;
  box-shadow: 0 20px 50px rgba(0,0,0,0.18);
}

/* ===============================
   HEADER
================================ */
#productModal .modal-header {
  padding: 10px 14px;
  border-bottom: 1px solid #f0f0f0;
}

#productModal .modal-title {
  font-size: 16px;
  font-weight: 600;
  color: #222;
}

#productModal .btn-close {
  opacity: 0.8;
}

/* ===============================
   BODY
================================ */
#productModal .modal-body {
  padding: 12px 14px 14px;
}

#productModal .modal-body h6 {
  font-size: 14px;
  font-weight: 600;
  color: #555;
  margin-bottom: 12px;
}

/* ===============================
   VARIANT LIST (modal)
================================ */
#productModal .unit-list {
  display: flex;
  flex-direction: column;
  flex-wrap: nowrap;
  gap: 8px;
  justify-content: flex-start;
  align-items: stretch;
}

/* Card row: stack on mobile, row on larger phones / tablet */
#productModal .unit-item.variant-option-card {
  display: flex;
  flex-direction: column;
  align-items: stretch;
  gap: 8px;
  padding: 10px 12px;
  border-radius: 10px;
  border: 1px solid #eaeaea;
  background: #fff;
  transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

#productModal .unit-item.variant-option-card:hover {
  border-color: #ff6a00;
  box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
}

#productModal .variant-option-main {
  display: flex;
  flex-direction: row;
  align-items: center;
  gap: 10px;
  min-width: 0;
  flex: 1;
}

#productModal .variant-option-thumb {
  width: 56px;
  height: 56px;
  object-fit: contain;
  flex-shrink: 0;
  border-radius: 8px;
  background: #fafafa;
}

#productModal .variant-option-details {
  min-width: 0;
  flex: 1;
}

#productModal .variant-option-title {
  font-size: 14px;
  font-weight: 600;
  color: #222;
  line-height: 1.35;
  overflow-wrap: anywhere;
  word-wrap: break-word;
}

#productModal .variant-option-meta {
  font-size: 13px;
  margin-top: 2px;
}

#productModal .variant-option-prices {
  display: flex;
  flex-wrap: wrap;
  align-items: baseline;
  gap: 6px;
  margin-top: 4px;
}

#productModal .variant-option-prices .original-price {
  font-size: 13px;
  color: #9ca3af;
}

#productModal .variant-option-prices .price {
  font-size: 15px;
  color: #16a34a;
}

/* Actions column: full width when card stacks (default); mobile + tablet override below */
#productModal .variant-option-qty {
  width: 100%;
  flex-shrink: 0;
  box-sizing: border-box;
}

/*
  style.css (mobile) forces .qty-box { justify-content:flex-start; margin-right:auto }.
  Use flex + flex-end so Add / −1+ stay right after AJAX replaces inner HTML.
*/
#productModal .qty-box.variant-option-qty {
  display: flex !important;
  flex-direction: row !important;
  justify-content: flex-end !important;
  align-items: center !important;
  flex-wrap: nowrap !important;
  width: 100% !important;
  max-width: 100% !important;
  margin-left: 0 !important;
  margin-right: 0 !important;
  padding: 0 !important;
  min-height: 0 !important;
  box-sizing: border-box;
  text-align: right !important;
}

#productModal .qty-box.variant-option-qty .variant-actions-row {
  display: flex !important;
  flex: 1 1 auto;
  width: 100% !important;
  max-width: 100% !important;
  box-sizing: border-box;
  justify-content: flex-end !important;
}

#productModal .qty-box.variant-option-qty .qty-container {
  margin-left: 0 !important;
  margin-right: 0 !important;
  justify-content: center !important;
}

#productModal .variant-actions-row {
  display: flex;
  flex-wrap: nowrap;
  align-items: center;
  justify-content: space-between;
  gap: 10px;
  width: 100%;
}

#productModal .variant-actions-row--qty-only {
  justify-content: flex-end;
}

/* Match product-card mobile: orange Add + cart icon */
#productModal .variant-option-qty .add-btn {
  margin: 0;
  padding: 6px 12px !important;
  border-radius: 5px !important;
  font-size: 13px !important;
  font-weight: 500 !important;
  white-space: nowrap !important;
  flex-shrink: 0 !important;
  background: #E94412 !important;
  color: #fff !important;
  border: none !important;
  display: inline-flex !important;
  align-items: center !important;
  justify-content: center !important;
  min-width: 70px;
  max-width: 92px;
  height: 30px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

#productModal .variant-option-qty .add-btn:hover {
  background: #d63a0f !important;
  color: #fff !important;
}

#productModal .variant-add-cart-icon {
  width: 14px !important;
  height: 14px !important;
  margin-left: 4px !important;
  display: inline-block !important;
  vertical-align: middle;
  filter: brightness(0) invert(1);
}

/* Same − / qty / + as grid product cards */
#productModal .variant-option-qty .qty-container {
  display: inline-flex !important;
  align-items: center !important;
  justify-content: center !important;
  gap: 0 !important;
  flex-shrink: 0 !important;
  width: auto !important;
  margin: 0 !important;
  padding: 0 !important;
}

#productModal .variant-option-qty .qty-btn {
  background: #E94412 !important;
  color: #fff !important;
  border: none !important;
  width: 25px !important;
  height: 25px !important;
  border-radius: 4px !important;
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
  font-size: 18px !important;
  font-weight: 600 !important;
  cursor: pointer !important;
  padding: 0 !important;
  margin: 0 !important;
  flex-shrink: 0 !important;
  line-height: 1 !important;
}

#productModal .variant-option-qty .qty-btn:disabled {
  opacity: 0.45 !important;
  cursor: not-allowed !important;
}

#productModal .variant-option-qty .qty-input {
  width: 27px !important;
  height: 27px !important;
  text-align: center !important;
  border: 1px solid #ddd !important;
  border-left: none !important;
  border-right: none !important;
  border-radius: 0 !important;
  padding: 0 !important;
  margin: 0 !important;
  font-size: 14px !important;
  font-weight: 600 !important;
  flex-shrink: 0 !important;
}

/* Hover (legacy class names kept for compatibility) */
#productModal .unit-item .unit-name {
  font-size: 14px;
  font-weight: 500;
  color: #222;
}

#productModal .unit-item .unit-price {
  font-size: 14px;
  font-weight: 600;
  color: #16a34a;
}

#productModal .unit-item.active {
  border-color: #ff6a00;
  background: #fff3e8;
}

/* ===============================
   LOADING TEXT
================================ */
#variantList p {
  font-size: 14px;
}

/* =================================================
   📱 MOBILE OPTIMIZATION (MOST IMPORTANT PART)
================================================= */
@media (max-width: 575.98px) {

  /*
    style.css forces all .modal-dialog to full-width bottom sheet — reset only #productModal
    so it stays a centered, narrower popup.
  */
  #productModal.modal.fade .modal-dialog,
  #productModal.modal.show .modal-dialog {
    position: relative !important;
    bottom: auto !important;
    left: auto !important;
    right: auto !important;
    margin: 16px auto !important;
    width: 100% !important;
    max-width: min(348px, calc(100vw - 28px)) !important;
    transform: none !important;
  }

  #productModal .modal-content {
    border-radius: 14px !important;
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.16) !important;
  }

  #productModal .modal-body {
    padding: 10px 12px 12px;
  }

  #productModal .unit-list {
    gap: 6px;
  }

  /* Row card: text left, Add / qty pinned top-right (no tall stack under prices) */
  #productModal .unit-item.variant-option-card {
    flex-direction: row !important;
    flex-wrap: nowrap !important;
    align-items: flex-start !important;
    gap: 8px;
    padding: 8px 10px;
  }

  #productModal .variant-option-main {
    flex: 1 1 0 !important;
    min-width: 0 !important;
    max-width: none !important;
  }

  #productModal .variant-option-thumb {
    width: 48px;
    height: 48px;
  }

  #productModal .variant-option-title {
    font-size: 13px;
    line-height: 1.3;
  }

  #productModal .variant-option-meta {
    margin-top: 0;
    font-size: 12px;
    line-height: 1.25;
  }

  #productModal .variant-option-prices {
    margin-top: 2px;
    gap: 6px;
  }

  #productModal .variant-option-qty {
    width: auto !important;
    max-width: 118px !important;
    min-width: 0 !important;
    flex: 0 0 auto !important;
    align-self: flex-start !important;
    margin-left: auto !important;
  }

  #productModal .qty-box.variant-option-qty {
    display: flex !important;
    justify-content: flex-end !important;
    align-items: center !important;
    width: auto !important;
    max-width: 118px !important;
    margin-left: 0 !important;
    margin-right: 0 !important;
  }

  #productModal .qty-box.variant-option-qty .variant-actions-row {
    width: auto !important;
    max-width: 118px !important;
    min-width: 0 !important;
  }

  #productModal .variant-actions-row--add,
  #productModal .variant-actions-row--qty {
    display: flex !important;
    width: 100% !important;
    max-width: 100% !important;
    justify-content: flex-end !important;
    align-items: center !important;
    gap: 4px;
    box-sizing: border-box;
  }

  #productModal .variant-actions-row--add .add-btn {
    margin-left: 0 !important;
    margin-right: 0 !important;
  }

  #productModal .variant-actions-row--qty-only,
  #productModal .variant-actions-row--qty.variant-actions-row--qty-only {
    justify-content: flex-end !important;
  }

  #productModal .variant-actions-row--qty-only .qty-container {
    margin-left: 0 !important;
    margin-right: 0 !important;
  }
}

/* Tablet / desktop: title left, actions column right */
@media (min-width: 576px) {

  #productModal .modal-dialog {
    max-width: min(520px, calc(100vw - 48px));
  }

  #productModal .unit-item.variant-option-card {
    flex-direction: row;
    align-items: flex-start;
    flex-wrap: nowrap;
    gap: 16px;
    padding: 14px 16px;
  }

  /*
    Critical: base rule sets .variant-option-qty { width:100% }. In a row flex card that
    makes the qty column demand full modal width → .variant-option-main collapses to ~0
    (title stacks one letter per line). Reset width here.
  */
  #productModal .variant-option-main {
    flex: 1 1 0;
    min-width: 0;
    max-width: none;
  }

  #productModal .variant-option-details {
    min-width: 0;
  }

  #productModal .variant-option-qty {
    width: auto !important;
    max-width: 240px;
    min-width: 168px;
    flex: 0 0 auto;
    align-self: center;
    margin-left: auto;
  }

  #productModal .qty-box.variant-option-qty {
    width: auto !important;
    max-width: 240px !important;
    display: flex !important;
    justify-content: flex-end !important;
    align-items: center !important;
  }

  #productModal .variant-actions-row {
    justify-content: space-between;
  }

}

/* =================================================
   📱 VERY SMALL DEVICES
================================================= */
@media (max-width: 360px) {

  #productModal .modal-body {
    padding: 12px;
  }

  #productModal .unit-item.variant-option-card {
    padding: 8px 10px;
  }
}

</style>


<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">

         {{-- Header --}}
         <div class="modal-header">
            <h5 class="modal-title" id="productModalLabel">Loading...</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>

         {{-- Body --}}
         <div class="modal-body">
            <!-- <h6 class="mb-3">Select variant</h6> -->
            <div class="unit-list" id="variantList">
               <p class="text-center text-muted">Loading...</p>
            </div>
         </div>

      </div>
   </div>
</div>


