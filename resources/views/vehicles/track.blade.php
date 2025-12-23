<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Google Map</title>
    <style>
        #map {
            height: 800px;
            width: 100%;
        }
    </style>
</head>
<body>
<!-- Map Container -->
<div id="map"></div>

<script>
    function initMap() {
        // Location (example: Delhi)
        const location = { lat: 28.6139, lng: 77.2090 };

        // Initialize map
        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 12,
            center: location
        });

        // Marker
        new google.maps.Marker({
            position: location,
            map: map,
            title: "My Location"
        });
    }
</script>

<!-- Google Maps API -->
 <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBqOdT7uQebSHbnuZcqpWSYFtM8mryin4o&callback=initMap&libraries=places" async
        defer></script>

</body>
</html>
