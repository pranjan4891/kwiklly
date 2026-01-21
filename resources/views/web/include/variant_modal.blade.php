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

/* Variant List */
.unit-list {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    justify-content: center;
}

/* Individual Variant Item */
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
  padding: 14px 16px;
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
  padding: 16px;
}

#productModal .modal-body h6 {
  font-size: 14px;
  font-weight: 600;
  color: #555;
  margin-bottom: 12px;
}

/* ===============================
   VARIANT LIST
================================ */
.unit-list {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

/* Each variant */
.unit-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 14px 14px;
  border-radius: 12px;
  border: 1px solid #eaeaea;
  background: #fff;
  cursor: pointer;
  transition: all 0.25s ease;
}

/* Hover (desktop) */
.unit-item:hover {
  border-color: #ff6a00;
  background: #fff7ed;
}

/* Active / Selected */
.unit-item.active {
  border-color: #ff6a00;
  background: #fff3e8;
}

/* Text */
.unit-item .unit-name {
  font-size: 14px;
  font-weight: 500;
  color: #222;
}

.unit-item .unit-price {
  font-size: 14px;
  font-weight: 600;
  color: #16a34a;
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
@media (max-width: 576px) {

  /* Full-width modal like apps */
  #productModal .modal-dialog {
    max-width: 100%;
    margin: 0;
  }

  /* Bottom safe spacing */
  #productModal .modal-content {
    border-radius: 14px;
  }

  /* Bigger tap targets */
  .unit-item {
    padding: 16px 14px;
  }

  .unit-item .unit-name,
  .unit-item .unit-price {
    font-size: 15px;
  }
}

/* =================================================
   📱 VERY SMALL DEVICES
================================================= */
@media (max-width: 360px) {

  #productModal .modal-body {
    padding: 14px;
  }

  .unit-item {
    padding: 14px 12px;
  }

  .unit-item .unit-name,
  .unit-item .unit-price {
    font-size: 14px;
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
            <h6 class="mb-3">Select varient</h6>
            <div class="unit-list" id="variantList">
               <p class="text-center text-muted">Loading...</p>
            </div>
         </div>

      </div>
   </div>
</div>


