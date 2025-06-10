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
                    <!-- Map container -->
                    <div id="map" style="height: 500px; width: 100%; border-radius: 0.5rem;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Google Maps JavaScript -->
    <script>
        function initMap() {
            // Default coordinates (Bangkok, Thailand)
            const center = { lat: 13.7563, lng: 100.5018 };
            
            // Create the map
            const map = new google.maps.Map(document.getElementById('map'), {
                center: center,
                zoom: 12,
                mapTypeId: 'roadmap',
            });

            // Add a marker at the center
            const marker = new google.maps.Marker({
                position: center,
                map: map,
                title: 'Bangkok'
            });
        }
    </script>
    
    <!-- Load Google Maps JavaScript API -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAKU-XAaErCEqfAltaErHdd_u-uQKgz900&callback=initMap" async defer></script>
</x-app-layout>
