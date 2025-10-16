<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<div id="map" class="h-96"></div>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
const map = L.map('map').setView([39.8,-86.1], 9);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
@foreach($appointments as $a)
L.marker([{{ $a->contact->lat ?? $a->office->lat }}, {{ $a->contact->lng ?? $a->office->lng }}]).addTo(map)
.bindPopup(`{{ $a->contact->first_name }} {{ $a->contact->last_name }}<br>{{ $a->scheduled_for }}`);
@endforeach
</script>