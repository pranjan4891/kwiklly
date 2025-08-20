@extends('admin.includes.main')

@section('main')
<style>
    #map_canvas {
        height: 500px;
        width: 100%;
        border: 1px solid #ccc;
        border-radius: 4px;
        margin-top: 20px;
    }
    .map-instructions {
        background: #f8f9fa;
        padding: 10px;
        border-radius: 4px;
        margin-bottom: 15px;
        border-left: 4px solid #17a2b8;
    }
    #serviceAreaCoords {
        width: 100%;
        min-height: 100px;
        margin-top: 10px;
        font-family: monospace;
    }
    .drawing-controls {
        margin: 15px 0;
    }
    .btn-draw {
        margin-right: 10px;
    }
    .coordinates-display {
        background: #f8f9fa;
        padding: 10px;
        border-radius: 4px;
        margin-top: 10px;
        font-family: monospace;
        white-space: pre-wrap;
    }
</style>

<div class="wraper container-fluid">
    <div class="page-title">
        <h3 class="title">Create New Branch</h3>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Branch Form</h3>

                </div>
                 <div class="col-sm-12" style="margin-top: 10px;">

                     {{-- Display validation errors --}}
                     @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                     @endif

                     {{-- Display success or error messages --}}
                     @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                     @endif
                     @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                     @endif
                  </div>

                <div class="panel-body">
                    <form action="{{ route('admin.branch.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                          <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Branch Name <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" id="branch_name" name="branch_name" value="{{ old('branch_name') }}" required>
                                @error('branch_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Branch Phone <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                                <input type="text" name="phone" class="form-control" maxlength="10" value="{{ old('phone') }}" required>
                                @error('phone')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Branch Email <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">GSTIN</label>
                            <div class="col-lg-8">
                                <input type="text" name="gstin" class="form-control" maxlength="15" value="{{ old('gstin') }}">
                                @error('gstin')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">PAN Number</label>
                            <div class="col-lg-8">
                                <input type="text" name="pan_number" class="form-control" maxlength="10" value="{{ old('pan_number') }}">
                                @error('pan_number')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">PAN Image</label>
                            <div class="col-lg-8">
                                <input type="file" name="pan_image" class="form-control" accept="image/*">
                                @error('pan_image')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Bank Name</label>
                            <div class="col-lg-8">
                                <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name') }}">
                                @error('bank_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Bank Branch</label>
                            <div class="col-lg-8">
                                <input type="text" name="bank_branch" class="form-control" value="{{ old('bank_branch') }}">
                                @error('bank_branch')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Account Holder Name</label>
                            <div class="col-lg-8">
                                <input type="text" name="account_holder_name" class="form-control" value="{{ old('account_holder_name') }}">
                                @error('account_holder_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Account Number</label>
                            <div class="col-lg-8">
                                <input type="text" name="account_number" class="form-control" value="{{ old('account_number') }}" maxlength="20">
                                @error('account_number')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Cancel Cheque Image</label>
                            <div class="col-lg-8">
                                <input type="file" name="cancel_cheque_image" class="form-control" accept="image/*">
                                @error('cancel_cheque_image')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">IFSC Code</label>
                            <div class="col-lg-8">
                                <input type="text" name="ifsc_code" class="form-control" maxlength="11" value="{{ old('ifsc_code') }}">
                                @error('ifsc_code')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Landmark<span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                                <input type="text" name="landmark" class="form-control" value="{{ old('landmark') }}" required>
                                @error('landmark')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Branch Address <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                                <textarea class="form-control" name="branch_address" id="branch_address" rows="2" required>{{ old('branch_address') }}</textarea>
                                @error('branch_address')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">State <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                                <select name="state" id="state" class="form-control" required>
                                    <option value="">Select State</option>
                                    @foreach($states as $state)
                                        <option value="{{ $state->id }}" {{ old('state') == $state->id ? 'selected' : '' }}>{{ $state->state_name }}</option>
                                    @endforeach
                                </select>
                                @error('state')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">City <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                                <select name="city" id="city" class="form-control" required>
                                    <option value="">Select City</option>
                                </select>
                                @error('city')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Pincode <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" id="postal_code" name="postal_code" maxlength="6" required value="{{ old('postal_code') }}">
                                @error('postal_code')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Place</label>
                            <div class="col-lg-8">
                                <select id="place" name="place" class="form-control" disabled>
                                    <option value="">Select Place</option>
                                </select>
                                @error('place')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <input type="hidden" name="place_name" id="place_name" value="{{ old('place_name') }}">
                        <input type="hidden" name="lat_long" id="lat_long" value="{{ old('lat_long') }}">
                        <input type="hidden" name="service_area" id="service_area">

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Latitude</label>
                            <div class="col-lg-8">
                                <input type="text" id="latitude" name="latitude" class="form-control" required value="{{ old('latitude') }}" readonly>
                                @error('latitude')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Longitude</label>
                            <div class="col-lg-8">
                                <input type="text" id="longitude" name="longitude" class="form-control" required value="{{ old('longitude') }}" readonly>
                                @error('longitude')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Service Area</label>
                            <div class="col-lg-8">
                                <div class="map-instructions">
                                    <p><strong>Instructions:</strong></p>
                                    <ol>
                                        <li>First select a place to load the boundary (blue area)</li>
                                        <li>Click on the map to set your branch location (red marker)</li>
                                        <li>Click "Draw Service Area" to create a custom polygon within the boundary</li>
                                        <li>Click on the map to add points to your polygon</li>
                                        <li>Double-click to complete the polygon</li>
                                        <li>Drag points to adjust the shape</li>
                                    </ol>
                                </div>

                                <div class="drawing-controls">
                                    <button type="button" id="btnDraw" class="btn btn-primary btn-draw" disabled>
                                        <i class="fa fa-draw-polygon"></i> Draw Service Area
                                    </button>
                                    <button type="button" id="btnClear" class="btn btn-danger" disabled>
                                        <i class="fa fa-trash"></i> Clear Drawing
                                    </button>
                                </div>

                                <div class="coordinates-display">
                                    <label>Service Area Coordinates:</label>
                                    <textarea id="serviceAreaCoords" name="service_area" class="form-control" rreadonly required placeholder="Service area coordinates will appear here...">{{ old('service_area') }}</textarea>
                                </div>
                                @error('service_area')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-lg-offset-2 col-lg-10">
                                <button type="submit" class="btn btn-success">
                                    <i class="fa fa-save"></i> Save Branch
                                </button>
                                <button type="reset" class="btn btn-default">
                                    <i class="fa fa-undo"></i> Reset
                                </button>
                            </div>
                        </div>
                    </form>

                    <div id="map_canvas" class="mt-4"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=geometry,drawing"></script>
<script>
    let map;
    let marker = null;
    let boundaryPolygon = null;
    let serviceAreaPolygon = null;
    let drawingManager = null;
    let isDrawing = false;

    function initializeMap() {
        map = new google.maps.Map(document.getElementById("map_canvas"), {
            center: { lat: 22.9734, lng: 78.6569 },
            zoom: 5,
            mapTypeId: 'roadmap',
            gestureHandling: 'greedy'
        });

        map.addListener("click", function(event) {
            if (!isDrawing) {
                handleMapClick(event.latLng);
            }
        });
    }

    function initDrawingTools() {
        drawingManager = new google.maps.drawing.DrawingManager({
            drawingMode: null,
            drawingControl: false,
            polygonOptions: {
                editable: true,
                draggable: false,
                strokeColor: '#FF0000',
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: '#FF0000',
                fillOpacity: 0.35,
                clickable: false
            }
        });
        drawingManager.setMap(map);

        google.maps.event.addListener(drawingManager, 'polygoncomplete', function(polygon) {
            handlePolygonComplete(polygon);
        });
    }

    function handlePolygonComplete(polygon) {
        // Check if the polygon is within the boundary
        if (boundaryPolygon) {
            const path = polygon.getPath();
            const allInside = checkPolygonInBoundary(path);

            if (!allInside) {
                alert("Your polygon extends beyond the allowed boundary. Please draw within the blue area.");
                polygon.setMap(null);
                return;
            }
        }

        // Clear previous service area
        if (serviceAreaPolygon) {
            serviceAreaPolygon.setMap(null);
        }
        serviceAreaPolygon = polygon;

        // Store the polygon coordinates
        storePolygonCoordinates(polygon);

        // Make the polygon editable
        makePolygonEditable(polygon);

        // Reset drawing state
        isDrawing = false;
        document.getElementById('btnDraw').innerHTML = '<i class="fa fa-draw-polygon"></i> Draw Service Area';
        document.getElementById('btnDraw').classList.remove('btn-warning');
        document.getElementById('btnDraw').classList.add('btn-primary');
    }

    function checkPolygonInBoundary(path) {
        for (let i = 0; i < path.getLength(); i++) {
            const point = path.getAt(i);
            if (!google.maps.geometry.poly.containsLocation(point, boundaryPolygon)) {
                return false;
            }
        }
        return true;
    }

    function storePolygonCoordinates(polygon) {
        const path = polygon.getPath();
        const coordinates = [];

        for (let i = 0; i < path.getLength(); i++) {
            const point = path.getAt(i);
            coordinates.push({
                lat: point.lat(),
                lng: point.lng()
            });
        }

        document.getElementById("serviceAreaCoords").value = JSON.stringify(coordinates, null, 2);
    }

    function makePolygonEditable(polygon) {
        // Add listener for edits
        polygon.getPath().addListener('set_at', function() {
            if (boundaryPolygon && !checkPolygonInBoundary(polygon.getPath())) {
                alert("You cannot extend the polygon beyond the boundary.");
                // Revert the edit
                this.setAt(this.length - 1, this.getAt(this.length - 1));
                return;
            }
            storePolygonCoordinates(polygon);
        });

        // Add listener for new points
        polygon.getPath().addListener('insert_at', function() {
            if (boundaryPolygon && !checkPolygonInBoundary(polygon.getPath())) {
                alert("You cannot extend the polygon beyond the boundary.");
                // Remove the new point
                this.removeAt(this.length - 1);
                return;
            }
            storePolygonCoordinates(polygon);
        });
    }

    function handleMapClick(clickedLatLng) {
        if (!boundaryPolygon) {
            alert("Please select a place first to load the area boundary.");
            return;
        }

        const isInside = google.maps.geometry.poly.containsLocation(clickedLatLng, boundaryPolygon);

        if (!isInside) {
            alert("The selected location is outside the defined area.");
            return;
        }

        placeMarker(clickedLatLng);

        // Enable drawing tools after marker is placed
        document.getElementById('btnDraw').disabled = false;
        document.getElementById('btnClear').disabled = false;
    }

    function placeMarker(position) {
        if (marker) marker.setMap(null);

        marker = new google.maps.Marker({
            position: position,
            map: map,
            title: "Branch Location",
            draggable: true,
            icon: {
                url: "http://maps.google.com/mapfiles/ms/icons/red-dot.png"
            }
        });

        // Update coordinates in form
        document.getElementById("latitude").value = position.lat();
        document.getElementById("longitude").value = position.lng();

        // When marker is dragged, check if still within boundary
        marker.addListener('dragend', function() {
            const newPosition = marker.getPosition();
            if (!google.maps.geometry.poly.containsLocation(newPosition, boundaryPolygon)) {
                alert("Marker must stay within the boundary area.");
                marker.setPosition(position);
                return;
            }
            document.getElementById("latitude").value = newPosition.lat();
            document.getElementById("longitude").value = newPosition.lng();
        });

        // Center map on marker
        map.setCenter(position);
        map.setZoom(16);
    }

    function drawPolygonBoundary(coords) {
        if (boundaryPolygon) boundaryPolygon.setMap(null);

        const path = coords.map(coord => new google.maps.LatLng(
            parseFloat(coord.lat), parseFloat(coord.lng)
        ));

        boundaryPolygon = new google.maps.Polygon({
            paths: path,
            strokeColor: "#0000FF",
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: "#0000FF",
            fillOpacity: 0.2,
            map: map,
            clickable: false
        });

        // Fit the map to the bounds of the polygon
        const bounds = new google.maps.LatLngBounds();
        path.forEach(coord => bounds.extend(coord));
        map.fitBounds(bounds);

        // Store the boundary coordinates
        document.getElementById("lat_long").value = JSON.stringify(coords);
    }

    function toggleDrawing() {
        if (!isDrawing) {
            // Start drawing
            isDrawing = true;
            drawingManager.setDrawingMode(google.maps.drawing.OverlayType.POLYGON);
            document.getElementById('btnDraw').innerHTML = '<i class="fa fa-stop-circle"></i> Finish Drawing (double-click)';
            document.getElementById('btnDraw').classList.remove('btn-primary');
            document.getElementById('btnDraw').classList.add('btn-warning');
        } else {
            // Cancel drawing
            isDrawing = false;
            drawingManager.setDrawingMode(null);
            document.getElementById('btnDraw').innerHTML = '<i class="fa fa-draw-polygon"></i> Draw Service Area';
            document.getElementById('btnDraw').classList.remove('btn-warning');
            document.getElementById('btnDraw').classList.add('btn-primary');
        }
    }

    function clearDrawing() {
        if (serviceAreaPolygon) {
            serviceAreaPolygon.setMap(null);
            serviceAreaPolygon = null;
            document.getElementById("serviceAreaCoords").value = '';
        }
        if (isDrawing) {
            drawingManager.setDrawingMode(null);
            isDrawing = false;
            document.getElementById('btnDraw').innerHTML = '<i class="fa fa-draw-polygon"></i> Draw Service Area';
            document.getElementById('btnDraw').classList.remove('btn-warning');
            document.getElementById('btnDraw').classList.add('btn-primary');
        }
    }

    document.addEventListener("DOMContentLoaded", () => {
        initializeMap();
        initDrawingTools();

        // Drawing control buttons
        document.getElementById('btnDraw').addEventListener('click', toggleDrawing);
        document.getElementById('btnClear').addEventListener('click', clearDrawing);

        // State and city dropdown handling
        document.getElementById('state').addEventListener('change', function() {
            const stateId = this.value;
            const url = "{{ route('admin.get.cities', ':id') }}".replace(':id', stateId);

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const citySelect = document.getElementById("city");
                    citySelect.innerHTML = `<option value="">Select City</option>`;
                    data.forEach(city => {
                        citySelect.innerHTML += `<option value="${city.id}">${city.city_name}</option>`;
                    });
                });
        });

        // Pincode and place handling
        document.getElementById('postal_code').addEventListener('blur', function() {
            const pin = this.value.trim();
            const placeDropdown = document.getElementById('place');
            placeDropdown.innerHTML = '<option value="">Select Place</option>';
            placeDropdown.disabled = true;

            if (pin.length === 6) {
                fetch("{{ route('admin.get.area') }}?pincode=" + pin)
                    .then(res => res.json())
                    .then(data => {
                        const places = data.places || data;
                        if (Array.isArray(places)) {
                            places.forEach(loc => {
                                if (loc.place && loc.lat_long) {
                                    try {
                                        const coords = JSON.parse(loc.lat_long);
                                        if (Array.isArray(coords) && coords.length >= 3) {
                                            const option = document.createElement('option');
                                            option.value = JSON.stringify(coords);
                                            option.text = loc.place;
                                            option.setAttribute('data-place-name', loc.place);
                                            placeDropdown.appendChild(option);
                                        }
                                    } catch (e) {
                                        console.error("Invalid lat_long JSON", loc.lat_long);
                                    }
                                }
                            });
                            placeDropdown.disabled = false;
                        }
                    })
                    .catch(err => {
                        console.error("Error fetching places:", err);
                    });
            }
        });

        // Place selection handling
        document.getElementById('place').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const placeName = selectedOption.getAttribute('data-place-name') || '';
            document.getElementById('place_name').value = placeName;

            const selected = this.value;
            if (selected) {
                try {
                    const latLong = JSON.parse(selected);
                    if (Array.isArray(latLong)) {
                        drawPolygonBoundary(latLong);
                        // Reset drawing tools when new boundary is loaded
                        clearDrawing();
                        document.getElementById('btnDraw').disabled = true;
                        document.getElementById('btnClear').disabled = true;
                        if (marker) {
                            marker.setMap(null);
                            marker = null;
                        }
                    }
                } catch (e) {
                    console.error("Invalid lat/long format", e);
                }
            }
        });
    });
</script>
@endpush
