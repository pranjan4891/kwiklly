<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add New Address</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

  <style>
    body {
      font-family: sans-serif;
    }

    .pata-add-new-address {
      padding: 10px 20px;
      background-color: #f97316;
      color: #fff;
      border-radius: 8px;
      display: inline-block;
      cursor: pointer;
      margin: 20px;
      font-weight: 600;
    }

    .pata-sidebar-overlay {
      position: fixed;
      top: 0;
      right: -100%;
      width: 100%;
      max-width: 400px;
      height: 100%;
      background: #fff;
      z-index: 1050;
      box-shadow: -4px 0 10px rgba(0, 0, 0, 0.2);
      transition: right 0.4s ease-in-out;
      overflow-y: auto;
    }

    .pata-sidebar-overlay.active {
      right: 0;
    }

    .pata-sidebar-header {
      display: flex;
      align-items: center;
      padding: 15px;
      border-bottom: 1px solid #ddd;
    }

    .pata-back-btn {
      font-size: 22px;
      cursor: pointer;
      margin-right: 10px;
    }

    #pata-map {
      height: 200px;
      width: 100%;
      border-radius: 10px;
      margin: 10px 0;
    }

    .pata-location-title {
      font-weight: 600;
      margin-top: 10px;
      margin-bottom: 4px;
    }

    .pata-location-desc {
      color: #555;
      font-size: 14px;
      margin-bottom: 15px;
    }

    .pata-tag-buttons button {
      width: 48%;
      font-weight: 600;
      border: 2px solid transparent;
      border-radius: 10px;
      padding: 10px;
      margin-bottom: 15px;
      font-size: 14px;
      transition: all 0.2s;
    }

    .pata-home {
      background: #f0fdf4;
      color: #22c55e;
    }

    .pata-home.active {
      border-color: #22c55e;
      color: #22c55e;
    }

    .pata-work {
      background: #fff7ed;
      color: #f97316;
    }

    .pata-work.active {
      border-color: #f97316;
      color: #f97316;
    }

    .pata-input input {
      width: 100%;
      padding: 10px;
      border-radius: 8px;
      border: 1px solid #ccc;
      margin-bottom: 12px;
      font-size: 14px;
    }

    .pata-save-btn {
      width: 100%;
      background: #f97316;
      color: white;
      padding: 12px;
      border-radius: 25px;
      border: none;
      font-weight: bold;
      margin-top: 10px;
      font-size: 16px;
    }

    @media (max-width: 576px) {
      .pata-sidebar-overlay {
        width: 100%;
      }
    }
  </style>
</head>
<body>

<!-- Add New Address Button -->
<div class="pata-add-new-address" onclick="openPataSidebar()">Add New Address</div>

<!-- Sidebar -->
<div class="pata-sidebar-overlay" id="pataSidebar">
  <div class="pata-sidebar">
    <!-- Header -->
    <div class="pata-sidebar-header">
      <span class="pata-back-btn" onclick="closePataSidebar()">&#8592;</span>
      <h5 class="mb-0">New Address</h5>
    </div>

    <!-- Body -->
    <div class="p-3">
      <!-- Location input -->
      <input type="text" class="form-control mb-2" placeholder="Your Location">

      <!-- Leaflet Map -->
      <div id="pata-map"></div>

      <!-- Location address text -->
      <div class="pata-location-title">Your Location</div>
      <div class="pata-location-desc">
        Cisf ground, gali no 2, near metro station gate no 3, saket, Delhi
      </div>

      <!-- Buttons: Home / Work -->
      <div class="d-flex justify-content-between pata-tag-buttons">
        <button id="pataHomeBtn" class="pata-home">🏠 Home</button>
        <button id="pataWorkBtn" class="pata-work">🏢 Work</button>
      </div>

      <!-- Form Inputs -->
      <div class="pata-input"><input type="text" placeholder="Area / Sector / Locality*" class="form-control"></div>
      <div class="pata-input"><input type="text" placeholder="Flat / Building no*" class="form-control"></div>
      <div class="pata-input"><input type="text" placeholder="Landmark (optional)" class="form-control"></div>
      <div class="pata-input"><input type="text" placeholder="Pincode*" class="form-control"></div>
      <div class="pata-input"><input type="text" placeholder="Name*" class="form-control"></div>
      <div class="pata-input"><input type="text" placeholder="Phone Number*" class="form-control"></div>
      <div class="pata-input"><input type="text" placeholder="Alternate Phone Number (optional)" class="form-control"></div>

      <!-- Save Button -->
      <button class="pata-save-btn">Save Address</button>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
  function openPataSidebar() {
    document.getElementById("pataSidebar").classList.add("active");
    setTimeout(initMap, 100); // Delay to ensure sidebar is visible
  }

  function closePataSidebar() {
    document.getElementById("pataSidebar").classList.remove("active");
  }

  document.addEventListener("DOMContentLoaded", function () {
    const homeBtn = document.getElementById("pataHomeBtn");
    const workBtn = document.getElementById("pataWorkBtn");

    homeBtn.addEventListener("click", function () {
      homeBtn.classList.add("active");
      workBtn.classList.remove("active");
    });

    workBtn.addEventListener("click", function () {
      workBtn.classList.add("active");
      homeBtn.classList.remove("active");
    });
  });

</script>

</body>
</html>
