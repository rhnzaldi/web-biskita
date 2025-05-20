<x-layout>
@section('custom-styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        html, body {
            margin: 0; padding: 0; height: 160vh;
        }
        #map-container {
            width: 80vw; height: 80vh;
            margin: 40px auto 60px auto !important;
            border: 2px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            position: relative;
        }
        #map {
            width: 100%; height: 100%;
        }
        #legend {
            position: absolute; bottom: 10px; left: 10px;
            background: white; padding: 8px 10px;
            font-size: 13px; border-radius: 5px;
            box-shadow: 0 0 5px rgba(0,0,0,0.3);
            z-index: 1000;
        }
        #search-container {
            position: absolute; top: 20px; left: 50%;
            transform: translateX(-50%);
            z-index: 1200;
            background: white; padding: 7px 10px;
            border-radius: 7px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.14);
            font-size: 14px;
        }
        #searchHalte {
            width: 135px; padding: 2px 7px;
        }
        #halte-list {
            position: absolute; bottom: 12px; right: 10px;
            z-index: 1100;
            background: #fff; padding: 10px 7px 7px 9px;
            border-radius: 7px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.14);
            max-height: 33vh;
            width: 145px;
            overflow-y: auto;
            font-size: 12px;
        }
        #halte-list ul {
            list-style: none; padding-left: 0; margin: 8px 0 0 0;
        }
        #halte-list ul li {
            margin-bottom: 7px;
        }
        #halte-list .halte-list-nama {
            cursor: pointer; color: #244be4; font-weight: 500;
        }
        #halte-list .halte-list-nama:hover {
            text-decoration: underline;
        }
        #halte-tabs {
            display: flex; justify-content: space-between;
            margin-bottom: 5px; gap: 5px;
        }
        #halte-tabs button {
            flex: 1 1 0;
            background: #f2f2f2;
            border: none;
            border-radius: 5px 5px 0 0;
            font-size: 13px;
            padding: 2px 0 1px 0;
            cursor: pointer;
            color: #333;
            font-weight: 600;
            transition: background 0.14s;
        }
        #halte-tabs button.active {
            background: #e7eefd;
            color: #244be4;
        }
        /* Wisata search & list */
        #wisata-search-container {
            width: 80vw;
            margin: 15px auto 5px auto;
            text-align: center;
        }
        #searchWisata {
            width: 250px;
            padding: 6px 10px;
            font-size: 14px;
            border-radius: 7px;
            border: 1px solid #ccc;
            box-shadow: 0 1px 4px rgba(0,0,0,0.1);
        }
        #wisata-list-container {
            width: 80vw;
            margin: 0 auto 30px auto;
            overflow-x: auto;
            white-space: nowrap;
            padding: 10px 0;
            box-sizing: border-box;
        }
        .wisata-card {
            display: inline-block;
            width: 160px;
            margin: 0 10px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 3px 6px rgba(0,0,0,0.1);
            cursor: pointer;
            vertical-align: top;
            user-select: none;
            transition: transform 0.2s;
        }
        .wisata-card:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 12px rgba(0,0,0,0.2);
        }
        .wisata-card img {
            width: 100%;
            height: 100px;
            object-fit: cover;
            border-radius: 8px 8px 0 0;
        }
        .wisata-card .wisata-name {
            padding: 8px 6px;
            font-weight: 600;
            font-size: 14px;
            color: #244be4;
            text-align: center;
        }
        #route-info {
            width: 80vw;
            margin: 0 auto 40px auto;
            padding: 12px 15px;
            background: #f7f9fc;
            border-radius: 8px;
            font-size: 14px;
            color: #444;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            min-height: 48px;
        }
        /* Tooltip enhancements */
        .custom-tooltip {
            background: rgba(36, 75, 228, 0.9) !important;
            color: #fff !important;
            font-weight: 600;
            border-radius: 4px !important;
            padding: 3px 8px !important;
            box-shadow: 0 0 5px rgba(36, 75, 228, 0.7);
            transition: all 0.3s ease;
        }
        /* Animated marker bounce */
        .leaflet-marker-icon.bounce {
            animation: bounce 3.5s ease;
        }
        @keyframes bounce {
            0% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0); }
        }
        @media (max-width: 700px) {
            #wisata-list-container, #wisata-search-container {
                width: 90vw;
            }
            #halte-list {
                width: 90vw;
                right: 3vw;
                left: 3vw;
                bottom: 7vw;
                font-size: 11.5px;
            }
            #legend {
                font-size: 11.5px;
            }
            #halte-tabs button {
                font-size: 12px;
            }
        }
    </style>
@endsection

<x-slot:title>Explore Bogor - Peta Interaktif</x-slot>

<livewire:company.show-wisatas />

<div id="nearest-halte-info" style="text-align:center; font-size:15px; margin-top:20px; color:#333;"></div>

<div id="map-container">
    <div id="search-container">
        <input type="text" id="searchHalte" placeholder="Cari Halte...">
    </div>

    <div id="legend">
        <strong>Legenda:</strong><br>
        <span style="color:orange;">&#8212;</span> Jalur K1<br>
        <span style="color:cyan;">&#8212;</span> Jalur K1 (balik)<br>
        <span style="color:green;">&#8212;</span> Jalur K2<br>
        <span style="color:blue;">&#8212;</span> Jalur K2 (balik)<br>
        <span style="color:blue;">●</span> Halte K1<br>
        <span style="color:darkorange;">●</span> Halte K2<br>
        <span style="color:red;">●</span> Wisata
    </div>

    <div id="map"></div>

    <div id="halte-list">
        <div id="halte-tabs">
            <button id="tabK1" class="active">K1</button>
            <button id="tabK2">K2</button>
        </div>
        <ul id="listHalte"></ul>
    </div>
</div>

<!-- Wisata Search -->
<div id="wisata-search-container">
    <input type="text" id="searchWisata" placeholder="Cari Tempat Wisata...">
</div>

<!-- Wisata List -->
<div id="wisata-list-container"></div>

<!-- Info Rute -->
<div id="route-info"></div>

<!-- Tombol Lokasi User -->
<div style="width:80vw; margin: 0 auto 10px auto; text-align: center;">
    <button id="btn-user-location" style="padding:8px 15px; background:#244be4; color:white; border:none; border-radius:7px; cursor:pointer;">
        Tampilkan Posisi Saya
    </button>
    <p style="font-size:13px; color:#555; margin-top:6px;">Atau klik langsung di peta untuk set posisi secara manual.</p>
</div>

<!-- Tombol Export GeoJSON -->
<div style="width:80vw; margin: 0 auto 30px auto; text-align: center;">
    <button id="btn-export-geojson" style="padding:8px 15px; background:#28a745; color:white; border:none; border-radius:7px; cursor:pointer;">
        Export Data Halte & Wisata ke GeoJSON
    </button>
</div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    const map = L.map('map').setView([-6.595038, 106.816635], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    const wisataLayer = L.layerGroup().addTo(map);
    const rekomendasiLayer = L.layerGroup().addTo(map);
    let halteGeoFeatures = [];
    let userLocationMarker = null;

    let allWisataFeatures = [];
    let wisataMarkers = [];

    // Icon custom untuk user
    const userIcon = L.icon({
        iconUrl: '/icons/user.png',
        iconSize: [30, 30],
        iconAnchor: [15, 30],
        popupAnchor: [0, -30]
    });

    // Load halte K1 dan K2
    Promise.all([
        fetch('/maps/biskita_k1.geojson').then(res => res.json()),
        fetch('/maps/biskita_k2.geojson').then(res => res.json())
    ]).then(([halteK1Data, halteK2Data]) => {
        halteGeoFeatures = [...halteK1Data.features, ...halteK2Data.features];

        fetch('/maps/wisata_bogor.geojson').then(res => res.json())
        .then(wisataData => {
            allWisataFeatures = wisataData.features;

            L.geoJSON(wisataData, {
                pointToLayer: (feature, latlng) => {
                    const marker = L.marker(latlng, {
                        icon: L.icon({
                            iconUrl: '/icons/wisata.png',
                            iconSize: [30, 30],
                            iconAnchor: [15, 30],
                            popupAnchor: [0, -30]
                        })
                    });
                    wisataMarkers.push({ marker, feature });
                    return marker;
                },
                onEachFeature: (feature, layer) => {
                    const p = feature.properties;
                    const fotoPath = p.foto ? `/images/${p.foto.replace('.png','.jpg')}` : '';
                    const popupContent = `
                        <div style="min-width: 250px;">
                            <h3>${p.nama}</h3>
                            <img src="${fotoPath}" alt="${p.nama}" style="width:100%; height:130px; object-fit:cover; border-radius:5px; margin-bottom:8px;" />
                            <b>Kategori:</b> ${p.kategori}<br>
                            <b>Jam Buka:</b> ${p.jam}<br>
                            <b>Alamat:</b> ${p.alamat}<br>
                            <b><a href="${p.google_maps}" target="_blank" rel="noopener">Lihat di Google Maps</a></b><br><br>
                            <p style="font-size:13px; color:#555;">${p.deskripsi}</p>
                        </div>
                    `;
                    layer.bindPopup(popupContent);

                    layer.on('mouseover', () => {
                        rekomendasiLayer.clearLayers();

                        let nearestHalte = null;
                        let minDist = Infinity;

                        halteGeoFeatures.forEach(h => {
                            const coords = h.geometry.coordinates;
                            const hLat = coords[1];
                            const hLng = coords[0];

                            const dLat = (feature.geometry.coordinates[1] - hLat) * Math.PI / 180;
                            const dLng = (feature.geometry.coordinates[0] - hLng) * Math.PI / 180;
                            const a = Math.sin(dLat / 2) ** 2 +
                                Math.cos(feature.geometry.coordinates[1] * Math.PI / 180) * Math.cos(hLat * Math.PI / 180) *
                                Math.sin(dLng / 2) ** 2;
                            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                            const distance = 6371 * c;

                            if (distance < minDist) {
                                minDist = distance;
                                nearestHalte = {
                                    ...h.properties,
                                    latitude: hLat,
                                    longitude: hLng
                                };
                            }
                        });

                        if (nearestHalte) {
                            const line = L.polyline([
                                [feature.geometry.coordinates[1], feature.geometry.coordinates[0]],
                                [nearestHalte.latitude, nearestHalte.longitude]
                            ], {
                                color: 'red',
                                weight: 2,
                                dashArray: '5, 5',
                                opacity: 0.8
                            }).addTo(rekomendasiLayer);

                            const halteMarker = L.marker([nearestHalte.latitude, nearestHalte.longitude], {
                                icon: L.divIcon({
                                    className: 'custom-div-icon',
                                    html: `<div style="background:#fff;border:2px solid red;padding:4px 8px;border-radius:6px;box-shadow:0 0 6px rgba(0,0,0,0.2);font-size:12px;">
                                        ${nearestHalte.nama_halte || nearestHalte.nama || 'Halte'}
                                    </div>`,
                                    iconSize: [100, 30],
                                    iconAnchor: [50, 30]
                                })
                            }).addTo(rekomendasiLayer);

                            line.bindPopup(
                                `Jalur ke halte terdekat: <strong>${nearestHalte.nama_halte || nearestHalte.nama || 'Halte'}</strong><br>Jarak sekitar ${minDist.toFixed(2)} km`
                            );
                        }
                    });

                    layer.on('mouseout', () => {
                        rekomendasiLayer.clearLayers();
                    });
                }
            }).addTo(wisataLayer);

            renderWisataList(allWisataFeatures);
        });
    });

    // Fungsi render list wisata
    function renderWisataList(features) {
        const container = document.getElementById('wisata-list-container');
        container.innerHTML = '';

        features.forEach(feature => {
            const p = feature.properties;
            const fotoPath = p.foto ? `/images/${p.foto.replace('.png','.jpg')}` : '';
            const div = document.createElement('div');
            div.className = 'wisata-card';
            div.innerHTML = `
                <img src="${fotoPath}" alt="${p.nama}" />
                <div class="wisata-name">${p.nama}</div>
            `;

            div.onclick = () => {
                const latlng = [feature.geometry.coordinates[1], feature.geometry.coordinates[0]];
                map.setView(latlng, 16, { animate: true });

                const markerObj = wisataMarkers.find(m => m.feature === feature);
                if (markerObj) {
                    markerObj.marker.openPopup();
                }

                rekomendasiLayer.clearLayers();
                document.getElementById('route-info').innerHTML = '';

                findNearestHalteAndRoute(feature);
            };

            container.appendChild(div);
        });
    }

    // Filter list wisata
    document.getElementById('searchWisata').addEventListener('input', function() {
        const val = this.value.trim().toLowerCase();
        const filtered = allWisataFeatures.filter(f => (f.properties.nama.toLowerCase().includes(val)));
        renderWisataList(filtered);
    });

    // Cari halte terdekat dan gambarkan rute ke wisata
    function findNearestHalteAndRoute(feature) {
        let nearestHalte = null;
        let minDist = Infinity;

        halteGeoFeatures.forEach(h => {
            const coords = h.geometry.coordinates;
            const hLat = coords[1];
            const hLng = coords[0];

            const dLat = (feature.geometry.coordinates[1] - hLat) * Math.PI / 180;
            const dLng = (feature.geometry.coordinates[0] - hLng) * Math.PI / 180;
            const a = Math.sin(dLat / 2) ** 2 +
                Math.cos(feature.geometry.coordinates[1] * Math.PI / 180) * Math.cos(hLat * Math.PI / 180) *
                Math.sin(dLng / 2) ** 2;
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            const distance = 6371 * c; // km

            if (distance < minDist) {
                minDist = distance;
                nearestHalte = {
                    ...h.properties,
                    latitude: hLat,
                    longitude: hLng
                };
            }
        });

        if (nearestHalte) {
            rekomendasiLayer.clearLayers();

            L.polyline([
                [feature.geometry.coordinates[1], feature.geometry.coordinates[0]],
                [nearestHalte.latitude, nearestHalte.longitude]
            ], {
                color: 'red',
                weight: 3,
                dashArray: '6, 6',
                opacity: 0.9
            }).addTo(rekomendasiLayer);

            L.marker([nearestHalte.latitude, nearestHalte.longitude], {
                icon: L.divIcon({
                    className: 'custom-div-icon',
                    html: `<div style="background:#fff;border:2px solid red;padding:4px 8px;border-radius:6px;box-shadow:0 0 6px rgba(0,0,0,0.2);font-size:12px;">
                        ${nearestHalte.nama_halte || nearestHalte.nama || 'Halte'}
                    </div>`,
                    iconSize: [100, 30],
                    iconAnchor: [50, 30]
                })
            }).addTo(rekomendasiLayer);

            const walkingSpeed = 5; // km/h
            const timeMinutes = (minDist / walkingSpeed) * 60;

            document.getElementById('route-info').innerHTML = `
                <strong>Rute ke Wisata:</strong> Dari halte terdekat <strong>${nearestHalte.nama_halte || nearestHalte.nama}</strong> ke <strong>${feature.properties.nama}</strong><br>
                Jarak: <strong>${minDist.toFixed(2)} km</strong>, Estimasi waktu jalan kaki: <strong>${Math.round(timeMinutes)} menit</strong>
            `;
        }
    }

    // ---- Jalur, Halte, dan List Halte ----
    const jalurK1Layer = L.layerGroup();
    const jalurK1BalikLayer = L.layerGroup();
    const jalurK2Layer = L.layerGroup();
    const jalurK2BalikLayer = L.layerGroup();
    const halteK1Layer = L.layerGroup();
    const halteK2Layer = L.layerGroup();

    let halteK1Markers = [];
    let halteK2Markers = [];

    Promise.all([
        fetch('/maps/jalur_k1.geojson').then(res => res.json()),
        fetch('/maps/jalur_k1(balik).geojson').then(res => res.json()),
        fetch('/maps/jalur_k2.geojson').then(res => res.json()),
        fetch('/maps/jalur_k2(balik).geojson').then(res => res.json()),
        fetch('/maps/biskita_k1.geojson').then(res => res.json()),
        fetch('/maps/biskita_k2.geojson').then(res => res.json())
    ]).then(([jalurK1Data, jalurK1BalikData, jalurK2Data, jalurK2BalikData, halteK1Data, halteK2Data]) => {

        // Jalur K1
        const jalurK1Geo = L.geoJSON(jalurK1Data, {
            style: { color: "black", weight: 1.2, opacity: 1, dashArray: "6,8" }
        }).addTo(jalurK1Layer);
        L.geoJSON(jalurK1Data, {
            style: { color: "orange", weight: 4, opacity: 1, dashArray: "6,8" }
        }).addTo(jalurK1Layer);
        jalurK1Layer.addTo(map);

        // Jalur K1 (balik)
        const jalurK1BalikGeo = L.geoJSON(jalurK1BalikData, {
            style: { color: "black", weight: 1.2, opacity: 1, dashArray: "6,8" }
        }).addTo(jalurK1BalikLayer);
        L.geoJSON(jalurK1BalikData, {
            style: { color: "cyan", weight: 4, opacity: 1, dashArray: "6,8" }
        }).addTo(jalurK1BalikLayer);
        jalurK1BalikLayer.addTo(map);

        // Jalur K2
        const jalurK2Geo = L.geoJSON(jalurK2Data, {
            style: { color: "black", weight: 1.2, opacity: 1, dashArray: "6,8" }
        }).addTo(jalurK2Layer);
        L.geoJSON(jalurK2Data, {
            style: { color: "green", weight: 4, opacity: 1, dashArray: "6,8" }
        }).addTo(jalurK2Layer);
        jalurK2Layer.addTo(map);

        // Jalur K2 (balik)
        const jalurK2BalikGeo = L.geoJSON(jalurK2BalikData, {
            style: { color: "black", weight: 1.2, opacity: 1, dashArray: "6,8" }
        }).addTo(jalurK2BalikLayer);
        L.geoJSON(jalurK2BalikData, {
            style: { color: "blue", weight: 4, opacity: 1, dashArray: "6,8" }
        }).addTo(jalurK2BalikLayer);
        jalurK2BalikLayer.addTo(map);

        // Halte K1
        const halteK1Geo = L.geoJSON(halteK1Data, {
            pointToLayer: (feature, latlng) => {
                const halteIcon = L.icon({
                    iconUrl: '/icons/bus_k1.png',
                    iconSize: [28, 32],
                    iconAnchor: [14, 32],
                    popupAnchor: [0, -30]
                });
                return L.marker(latlng, { icon: halteIcon });
            },
            onEachFeature: (feature, layer) => {
                const props = feature.properties;
                halteK1Markers.push(layer);
                const popup = `
                    <div style="min-width:120px;">
                        <strong>${props.nama_halte || props.nama || 'Halte K1'}</strong><br/>
                        Koridor: <span style="font-weight:bold;">${props.rute || 'K1'}</span>
                    </div>
                `;
                layer.bindPopup(popup);
                layer.bindTooltip(props.nama_halte || props.nama || 'Halte K1', {
                    direction: 'top', offset: [0, -8], permanent: false
                });
            }
        }).addTo(halteK1Layer);
        halteK1Layer.addTo(map);

        // Halte K2
        const halteK2Geo = L.geoJSON(halteK2Data, {
            pointToLayer: (feature, latlng) => {
                const halteIcon = L.icon({
                    iconUrl: '/icons/bus_k2.png',
                    iconSize: [28, 32],
                    iconAnchor: [14, 32],
                    popupAnchor: [0, -30]
                });
                return L.marker(latlng, { icon: halteIcon });
            },
            onEachFeature: (feature, layer) => {
                const props = feature.properties;
                halteK2Markers.push(layer);
                const popup = `
                    <div style="min-width:120px;">
                        <strong>${props.nama_halte || props.nama || 'Halte K2'}</strong><br/>
                        Koridor: <span style="font-weight:bold;">${props.rute || 'K2'}</span>
                    </div>
                `;
                layer.bindPopup(popup);
                layer.bindTooltip(props.nama_halte || props.nama || 'Halte K2', {
                    direction: 'top', offset: [0, -8], permanent: false
                });
            }
        }).addTo(halteK2Layer);
        halteK2Layer.addTo(map);

        // Fit semua bounds
        const allBounds = L.featureGroup([
            jalurK1Geo, jalurK1BalikGeo, jalurK2Geo, jalurK2BalikGeo,
            halteK1Geo, halteK2Geo
        ]).getBounds();
        map.fitBounds(allBounds);

        // List Halte K1/K2 di panel kanan bawah
let halteList = document.getElementById("listHalte");
let tabK1 = document.getElementById("tabK1");
let tabK2 = document.getElementById("tabK2");

function showHalteListKoridor(koridor) {
    halteList.innerHTML = '';
    const markers = koridor === "K1" ? halteK1Markers : halteK2Markers;
    markers.forEach(marker => {
        const nama = marker.feature.properties.nama_halte || marker.feature.properties.nama || ("Halte " + koridor);
        const circleColor = koridor === 'K1' ? 'blue' : 'darkorange';       // Warna background lingkaran sesuai legenda
        const borderColor = koridor === 'K1' ? '#244be4' : '#e09d00';       // Warna border lingkaran sesuai legenda

        const li = document.createElement('li');
        li.innerHTML = `
            <span style="
                display:inline-block;
                width:8px;
                height:8px;
                border-radius:50%;
                background:${circleColor};
                margin-right:7px;
                vertical-align:middle;
                border:2px solid ${borderColor};
            "></span>
            <span class="halte-list-nama">${nama}</span>
        `;
        li.querySelector('.halte-list-nama').onclick = () => {
            map.setView(marker.getLatLng(), 16, { animate: true });
            marker.openPopup();
        };
        halteList.appendChild(li);
    });
}

showHalteListKoridor("K1");
tabK1.classList.add("active");
tabK2.classList.remove("active");

tabK1.onclick = () => {
    tabK1.classList.add("active");
    tabK2.classList.remove("active");
    showHalteListKoridor("K1");
};
tabK2.onclick = () => {
    tabK2.classList.add("active");
    tabK1.classList.remove("active");
    showHalteListKoridor("K2");
};

        // Search filter halte
        document.getElementById("searchHalte").addEventListener("input", function() {
            let searchVal = this.value.trim().toLowerCase();
            [...halteK1Markers, ...halteK2Markers].forEach(layer => {
                let nama = (layer.feature.properties.nama_halte || layer.feature.properties.nama || '').toLowerCase();
                if (nama.includes(searchVal) || searchVal === "") {
                    if (!halteK1Layer.hasLayer(layer) && halteK1Markers.includes(layer))
                        halteK1Layer.addLayer(layer);
                    if (!halteK2Layer.hasLayer(layer) && halteK2Markers.includes(layer))
                        halteK2Layer.addLayer(layer);
                } else {
                    if (halteK1Markers.includes(layer)) halteK1Layer.removeLayer(layer);
                    if (halteK2Markers.includes(layer)) halteK2Layer.removeLayer(layer);
                }
            });
        });
    });

    // Tombol & Fungsi Geolocation Posisi User Otomatis
    const btnUserLocation = document.getElementById('btn-user-location');
    btnUserLocation.addEventListener('click', () => {
        if (!navigator.geolocation) {
            alert("Geolocation tidak didukung browser Anda.");
            return;
        }
        navigator.geolocation.getCurrentPosition(position => {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            setUserLocationMarker(lat, lng, "Posisi Anda (otomatis)");
        }, () => {
            alert("Gagal mendapatkan lokasi.");
        });
    });

    // Fungsi set/move marker user dan cari halte terdekat
    function setUserLocationMarker(lat, lng, label = "Posisi Anda") {
        if (userLocationMarker) {
            userLocationMarker.setLatLng([lat, lng]);
            userLocationMarker.setPopupContent(label);
        } else {
            userLocationMarker = L.marker([lat, lng], { icon: userIcon })
                .addTo(map)
                .bindPopup(label)
                .openPopup();
        }
        map.setView([lat, lng], 16, { animate: true });

        // Cari halte terdekat dari posisi user
        let nearestHalte = null;
        let minDist = Infinity;

        halteGeoFeatures.forEach(h => {
            const coords = h.geometry.coordinates;
            const hLat = coords[1];
            const hLng = coords[0];

            const dLat = (lat - hLat) * Math.PI / 180;
            const dLng = (lng - hLng) * Math.PI / 180;
            const a = Math.sin(dLat / 2) ** 2 +
                Math.cos(lat * Math.PI / 180) * Math.cos(hLat * Math.PI / 180) *
                Math.sin(dLng / 2) ** 2;
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            const distance = 6371 * c;

            if (distance < minDist) {
                minDist = distance;
                nearestHalte = {
                    ...h.properties,
                    latitude: hLat,
                    longitude: hLng
                };
            }
        });

        if (nearestHalte) {
            rekomendasiLayer.clearLayers();

            // Gambar garis ke halte terdekat
            L.polyline([
                [lat, lng],
                [nearestHalte.latitude, nearestHalte.longitude]
            ], {
                color: 'red',
                weight: 3,
                dashArray: '6, 6',
                opacity: 0.9
            }).addTo(rekomendasiLayer);

            // Marker halte terdekat
            L.marker([nearestHalte.latitude, nearestHalte.longitude], {
                icon: L.divIcon({
                    className: 'custom-div-icon',
                    html: `<div style="background:#fff;border:2px solid red;padding:4px 8px;border-radius:6px;box-shadow:0 0 6px rgba(0,0,0,0.2);font-size:12px;">
                        ${nearestHalte.nama_halte || nearestHalte.nama || 'Halte'}
                    </div>`,
                    iconSize: [100, 30],
                    iconAnchor: [50, 30]
                })
            }).addTo(rekomendasiLayer);

            // Update popup user dengan info halte terdekat
            userLocationMarker.bindPopup(`${label}<br>Halte terdekat: <strong>${nearestHalte.nama_halte || nearestHalte.nama}</strong><br>Jarak: ${minDist.toFixed(2)} km`).openPopup();

            // Update info panel
            document.getElementById('nearest-halte-info').innerHTML = `
                Halte terdekat untuk lokasi Anda adalah <strong>${nearestHalte.nama_halte || nearestHalte.nama}</strong> dengan jarak sekitar <strong>${minDist.toFixed(2)} km</strong>.
            `;
        }
    }

    // Event klik peta untuk set posisi user manual
    // Popup halte terdekat di-nonaktifkan agar tidak mengganggu user experience
    map.on('click', function(e) {
        setUserLocationMarker(e.latlng.lat, e.latlng.lng, "Posisi Anda ");
        // Tidak ada kode popup halte terdekat di sini
    });

    // Layer control
    const overlayMaps = {
        "Jalur K1": jalurK1Layer,
        "Jalur K1 (balik)": jalurK1BalikLayer,
        "Jalur K2": jalurK2Layer,
        "Jalur K2 (balik)": jalurK2BalikLayer,
        "Halte K1": halteK1Layer,
        "Halte K2": halteK2Layer,
        "Wisata": wisataLayer
    };
    L.control.layers(null, overlayMaps).addTo(map);

    // Event highlight wisata dari Livewire atau UI lain
    window.addEventListener('show-on-map', function(event) {
        const data = event.detail[0];

        const lat = parseFloat(data.lat);
        const lng = parseFloat(data.lng);
        const nama = data.nama;

        if (isNaN(lat) || isNaN(lng)) {
            console.error("Invalid coordinates after parse:", lat, lng);
            return;
        }

        map.setView([lat, lng], 16, { animate: true });

        L.popup()
            .setLatLng([lat, lng])
            .setContent(`<strong>${nama}</strong>`)
            .openOn(map);

        rekomendasiLayer.clearLayers();

        let nearestHalte = null;
        let minDist = Infinity;

        halteGeoFeatures.forEach(h => {
            const coords = h.geometry.coordinates;
            const hLat = coords[1];
            const hLng = coords[0];

            const dLat = (lat - hLat) * Math.PI / 180;
            const dLng = (lng - hLng) * Math.PI / 180;
            const a = Math.sin(dLat / 2) ** 2 +
                Math.cos(lat * Math.PI / 180) * Math.cos(hLat * Math.PI / 180) *
                Math.sin(dLng / 2) ** 2;
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            const distance = 6371 * c;

            if (distance < minDist) {
                minDist = distance;
                nearestHalte = {
                    ...h.properties,
                    latitude: hLat,
                    longitude: hLng
                };
            }
        });

        if (nearestHalte) {
            L.polyline([
                [lat, lng],
                [nearestHalte.latitude, nearestHalte.longitude]
            ], {
                color: 'red',
                weight: 3,
                dashArray: '6, 6',
                opacity: 0.9
            }).addTo(rekomendasiLayer);

            L.marker([nearestHalte.latitude, nearestHalte.longitude], {
                icon: L.divIcon({
                    className: 'custom-div-icon',
                    html: `<div style="background:#fff;border:2px solid red;padding:4px 8px;border-radius:6px;box-shadow:0 0 6px rgba(0,0,0,0.2);font-size:12px;">
                        ${nearestHalte.nama_halte || nearestHalte.nama || 'Halte'}
                    </div>`,
                    iconSize: [100, 30],
                    iconAnchor: [50, 30]
                })
            }).addTo(rekomendasiLayer);

            document.getElementById('nearest-halte-info').innerHTML = `
                Halte terdekat untuk menuju wisata <strong>${nama}</strong> ialah 
                <strong style="color:#244be4;">${nearestHalte.nama_halte || nearestHalte.nama}</strong>.
            `;
        }

        document.getElementById('map-container')?.scrollIntoView({ behavior: 'smooth' });
    });

    // Fungsi Export GeoJSON dan download file
    function downloadGeoJSON(data, filename) {
        const blob = new Blob([JSON.stringify(data, null, 2)], { type: "application/json" });
        const url = URL.createObjectURL(blob);

        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);

        URL.revokeObjectURL(url);
    }

    document.getElementById('btn-export-geojson').addEventListener('click', () => {
        // Gabungkan data halte dan wisata jadi satu GeoJSON FeatureCollection
        const combinedFeatures = [
            ...halteGeoFeatures,
            ...allWisataFeatures
        ];

        const geojsonExport = {
            type: "FeatureCollection",
            features: combinedFeatures
        };

        downloadGeoJSON(geojsonExport, "data-halte-wisata.geojson");
    });

    /* 
     * Catatan: Animasi bounce untuk marker user sudah ada definisinya di CSS (.leaflet-marker-icon.bounce),
     * tapi tidak ada kode JS yang menambahkan kelas "bounce" ke userLocationMarker,
     * jadi animasi ini belum aktif saat ini.
     * Anda bisa menambahkan class "bounce" pada marker untuk mengaktifkannya jika ingin.
     */
</script>

</x-layout>
