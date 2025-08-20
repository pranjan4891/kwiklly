@extends('admin.includes.main')

@section('main')
<style>
    .suggestions {
        list-style-type: none;
        padding: 0;
        border: 1px solid #ccc;
        max-height: 150px;
        overflow-y: auto;
        position: absolute;
        background-color: #fff;
        z-index: 1000;
    }

    .suggestions li {
        padding: 8px;
        cursor: pointer;
    }

    .suggestions li:hover {
        background-color: #f0f0f0;
    }

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
        <h3 class="title">Edit Pincode Location with Geofence</h3>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Edit Location</h3>
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route('admin.location.update', $location->id) }}">
                        @csrf
                        @method('POST')

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">State</label>
                            <div class="col-lg-8">
                                <select class="form-control" name="state_id" id="state" required>
                                    <option value="">Select State</option>
                                    @foreach($states as $state)
                                        <option value="{{ $state->id }}" {{ $location->state_id == $state->id ? 'selected' : '' }}>
                                            {{ $state->state_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">City</label>
                            <div class="col-lg-8">
                                <select class="form-control" name="city_id" id="city" required>
                                    <option value="">Select City</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city->id }}" {{ $location->city_id == $city->id ? 'selected' : '' }}>
                                            {{ $city->city_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Pincode</label>
                            <div class="col-lg-8">
                                <input type="text" name="pincode" class="form-control" id="pincode" value="{{ $location->pincode }}" required maxlength="6" minlength="6">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Place</label>
                            <div class="col-lg-8">
                                <input type="text" name="place" id="address" class="form-control" value="{{ $location->place }}" oninput="fetchSuggestions(this.value)" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Custom Area (lat / long)</label>
                            <div class="col-lg-8">
                                <textarea class="form-control" name="lat_long" id="lat_long" rows="5" readonly>{{ $location->lat_long }}</textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-lg-offset-2 col-lg-10">
                                <button type="submit" class="btn btn-success">Update</button>
                                <a href="{{ route('admin.location') }}" class="btn btn-default">Cancel</a>
                            </div>
                        </div>
                    </form>

                    <div id="map_canvas"></div>
                    <button id="deleteButton" class="btn btn-danger mt-3">Delete Selected Shape</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Google Maps & Drawing JS -->
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=drawing"></script>
<script>
    const existingPolygon = {!! $location->lat_long !!};
    let map, drawingManager, polygon = null;

    function initializeMap() {
        map = new google.maps.Map(document.getElementById("map_canvas"), {
            center: { lat: 22.9734, lng: 78.6569 },
            zoom: 5,
            mapTypeId: 'roadmap'
        });

        drawingManager = new google.maps.drawing.DrawingManager({
            drawingMode: null,
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
            if (polygon) polygon.setMap(null);
            polygon = event.overlay;
            updateLatLong(polygon);
        });

        if (existingPolygon.length) {
            const coords = existingPolygon.map(p => ({ lat: parseFloat(p.lat), lng: parseFloat(p.lng) }));
            polygon = new google.maps.Polygon({
                paths: coords,
                strokeColor: '#FF0000',
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: '#FF0000',
                fillOpacity: 0.35,
                editable: true,
                map: map
            });

            map.fitBounds(new google.maps.LatLngBounds(...coords.map(coord => new google.maps.LatLng(coord.lat, coord.lng))));
            updateLatLong(polygon);
        }
    }

    function updateLatLong(polygon) {
        const coordinates = polygon.getPath().getArray().map(coord => ({
            lat: coord.lat(),
            lng: coord.lng()
        }));
        document.getElementById("lat_long").value = JSON.stringify(coordinates);
    }

    document.getElementById("deleteButton").addEventListener("click", () => {
        if (polygon) {
            polygon.setMap(null);
            polygon = null;
            document.getElementById("lat_long").value = '';
        }
    });

    // Auto-suggest using Azure Maps
    const azureKey = "{{ env('AZURE_MAPS_KEY') }}";

    function fetchSuggestions(input) {
        if (input.length < 3) return document.getElementById("suggestion-list").innerHTML = "";

        fetch(`https://atlas.microsoft.com/search/address/json?api-version=1.0&query=${encodeURIComponent(input)}&subscription-key=${azureKey}`)
            .then(res => res.json())
            .then(data => {
                const list = document.getElementById("suggestion-list");
                list.innerHTML = "";
                data.results.forEach(item => {
                    const li = document.createElement("li");
                    li.textContent = item.address.freeformAddress;
                    li.onclick = () => selectSuggestion(item.address.freeformAddress, item.position.lat, item.position.lon);
                    list.appendChild(li);
                });
            });
    }

    function selectSuggestion(address, lat, lon) {
        document.getElementById("address").value = address;
        document.getElementById("latitude").value = lat;
        document.getElementById("longitude").value = lon;
        document.getElementById("suggestion-list").innerHTML = "";

        const selectedLatLng = new google.maps.LatLng(lat, lon);
        map.setCenter(selectedLatLng);
        map.setZoom(15);

        new google.maps.Marker({
            position: selectedLatLng,
            map: map,
            title: address
        });
    }

    document.addEventListener("DOMContentLoaded", () => {
        initializeMap();
    });
</script>
@endsection
