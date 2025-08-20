<?php include("include/header2.php")?>
<style>
.invoicebtn .btn{
  border-color:#E94412;
  background-color:#F5DED8;
}
.delivery-card {
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: #f9f9f9;
      border-radius: 12px;
      padding: 12px 16px;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
      /* max-width: 600px; */
      /* margin: 20px auto; */
    }

    .delivery-info {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .profile-img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      object-fit: cover;
    }

    .details {
      line-height: 1.2;
    }

    .details .name {
      font-weight: 600;
      font-size: 16px;
    }

    .details .company {
      font-size: 13px;
      color: #777;
    }

    .company span {
      font-weight: bold;
      color: #0073e6;
    }

    .call-icon {
      width: 36px;
      height: 36px;
      border: 2px solid #e65100;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #e65100;
      font-size: 16px;
    }

    .newbackground{
      background-color:#F9F9F9;
      padding:10px;
      border-radius:8px;
    }
    .newbackground .fa{
      font-size: 25px;
      margin: 10px 10px 0px 0px;
    }

    /* Responsive */
    @media (max-width: 480px) {
      .delivery-card {
        /* flex-direction: column; */
        align-items: flex-start;
        gap: 12px;
      }

      .call-icon {
        align-self: flex-end;
      }
    }
</style>
<!-- first section start  -->
<section class="extrapadding">
<div class="container py-4 bg-light">
  <div class="row g-3">
   <!-- Summary View -->


  <!-- Detailed View (Initially Hidden) -->
  <div class="">
    <div class="deorder-summary-box p-3 rounded shadow-sm mb-3 bg-white">
      <div class="d-flex justify-content-between">
        <div>
          <strong>Order Summary</strong><br>
          <div class="newbackground d-flex">
            <div>
            <i class="fa fa-store"></i>
            </div>
            <div>
            <small>Aryan Grocery</small><br>
            <small>Piyush Residency, Flora Enclave, Gangapuram, Ghaziabad</small><br>
            </div>
          </div>
          <small>GST: XXXX XXXX XXXX XXXX</small>
        </div>
        <div class="text-end">
          <strong>Arriving on: Sat 4 May 2025, 11:04 AM</strong><br>
          <small>Order ID: #34JS8793J282FF4</small><br>
          <small>Ordered on: Sat 4 May 2024, 9:20 PM</small>
        </div>
      </div>

      <hr>
      <div class="mb-3">
        <strong>2 items in this order</strong>
        <div class="d-flex justify-content-between align-items-center mt-2">
          <div class="d-flex align-items-center">
            <img src="images/product1.png" width="150" alt="Item">
            <div class="ms-3">
              <strong>Jackpot Cheese Balls - 100 gm</strong><br>
              ₹110 <small>(₹55 X 2)</small>
            </div>
          </div>
          <!-- <div>
          <button class="add-btn py-2" onclick="convertToQty(this)">Add Again <img src="images/cart.svg" alt="" class="ms-2"></button>
            <div class="text-center text-muted mt-1" style="font-size: 12px;">Order for ₹60 per unit</div>
          </div> -->
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
          <div class="d-flex align-items-center">
            <img src="images/product2.png" width="150" alt="Item">
            <div class="ms-3">
              <strong>Coca Cola - 400ml</strong><br>
              ₹40 <small>(₹40 X 1)</small>
            </div>
          </div>
          <!-- <div>
            <button class="add-btn py-2" onclick="convertToQty(this)">Add Again <img src="images/cart.svg" alt="" class="ms-2"></button>
            <div class="text-center text-muted mt-1" style="font-size: 12px;">Order for ₹60 per unit</div>
          </div> -->
        </div>
      </div>
      <div class="delivery-card">
        <div class="delivery-info">
        <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Aryan Kumar" class="profile-img" />
        <div class="details">
            <div class="name">Aryan Kumar</div>
            <div class="company">Delivered by <span style="color:green;">BLUE</span><span style="color:#0047ab;"> DART</span></div>
        </div>
        </div>
        <div class="call-icon">
        <i class="fas fa-phone-alt"></i>
        </div>
      </div>
      <div class="deorder-bill-summary mt-4">
        <h6 class="fw-bold mb-2">Bill Summary</h6>
        <div class="d-flex justify-content-between">
          <span><i class="fa fa-shopping-cart me-1"></i> Item charge</span>
          <span><s>₹380</s> ₹332</span>
        </div>
        <div class="d-flex justify-content-between">
          <span><i class="fa fa-truck me-1"></i> Delivery Charges</span>
          <span>₹20</span>
        </div>
        <div class="d-flex justify-content-between">
          <span><i class="fa fa-tag me-1"></i> Coupon Discount</span>
          <span>- ₹40</span>
        </div>
        <div class="d-flex justify-content-between">
          <span><i class="fa fa-wallet me-1"></i> Wallet Discount</span>
          <span>- ₹5</span>
        </div>
        <hr>
        <div class="d-flex justify-content-between align-items-center fw-bold">
          <span>Total Charges</span>
          <div class="text-end">
            <div>
              <span class="text-muted text-decoration-line-through">₹400</span>
              <span class="ms-1">₹347</span>
            </div>
            <span class="badge bg-success bg-opacity-10 text-success mt-1 rounded-pill px-2 py-1" style="font-weight: 500;">
              Saved ₹53
            </span>
          </div>
        </div>
      </div>

      <div class="text-end mt-3">
        <button class="btn text-danger"><h5><b>Cancel Order</b></h5></button>
      </div>
    </div>
  </div>
</div>
</div>
</section>


<!-- first section end  -->
<?php include("include/footer.php")?>