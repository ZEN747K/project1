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
                        <button id="currentLocationBtn" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md"
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
                        <button id="calculateRouteBtn" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
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

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAkkbCsu-4AU3JWJ3u_j3sjwzljVU_blhk&libraries=places&language=th&region=TH"></script>
    @vite('resources/js/map.js')
</x-app-layout>

<script>
    document.getElementById('currentLocationBtn').addEventListener('click', function() {
        getCurrentLocation();
    });

    document.getElementById('calculateRouteBtn').addEventListener('click', function() {
        calculateRoute();
    });
</script>
