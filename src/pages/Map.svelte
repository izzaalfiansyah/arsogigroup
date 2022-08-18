<script lang="ts">
	export let open = false;
	export let value = null;
	export let search = false;

	import { createEventDispatcher, onMount } from 'svelte';

	import 'leaflet/dist/leaflet.css';
	import L from 'leaflet/dist/leaflet.js';
	import { notif } from '@/lib';

	let map = null;
	let marker = null;

	const dispatch = createEventDispatcher();

	$: if (value) {
		if (map) {
			map.remove();
			map = null;
		}
		render(value, 15);
		marker = L.marker(value).addTo(map);
	}

	function setPosition(pos) {
		if (marker) {
			marker.remove();
		}

		marker = L.marker(pos).addTo(map);

		dispatch('change', {
			latitude: pos.lat,
			longitude: pos.lng,
		});
	}

	function render(latlng = [-1.2303741774326018, 113.77441406250001], zoom = 4) {
		if (!map) {
			map = L.map('map').setView(latlng, zoom);
			L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

			map.on('click', (e) => {
				setPosition(e.latlng);
			});
		}
	}

	$: if (open && map) {
		map.invalidateSize();
	}

	$: if (search) {
		navigator.permissions.query({ name: 'geolocation' });
		
		map
			.locate({ setView: true, timeout: 5000 })
			.on('locationfound', (e) => {
				setPosition(e.latlng);
				search = false;
			})
			.on('locationerror', (e) => {
				notif(e.message.replace('Geolocation error: ', ''));
				search = false;
			});
	}

	onMount(() => {
		setTimeout(render, 800);
	});
</script>

<div id="map" />

<style>
	#map {
		height: 300px;
		width: 100%;
	}
</style>
