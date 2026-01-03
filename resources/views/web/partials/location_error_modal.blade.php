<!-- Location Error Modal -->
<div class="modal fade" id="locationErrorModal" tabindex="-1" aria-labelledby="locationErrorModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content location-error-modal">
            <div class="modal-header location-error-header">
                <h5 class="modal-title" id="locationErrorModalLabel">Service Not Available</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body location-error-body text-center">
                <div class="mb-4">
                    <i class="fas fa-exclamation-triangle location-error-icon"></i>
                </div>
                <h4 class="location-error-title mb-3">Sorry!</h4>
                <p class="location-error-text mb-3">We currently don't provide service in your selected area.</p>
                <p class="location-error-text mb-4">Please change your location to an area where we provide service.</p>
                <button class="btn location-error-btn" onclick="openLocationPopup()">
                    <i class="fas fa-map-marker-alt me-2"></i> Change Location
                </button>
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
