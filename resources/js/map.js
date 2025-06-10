let map;
let directionsService;
let directionsRenderer;
let originAutocomplete;
let destinationAutocomplete;
let currentLocationMarker;

function initializeMap() {
    const center = { lat: 13.7563, lng: 100.5018 };
    
    map = new google.maps.Map(document.getElementById('map'), {
        center: center,
        zoom: 12,
        mapTypeId: 'roadmap',
        language: 'th',
        region: 'TH'
    });

    // Initialize directions service and renderer
    directionsService = new google.maps.DirectionsService();
    directionsRenderer = new google.maps.DirectionsRenderer({
        map: map,
        panel: document.getElementById('directionsPanel')
    });

    // Set up autocomplete
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

    originAutocomplete.bindTo('bounds', map);
    destinationAutocomplete.bindTo('bounds', map);

    // Add button event listeners
    document.querySelector('button[onclick="getCurrentLocation()"]')
        .addEventListener('click', getCurrentLocation);
    document.querySelector('button[onclick="calculateRoute()"]')
        .addEventListener('click', calculateRoute);
}

window.getCurrentLocation = function() {
    if (!navigator.geolocation) {
        alert('เบราว์เซอร์ของคุณไม่รองรับการระบุตำแหน่ง');
        return;
    }

    navigator.geolocation.getCurrentPosition(
        (position) => {
            const pos = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };

            if (currentLocationMarker) {
                currentLocationMarker.setMap(null);
            }

            currentLocationMarker = new google.maps.Marker({
                position: pos,
                map: map,
                title: 'ตำแหน่งปัจจุบัน'
            });

            map.setCenter(pos);
            map.setZoom(15);

            const geocoder = new google.maps.Geocoder();
            geocoder.geocode({ location: pos }, (results, status) => {
                if (status === 'OK' && results[0]) {
                    document.getElementById('origin').value = results[0].formatted_address;
                } else {
                    alert('ไม่สามารถระบุที่อยู่ของตำแหน่งปัจจุบันได้');
                }
            });
        },
        (error) => {
            const errorMessages = {
                PERMISSION_DENIED: 'กรุณาอนุญาตให้เข้าถึงตำแหน่งของคุณ',
                POSITION_UNAVAILABLE: 'ไม่สามารถระบุตำแหน่งของคุณได้',
                TIMEOUT: 'หมดเวลาในการค้นหาตำแหน่ง',
                DEFAULT: 'เกิดข้อผิดพลาดในการค้นหาตำแหน่ง'
            };
            alert(errorMessages[error.code] || errorMessages.DEFAULT);
        }
    );
}

window.calculateRoute = function() {
    const origin = document.getElementById('origin').value;
    const destination = document.getElementById('destination').value;

    if (!origin || !destination) {
        alert('กรุณาระบุจุดเริ่มต้นและจุดหมายปลายทาง');
        return;
    }

    const request = {
        origin: origin,
        destination: destination,
        travelMode: google.maps.TravelMode.DRIVING,
        language: 'th',
        region: 'TH'
    };

    directionsService.route(request, (response, status) => {
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

// Wait for Google Maps API to load
function initMap() {
    if (typeof google === 'undefined' || typeof google.maps === 'undefined') {
        setTimeout(initMap, 100);
        return;
    }
    initializeMap();
}

document.addEventListener('DOMContentLoaded', initMap);