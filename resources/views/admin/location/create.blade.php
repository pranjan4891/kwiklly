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
</style>

<div class="wraper container-fluid">
    <div class="page-title">
        <h3 class="title">Add Pincode Locations with Geofence</h3>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Pincode Location Form</h3>
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route('admin.location.store') }}" id="pincodeForm">
                        @csrf
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">State</label>
                            <div class="col-lg-8">
                                <select class="form-control" name="state_id" id="state" required>
                                    <option value="">Select State</option>
                                    @foreach($states as $state)
                                        <option value="{{ $state->id }}">{{ $state->state_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('state_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">City</label>
                            <div class="col-lg-8">
                                <select class="form-control" name="city_id" id="city" required>
                                    <option value="">Select City</option>
                                </select>
                            </div>
                            @error('city_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Pincode</label>
                            <div class="col-lg-8">
                                <input type="text" name="pincode" class="form-control" id="pincode" maxlength="6" minlength="6" placeholder="Enter Pincode" required>
                            </div>
                            @error('pincode')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Area Name</label>
                            <div class="col-lg-8">
                                <input type="text" name="place" id="address" class="form-control" placeholder="Enter Place" oninput="fetchSuggestions(this.value)" required>
                            </div>
                            @error('place')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Custom Area (lat / long)</label>
                            <div class="col-lg-8">
                                <textarea class="form-control" name="lat_long" id="lat_long" rows="5" readonly></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-lg-offset-2 col-lg-10">
                                <button type="submit" class="btn btn-success">Save</button>
                                <button type="reset" class="btn btn-default">Reset</button>
                            </div>
                        </div>
                    </form>

                    <div id="map_canvas"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Google Maps JS with Drawing Library -->
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=drawing"></script>
<script>
    let map, marker, drawingManager, polygon = null;

    function initializeMap() {
        map = new google.maps.Map(document.getElementById("map_canvas"), {
            center: { lat: 22.9734, lng: 78.6569 },
            zoom: 5,
            mapTypeId: 'roadmap'
        });

        drawingManager = new google.maps.drawing.DrawingManager({
            drawingMode: google.maps.drawing.OverlayType.POLYGON,
            drawingControl: true,
            drawingControlOptions: {
                position: google.maps.ControlPosition.TOP_CENTER,
                drawingModes: ['polygon']
            },
            polygonOptions: {
                strokeColor: '#FF0000',
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: '#FF0000',
                fillOpacity: 0.35,
                editable: true,
                draggable: false
            }
        });

        drawingManager.setMap(map);

        google.maps.event.addListener(drawingManager, 'overlaycomplete', function(event) {
            if (polygon) polygon.setMap(null); // Remove previous polygon

            polygon = event.overlay;

            const coordinates = polygon.getPath().getArray().map(coord => ({
                lat: coord.lat(),
                lng: coord.lng()
            }));

            document.getElementById("lat_long").value = JSON.stringify(coordinates);
        });
    }

    function geocodePincode(pincode) {
        const geocoder = new google.maps.Geocoder();
        geocoder.geocode({ address: pincode }, function(results, status) {
            if (status === 'OK' && results.length) {
                const location = results[0].geometry.location;

                if (marker) marker.setMap(null);

                marker = new google.maps.Marker({
                    map: map,
                    position: location
                });

                map.setCenter(location);
                map.setZoom(14);
            } else {
                alert('Location not found for this pincode.');
            }
        });
    }

    document.addEventListener("DOMContentLoaded", () => {
        initializeMap();

        document.getElementById("pincode").addEventListener("change", () => {
            const pincode = document.getElementById("pincode").value.trim();
            if (pincode) {
                geocodePincode(pincode);
            }
        });

        document.getElementById("state").addEventListener("change", function () {
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
    });
</script>
@endsection
