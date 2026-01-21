<!-- Location Error Modal -->

  <style>
    /* ===============================
       MODAL CENTER FIX (SAFE)
    =============================== */
    .modal.show {
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
    }

    .modal {
      z-index: 1055;
    }

    .modal-backdrop {
      z-index: 1050;
    }

    body.modal-open {
      overflow: hidden;
      padding-right: 0 !important;
    }

    /* ===============================
       MODAL WIDTH
    =============================== */
    .locerr-dialog {
      width: 100%;
      max-width: 420px;
      padding: 16px;
      margin: 0;
    }

    /* ===============================
       MODAL CARD
    =============================== */
    .locerr-modal {
      border-radius: 18px;
      border: none;
      box-shadow: 0 20px 50px rgba(0,0,0,0.18);
      background: #fff;
    }

    /* ===============================
       HEADER
    =============================== */
    .locerr-header {
      position: relative;
    }

    .locerr-header .btn-close {
      position: absolute;
      top: 14px;
      right: 14px;
      z-index: 2;
    }

    /* ===============================
       BODY
    =============================== */
    .locerr-body {
      padding: 28px 24px 34px;
    }

    /* ===============================
       ICON
    =============================== */
    .locerr-icon-wrap {
      width: 88px;
      height: 88px;
      margin: 0 auto 18px;
      background: linear-gradient(135deg, #ff6a00, #ff3d00);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .locerr-icon-wrap i {
      font-size: 34px;
      color: #fff;
    }

    /* ===============================
       TEXT
    =============================== */
    .locerr-title {
      font-size: clamp(18px, 4vw, 22px);
      font-weight: 700;
      color: #222;
      margin-bottom: 6px;
    }

    .locerr-desc {
      font-size: 15px;
      color: #555;
      margin-bottom: 4px;
    }

    .locerr-subdesc {
      font-size: 14px;
      color: #777;
      margin-bottom: 22px;
    }

    /* ===============================
       BUTTON
    =============================== */
    .locerr-btn {
      background: linear-gradient(135deg, #ff6a00, #ff3d00);
      color: #fff;
      font-weight: 600;
      padding: 12px;
      border-radius: 28px;
      border: none;
      width: 100%;
      transition: all 0.3s ease;
    }

    .locerr-btn:hover {
      background: linear-gradient(135deg, #e85c00, #d93700);
      transform: translateY(-2px);
      color: #fff;
    }

    /* Desktop button */
    @media (min-width: 576px) {
      .locerr-btn {
        width: auto;
        padding: 12px 32px;
      }
    }

    /* ===============================
       FOOTER TEXT
    =============================== */
    .locerr-footer {
      font-size: 13px;
      color: #999;
      margin-top: 16px;
    }

    /* ===============================
       SMALL MOBILE
    =============================== */
    @media (max-width: 360px) {
      .locerr-icon-wrap {
        width: 72px;
        height: 72px;
      }

      .locerr-icon-wrap i {
        font-size: 28px;
      }

      .locerr-body {
        padding: 22px 18px 26px;
      }
    }
  </style>
<div class="modal fade" id="locationErrorModal" tabindex="-1"
     aria-hidden="true"
     data-bs-backdrop="static"
     data-bs-keyboard="false">

  <div class="modal-dialog modal-dialog-centered locerr-dialog">
    <div class="modal-content locerr-modal">

      <div class="locerr-header">
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body locerr-body text-center">

        <div class="locerr-icon-wrap">
          <i class="fas fa-location-crosshairs"></i>
        </div>

        <h4 class="locerr-title">Service Not Available 😔</h4>

        <p class="locerr-desc">
          We’re not delivering to this location yet.
        </p>

        <p class="locerr-subdesc">
          Please select a nearby area where service is available.
        </p>

        <button class="btn locerr-btn w-100 w-sm-auto"
                onclick="openLocationPopup()">
          <i class="fas fa-map-marker-alt me-2"></i> Change Location
        </button>

        <p class="locerr-footer">
          Coming soon to your area ❤️
        </p>

      </div>
    </div>
  </div>
</div>


<script>
    function openLocationPopup() {
        // Close the error modal first
        const modalElement = document.getElementById('locationErrorModal');
        if (modalElement) {
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) {
                modal.hide();
            }
        }

        // Open the location selection popup
        setTimeout(function() {
            if (typeof toggleAddpop === 'function') {
        toggleAddpop(event);
            }
        }, 300);
    }

    // Check location on page load and show modal if outside master area
    function checkLocationAndShowModal() {
        const savedLocation = localStorage.getItem("userLocation");
        if (!savedLocation) {
            return; // No location saved yet
        }

        try {
            const loc = JSON.parse(savedLocation);
            if (loc.lat && loc.lng) {
                // Check if location is in master area
                $.ajax({
                    url: '{{ route("check.location.in.master") }}',
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        latitude: loc.lat,
                        longitude: loc.lng
                    },
                    success: function(response) {
                        console.log("Location check response:", response);
                        if (response && response.is_in_master_area === false) {
                            // Show modal if location is outside master area
                            console.log("Location is outside master area, showing modal");
                            const modalElement = document.getElementById('locationErrorModal');
                            if (modalElement) {
                                // Hide any existing modal instance first
                                const existingModal = bootstrap.Modal.getInstance(modalElement);
                                if (existingModal) {
                                    existingModal.hide();
                                }
                                
                                // Use Bootstrap 5 modal
                                const modal = new bootstrap.Modal(modalElement, {
                                    backdrop: 'static',
                                    keyboard: false
                                });
                                modal.show();
                                
                                // Force show if modal doesn't appear
                                setTimeout(function() {
                                    if (!modalElement.classList.contains('show')) {
                                        $(modalElement).modal('show');
                                    }
                                }, 100);
                            } else {
                                console.error("Location error modal element not found");
                            }
                        } else {
                            console.log("Location is in master area");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error checking location:", error);
                    }
                });
            }
        } catch (e) {
            console.error("Error parsing saved location:", e);
        }
    }

    // Check location when page loads
    $(document).ready(function() {
        // Wait a bit for page to fully load and check location
        setTimeout(function() {
            checkLocationAndShowModal();
        }, 1000);
    });
    
    // Also check when location is detected/updated
    if (typeof window !== 'undefined') {
        // Override updateLocation to check after location update
        const originalUpdateLocation = window.updateLocation;
        if (originalUpdateLocation) {
            window.updateLocation = function(fullAddress, place, lat, lng, isAutoDetect) {
                originalUpdateLocation(fullAddress, place, lat, lng, isAutoDetect);
                // Check location after update
                if (lat && lng) {
                    setTimeout(function() {
                        if (typeof checkLocationInMasterArea === 'function') {
                            checkLocationInMasterArea(lat, lng);
                        }
                    }, 1500);
                }
            };
        }
    }
</script>
