@extends('layouts.master')

@section('title')
    {{ __('change_order') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                {{ __('change_order') }}: {{ $route->name }}
            </h3>
            <div class="d-flex justify-content-end">
                <a class="btn btn-sm btn-theme" href="{{ route('routes.index') }}">{{ __('back') }}</a>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-12 h-100">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">{{ __('change_order') }}</h4>
                        {{-- <form action="{{ route('routes.update-pickup-order', $route->id) }}" class="edit-form" id="edit-form" method="POST">
                            @csrf
                            @method('PUT') --}}
                        <div class="row">
                            <div class="col-md-6 h-100">
                                <div class="border border-theme p-4">
                                    <div id="profile-list-left" class="py-2 h-100">
                                        @if (isset($route->routePickupPoints) && $route->routePickupPoints->isNotEmpty())
                                            @foreach ($route->routePickupPoints->sortBy('order') as $routePickupPoint)
                                                <div class="card rounded mb-2 border border-secondary"
                                                    data-id="{{ $routePickupPoint->id }}">
                                                    <div class="card-body p-3 d-flex align-items-center">
                                                        <div class="order-pickup-point me-3">
                                                            {{ $routePickupPoint->order }}
                                                        </div>
                                                        <div class="media-body">
                                                            <h6 class="mb-1">
                                                                {{ $routePickupPoint->pickupPoint->name }}
                                                            </h6>
                                                            <p class="mb-0 text-muted">
                                                                {{ $routePickupPoint->pickup_time }} -
                                                                {{ $routePickupPoint->drop_time }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                    {{-- hidden input to capture order in form --}}
                                                    <input type="hidden" name="pickup_points[{{ $routePickupPoint->id }}]"
                                                        value="{{ $routePickupPoint->order }}">
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="text-center text-muted">
                                                <p>No pickup points found for this route.</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class=" col-md-6">
                                <div id="map" style="height: 400px; width: 100%;"></div>
                            </div>
                        </div>
                        {{-- <div class="row">
                            <div class=" col-md-12">
                                <div id="map" style="height: 400px; width: 100%;"></div>
                            </div>
                        </div> --}}
                        {{-- <div class="d-flex justify-content-end">
                                <input type="submit" class="btn btn-theme mt-3" value="{{ __('update') }}">
                            </div>
                        </form> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        let dragulaInstance = null;

        $(document).ready(function() {
            initializeDragula();
        });

        function initializeDragula() {
            if (dragulaInstance) {
                dragulaInstance.destroy();
            }

            dragulaInstance = dragula([document.getElementById('profile-list-left')]);

            dragulaInstance.on('drop', function(el, target, source, sibling) {
                updateOrderNumbers();
                saveOrderToDB();
            });

            dragulaInstance.on('drag', function(el) {
                el.classList.add('dragging');
            });

            dragulaInstance.on('dragend', function(el) {
                el.classList.remove('dragging');
            });
        }

        // Update order numbers + hidden inputs
        function updateOrderNumbers() {
            $('#profile-list-left .card').each(function(index) {
                const order = index + 1;
                $(this).find('.order-pickup-point').text(order);
                $(this).find('input[type="hidden"]').val(order);
            });
        }

        // Send updated order to DB via AJAX
        function saveOrderToDB() {
            let pickupPoints = {};

            $('#profile-list-left .card').each(function(index) {
                const id = $(this).data('id');
                pickupPoints[id] = index + 1;
            });

            $.ajax({
                url: "{{ route('routes.update-pickup-order', $route->id) }}",
                method: "PUT",
                data: {
                    pickup_points: pickupPoints
                },
                success: function(response) {
                    if (!response.error) {
                        showSuccessToast(response.message);
                        window.location.reload();
                    } else {
                        showErrorToast(response.message);
                    }
                },
                error: function() {
                    showErrorToast('Something went wrong!');
                }
            });
        }

        let map;
        const routes = @json($route->routePickupPoints);

        function initMap() {

            const defaultPosition = {
                lat: 20.5937,
                lng: 78.9629
            };
            const bounds = new google.maps.LatLngBounds();

            map = new google.maps.Map(document.getElementById("map"), {
                center: defaultPosition,
                zoom: 10,
            });

            routes.forEach((route, index) => {

                // Use single pickup_point (not pickup_points)
                const point = route.pickup_point;

                // Skip if missing
                if (!point) return;

                const position = {
                    lat: parseFloat(point.latitude),
                    lng: parseFloat(point.longitude)
                };

                // Extend map bounds
                bounds.extend(position);

                // Create circle
                new google.maps.Circle({
                    center: position,
                    fillColor: "#4285F4",
                    fillOpacity: 0.25,
                    strokeColor: "#4285F4",
                    strokeWeight: 2,
                    map: map
                });

                // Create numbered marker
                new google.maps.Marker({
                    position: position,
                    map: map,
                    label: {
                        text: (index + 1).toString(),
                        color: "#fff",
                        fontSize: "10px",
                        fontWeight: "bold",
                    },
                    icon: {
                        path: google.maps.SymbolPath.CIRCLE,
                        scale: 12,
                        fillColor: "#4285F4",
                        fillOpacity: 1,
                        strokeWeight: 0,
                    }
                });

            });

            // Adjust zoom to fit all points
            if (routes.length > 0) {
                map.fitBounds(bounds);

                google.maps.event.addListenerOnce(map, 'bounds_changed', function() {
                    let desiredZoom = 12;

                    if (map.getZoom() > desiredZoom) {
                        map.setZoom(desiredZoom);
                    }
                });
            }
        }
    </script>
@endsection
