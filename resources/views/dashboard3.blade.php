<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Google Maps Dashboard') }}
        </h2>
    </x-slot>    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Route inputs -->
                    <div class="mb-4 flex space-x-4">
                        <button onclick="getCurrentLocation()" 
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md"
                                title="ใช้ตำแหน่งปัจจุบัน">
                            ตำแหน่งปัจจุบัน
                        </button>
                        <div class="flex-1">
                            <input type="text" id="origin" placeholder="ระบุจุดเริ่มต้น" 
                                   class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                        </div>
                        <div class="flex-1">
                            <input type="text" id="destination" placeholder="ระบุจุดหมายปลายทาง"
                                   class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                        </div>
                        <button onclick="calculateRoute()" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            ค้นหาเส้นทาง
                        </button>
                    </div>
                    <!-- Map container -->
                    <div id="map" style="height: 500px; width: 100%; border-radius: 0.5rem;"></div>
                    <!-- Directions panel -->
                    <div id="directionsPanel" class="mt-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-md text-gray-800 dark:text-gray-200">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Google Maps JavaScript -->
    <script>
        let map;
        let directionsService;
        let directionsRenderer;
        let originAutocomplete;
        let destinationAutocomplete;
        let currentLocationMarker;

        function initMap() {
            // Default coordinates (Bangkok, Thailand)
            const center = { lat: 13.7563, lng: 100.5018 };
            
            // Initialize the map
            map = new google.maps.Map(document.getElementById('map'), {
                center: center,
                zoom: 12,
                mapTypeId: 'roadmap',
                language: 'th', // Set language to Thai
                region: 'TH'    // Set region to Thailand
            });

            // Initialize the directions service and renderer
            directionsService = new google.maps.DirectionsService();
            directionsRenderer = new google.maps.DirectionsRenderer({
                map: map,
                panel: document.getElementById('directionsPanel')
            });

            // Set up autocomplete for origin and destination
            const options = {
                componentRestrictions: { country: 'th' },
                fields: ['formatted_address', 'geometry', 'name'],
                strictBounds: false,
                types: ['establishment', 'geocode']
            };

            originAutocomplete = new google.maps.places.Autocomplete(
                document.getElementById('origin'),
                options
            );

            destinationAutocomplete = new google.maps.places.Autocomplete(
                document.getElementById('destination'),
                options
            );

            // Bind autocomplete to map bounds
            originAutocomplete.bindTo('bounds', map);
            destinationAutocomplete.bindTo('bounds', map);
        }

        function getCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const pos = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        };

                        // Remove existing marker if any
                        if (currentLocationMarker) {
                            currentLocationMarker.setMap(null);
                        }

                        // Create new marker
                        currentLocationMarker = new google.maps.Marker({
                            position: pos,
                            map: map,
                            title: 'ตำแหน่งปัจจุบัน'
                        });

                        // Center map on current location
                        map.setCenter(pos);
                        map.setZoom(15);

                        // Get address from coordinates using Geocoding service
                        const geocoder = new google.maps.Geocoder();
                        geocoder.geocode({ location: pos }, (results, status) => {
                            if (status === 'OK' && results[0]) {
                                document.getElementById('origin').value = results[0].formatted_address;
                            } else {
                                alert('ไม่สามารถระบุที่อยู่ของตำแหน่งปัจจุบันได้');
                            }
                        });
                    },
                    function(error) {
                        switch(error.code) {
                            case error.PERMISSION_DENIED:
                                alert('กรุณาอนุญาตให้เข้าถึงตำแหน่งของคุณ');
                                break;
                            case error.POSITION_UNAVAILABLE:
                                alert('ไม่สามารถระบุตำแหน่งของคุณได้');
                                break;
                            case error.TIMEOUT:
                                alert('หมดเวลาในการค้นหาตำแหน่ง');
                                break;
                            default:
                                alert('เกิดข้อผิดพลาดในการค้นหาตำแหน่ง');
                                break;
                        }
                    }
                );
            } else {
                alert('เบราว์เซอร์ของคุณไม่รองรับการระบุตำแหน่ง');
            }
        }

        function calculateRoute() {
            const origin = document.getElementById('origin').value;
            const destination = document.getElementById('destination').value;

            if (!origin || !destination) {
                alert('กรุณาระบุจุดเริ่มต้นและจุดหมายปลายทาง');
                return;
            }

            const request = {
                origin: origin,
                destination: destination,
                travelMode: 'DRIVING',
                language: 'th',  // Set directions language to Thai
                region: 'TH'     // Set region to Thailand
            };

            directionsService.route(request, function(response, status) {
                if (status === 'OK') {
                    directionsRenderer.setDirections(response);
                    if (currentLocationMarker) {
                        currentLocationMarker.setMap(null);
                    }
                } else {
                    alert('ไม่สามารถค้นหาเส้นทางได้: ' + status);
                }
            });
        }
    </script>
    
    <!-- Load Google Maps JavaScript API with places library -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAkkbCsu-4AU3JWJ3u_j3sjwzljVU_blhk&libraries=places&language=th&region=TH&callback=initMap" async defer></script>
</x-app-layout>
