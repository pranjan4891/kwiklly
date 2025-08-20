<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Aryan Grocery</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
  <style>
    body, html {
      margin: 0;
      padding: 0;
    }

    .grocery-banner {
      background: url('./images/departmentimg.jpg') no-repeat center center/cover;
      min-height: 100vh;
      position: relative;
      color: #fff;
      display: flex;
      align-items: center;
    }

    .grocery-overlay {
      background: linear-gradient(to top, rgba(28, 77, 24, 0.95), rgba(28, 77, 24, 0.6), transparent);
      width: 100%;
      padding: 2rem 1rem;
    }

    .fssai-logo {
      width: 70px;
    }

    .coupon-tag {
      position: absolute;
      top: 20px;
      right: 20px;
      background-color: white;
      color: orange;
      font-weight: bold;
      padding: 5px 15px;
      border-radius: 20px;
      box-shadow: 0 0 5px rgba(0,0,0,0.2);
    }

    .grocery-info h3 {
      font-weight: 700;
      margin-top: 1rem;
    }

    .location-text {
      font-size: 15px;
      font-weight: 500;
    }

    .timing-badge {
      background-color: rgba(255, 255, 255, 0.15);
      padding: 10px 15px;
      border: 1px solid white;
      border-radius: 25px;
      font-size: 14px;
      margin-top: 10px;
      display: inline-block;
    }

    .coupon-card {
      background: #fff;
      border-radius: 10px;
      padding: 15px;
      margin-top: 1rem;
      color: #000;
      box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }

    .coupon-card img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      object-fit: cover;
      margin-right: 10px;
    }

    .coupon-text {
      font-size: 14px;
    }

    .coupon-text strong {
      font-weight: 600;
    }

    .progress {
      height: 5px;
      background-color: #e9ecef;
      margin-top: 8px;
    }

    .progress-bar {
      background-color: green;
    }

    @media (min-width: 768px) {
      .grocery-overlay {
        padding: 3rem;
      }   
      .desktop-layout {
        display: flex;
        align-items: center;
        justify-content: space-between;
      }

      .left-section {
        flex: 1;
        padding-right: 2rem;
      }

      .right-section {
        width: 350px;
        flex-shrink: 0;
      }

      .coupon-card {
        margin-top: 0;
      }
      .grocery-banner {
      background: url('./images/departmentimg.jpg') no-repeat center center/cover;
      min-height: 40vh;
      position: relative;
      color: #fff;
      display: flex;
      align-items: center;
    }
    }
  </style>
</head>
<body>
  <div class="grocery-banner">
    <div class="coupon-tag">Coupons</div>

    <div class="grocery-overlay">
      <div class="container">
        <div class="desktop-layout">
          <!-- Left -->
          <div class="left-section text-white">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/4/4b/FSSAI.svg/1200px-FSSAI.svg.png" class="fssai-logo" alt="fssai" />
            <h3>Aryan Grocery</h3>
            <div class="location-text mb-2">
              <i class="fas fa-map-marker-alt me-1"></i> Comfort pg, Saket, Delhi, 110017
            </div>
            <div class="timing-badge">Wednesday 9:30 AM - 10:00 PM</div>
          </div>

          <!-- Right -->
          <div class="right-section">
            <div class="coupon-card">
              <div class="d-flex align-items-center mb-2">
                <img src="https://images.unsplash.com/photo-1503602642458-232111445657?auto=format&fit=crop&w=60&q=60" alt="icon" />
                <div class="coupon-text">Add Item worth <strong>₹5000</strong> to get free cook</div>
              </div>
              <div class="progress mb-3">
                <div class="progress-bar" style="width: 70%"></div>
              </div>

              <div class="coupon-text">Add Item worth <strong>₹60</strong> more to get free delivery</div>
              <div class="progress">
                <div class="progress-bar" style="width: 30%"></div>
              </div>
            </div>
          </div>
        </div> <!-- desktop-layout -->
      </div>
    </div>
  </div>
</body>
</html>
