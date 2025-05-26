<x-layout>
    @section('custom-styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
        <style>
            html,
            body {
                margin: 0;
                padding: 0;
                height: 160vh;
            }

            #map-container {
                width: 80vw;
                height: 80vh;
                margin: 40px auto 60px auto !important;
                border: 2px solid #ccc;
                border-radius: 10px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                position: relative;
            }

            #map {
                width: 100%;
                height: 100%;
            }

            #legend {
                position: absolute;
                bottom: 10px;
                left: 10px;
                background: white;
                padding: 8px 10px;
                font-size: 13px;
                border-radius: 5px;
                box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
                z-index: 1000;
                max-height: 160px;
                /* tambahkan batas tinggi */
                overflow-y: auto;
                /* aktifkan scroll vertikal */
            }

            #search-container {
                position: absolute;
                top: 20px;
                left: 50%;
                transform: translateX(-50%);
                z-index: 1200;
                background: white;
                padding: 7px 10px;
                border-radius: 7px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.14);
                font-size: 14px;
            }

            #searchHalte {
                width: 135px;
                padding: 2px 7px;
            }

            #halte-list {
                position: absolute;
                bottom: 12px;
                right: 10px;
                z-index: 1100;
                background: #fff;
                padding: 10px 7px 7px 9px;
                border-radius: 7px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.14);
                max-height: 33vh;
                width: 145px;
                overflow-y: auto;
                font-size: 12px;
            }

            #halte-list ul {
                list-style: none;
                padding-left: 0;
                margin: 8px 0 0 0;
            }

            #halte-list ul li {
                margin-bottom: 7px;
            }

            #halte-list .halte-list-nama {
                cursor: pointer;
                color: #244be4;
                font-weight: 500;
            }

            #halte-list .halte-list-nama:hover {
                text-decoration: underline;
            }

            #halte-tabs {
                display: flex;
                justify-content: space-between;
                margin-bottom: 5px;
                gap: 5px;
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
                box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
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
                box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
                cursor: pointer;
                vertical-align: top;
                user-select: none;
                transition: transform 0.2s;
            }

            .wisata-card:hover {
                transform: scale(1.05);
                box-shadow: 0 5px 12px rgba(0, 0, 0, 0.2);
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
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
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
                0% {
                    transform: translateY(0);
                }

                50% {
                    transform: translateY(-15px);
                }

                100% {
                    transform: translateY(0);
                }
            }

            @media (max-width: 700px) {

                #wisata-list-container,
                #wisata-search-container {
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

    <!-- Wisata Search -->
    <div id="wisata-search-container" class="mt-4">
        <input type="text" id="searchWisata" placeholder="Cari Tempat Wisata...">
    </div>

    <!-- Wisata List -->
    <div id="wisata-list-container"></div>

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
            <img src="icons/bus_k1.png" width="20" style="vertical-align:middle;"> Halte K1<br>
            <img src="icons/bus_k2.png" width="20" style="vertical-align:middle;"> Halte K2<br>
            <br>
            <strong>Wisata:</strong><br>
            <img src="icons/Galeri_Seni.png" width="20" style="vertical-align:middle;"> Galeri Seni<br>
            <img src="icons/Herbarium.png" width="20" style="vertical-align:middle;"> Herbarium<br>
            <img src="icons/Kebun_Botani.png" width="20" style="vertical-align:middle;"> Kebun Botani<br>
            <img src="icons/Museum.png" width="20" style="vertical-align:middle;"> Museum<br>
            <img src="icons/Perpustakaan.png" width="20" style="vertical-align:middle;"> Perpustakaan<br>
            <img src="icons/Taman_Kota.png" width="20" style="vertical-align:middle;"> Taman Kota<br>
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

    <div id="nearest-halte-info" style="text-align:center; font-size:15px; margin-top:20px; color:#333;"></div>


    <!-- Info Rute -->
    <!-- STEP-BY-STEP RUTE DROPDOWN -->
    <div id="stepbystep-container"
        style="width:80vw; margin:20px auto 18px auto; background:#fff; border-radius:10px; box-shadow:0 2px 10px rgba(0,0,0,0.14); padding:15px;">
        <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
            <select id="startHalteSelect"
                style="flex:1; min-width:180px; padding:6px; border-radius:7px; border:1px solid #ccc;">
                <option value="">Pilih Halte Awal...</option>
            </select>
            <select id="endWisataSelect"
                style="flex:1; min-width:180px; padding:6px; border-radius:7px; border:1px solid #ccc;">
                <option value="">Pilih Tujuan Wisata...</option>
            </select>
            <button id="btn-stepbystep"
                style="padding:8px 12px; background:#244be4; color:#fff; border:none; border-radius:7px; margin-top:4px;">Tampilkan
                Rute</button>
        </div>
        <div id="stepbystep-result" style="margin-top:15px; font-size:15px; color:#333;"></div>
    </div>

    <!-- Tombol Lokasi User -->
    <div style="width:80vw; margin: 0 auto 10px auto; text-align: center;">
        <button id="btn-user-location"
            style="padding:8px 15px; background:#244be4; color:white; border:none; border-radius:7px; cursor:pointer;">
            Tampilkan Posisi Saya
        </button>
        <p style="font-size:13px; color:#555; margin-top:6px;">Atau klik langsung di peta untuk set posisi secara
            manual.</p>
    </div>

    <!-- Tombol Export GeoJSON -->
    <div style="width:80vw; margin: 0 auto 30px auto; text-align: center;">
        <button id="btn-export-geojson"
            style="padding:8px 15px; background:#28a745; color:white; border:none; border-radius:7px; cursor:pointer;">
            Export Data Halte & Wisata ke GeoJSON
        </button>
    </div>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        // =================== MAP JS MULAI DI SINI ===================
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

        // Di luar event listener, mungkin setelah semua data halte dimuat
        let transferPoints = [];
        function findTransferPoints() {
            const allHalteNamesK1 = halteK1Markers.map(m => m.feature.properties.nama_halte || m.feature.properties.nama);
            const allHalteNamesK2 = halteK2Markers.map(m => m.feature.properties.nama_halte || m.feature.properties.nama);

            transferPoints = [];
            allHalteNamesK1.forEach(namaK1 => {
                if (allHalteNamesK2.includes(namaK1)) {
                    // Cari fitur halte sebenarnya untuk mendapatkan detail jika perlu
                    const halteFeature = halteGeoFeatures.find(f => (f.properties.nama_halte || f.properties.nama) === namaK1);
                    if (halteFeature) {
                        transferPoints.push(halteFeature); // Simpan fitur halte transit
                    }
                }
            });
            console.log("Transfer Points:", transferPoints.map(tp => tp.properties.nama_halte || tp.properties.nama));
        }

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
                            const kategori = feature.properties.kategori;
                            const marker = L.marker(latlng, {
                                icon: getIconByCategory(kategori)
                            });
                            wisataMarkers.push({
                                marker,
                                feature
                            });
                            return marker;
                        },
                        onEachFeature: (feature, layer) => {
                            const p = feature.properties;
                            const fotoPath = p.foto ? `/images/${p.foto.replace('.png','.jpg')}` :
                                '';
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

                                    const dLat = (feature.geometry.coordinates[1] -
                                        hLat) * Math.PI / 180;
                                    const dLng = (feature.geometry.coordinates[0] -
                                        hLng) * Math.PI / 180;
                                    const a = Math.sin(dLat / 2) ** 2 +
                                        Math.cos(feature.geometry.coordinates[1] *
                                            Math.PI / 180) * Math.cos(hLat * Math
                                            .PI / 180) *
                                        Math.sin(dLng / 2) ** 2;
                                    const c = 2 * Math.atan2(Math.sqrt(a), Math
                                        .sqrt(1 - a));
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
                                        [feature.geometry.coordinates[1], feature
                                            .geometry.coordinates[0]
                                        ],
                                        [nearestHalte.latitude, nearestHalte
                                            .longitude
                                        ]
                                    ], {
                                        color: 'red',
                                        weight: 2,
                                        dashArray: '5, 5',
                                        opacity: 0.8
                                    }).addTo(rekomendasiLayer);

                                    const halteMarker = L.marker([nearestHalte.latitude,
                                        nearestHalte.longitude
                                    ], {
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
                    map.setView(latlng, 16, {
                        animate: true
                    });

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

        function getIconByCategory(kategori) {
            const icons = {
                "Museum": "Museum.png",
                "Taman Kota": "Taman_Kota.png",
                "Kebun Botani": "Kebun_Botani.png",
                "Perpustakaan": "Perpustakaan.png",
                "Herbarium": "Herbarium.png",
                "Galeri Seni": "Galeri_Seni.png"
            };

            const iconFile = icons[kategori.trim()] || "wisata.png";
            return L.icon({
                iconUrl: `/icons/${iconFile}`,
                iconSize: [30, 30],
                iconAnchor: [15, 30],
                popupAnchor: [0, -30]
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

        // Jalur, Halte, dan List Halte
        const jalurK1Layer = L.layerGroup();
        const jalurK1BalikLayer = L.layerGroup();
        const jalurK2Layer = L.layerGroup();
        const jalurK2BalikLayer = L.layerGroup();
        const halteK1Layer = L.layerGroup();
        const halteK2Layer = L.layerGroup();

        let halteK1Markers = [];
        let halteK2Markers = [];

        // Jalur & Halte loading
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
                style: {
                    color: "black",
                    weight: 1.2,
                    opacity: 1,
                    dashArray: "6,8"
                }
            }).addTo(jalurK1Layer);
            L.geoJSON(jalurK1Data, {
                style: {
                    color: "orange",
                    weight: 4,
                    opacity: 1,
                    dashArray: "6,8"
                }
            }).addTo(jalurK1Layer);
            jalurK1Layer.addTo(map);

            // Jalur K1 (balik)
            const jalurK1BalikGeo = L.geoJSON(jalurK1BalikData, {
                style: {
                    color: "black",
                    weight: 1.2,
                    opacity: 1,
                    dashArray: "6,8"
                }
            }).addTo(jalurK1BalikLayer);
            L.geoJSON(jalurK1BalikData, {
                style: {
                    color: "cyan",
                    weight: 4,
                    opacity: 1,
                    dashArray: "6,8"
                }
            }).addTo(jalurK1BalikLayer);
            jalurK1BalikLayer.addTo(map);

            // Jalur K2
            const jalurK2Geo = L.geoJSON(jalurK2Data, {
                style: {
                    color: "black",
                    weight: 1.2,
                    opacity: 1,
                    dashArray: "6,8"
                }
            }).addTo(jalurK2Layer);
            L.geoJSON(jalurK2Data, {
                style: {
                    color: "green",
                    weight: 4,
                    opacity: 1,
                    dashArray: "6,8"
                }
            }).addTo(jalurK2Layer);
            jalurK2Layer.addTo(map);

            // Jalur K2 (balik)
            const jalurK2BalikGeo = L.geoJSON(jalurK2BalikData, {
                style: {
                    color: "black",
                    weight: 1.2,
                    opacity: 1,
                    dashArray: "6,8"
                }
            }).addTo(jalurK2BalikLayer);
            L.geoJSON(jalurK2BalikData, {
                style: {
                    color: "blue",
                    weight: 4,
                    opacity: 1,
                    dashArray: "6,8"
                }
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
                    return L.marker(latlng, {
                        icon: halteIcon
                    });
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
                        direction: 'top',
                        offset: [0, -8],
                        permanent: false
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
                    return L.marker(latlng, {
                        icon: halteIcon
                    });
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
                        direction: 'top',
                        offset: [0, -8],
                        permanent: false
                    });
                }
            }).addTo(halteK2Layer);
            halteK2Layer.addTo(map);

            findTransferPoints(); 

            // Fit semua bounds
            const allBounds = L.featureGroup([
                jalurK1Geo, jalurK1BalikGeo, jalurK2Geo, jalurK2BalikGeo, halteK1Geo, halteK2Geo
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
                    const nama = marker.feature.properties.nama_halte || marker.feature.properties.nama || (
                        "Halte " + koridor);
                    const circleColor = koridor === 'K1' ? 'blue' : 'darkorange';
                    const borderColor = koridor === 'K1' ? '#244be4' : '#e09d00';

                    const li = document.createElement('li');
                    li.innerHTML = `
                <span style="display:inline-block; width:8px; height:8px; border-radius:50%; background:${circleColor}; margin-right:7px; vertical-align:middle; border:2px solid ${borderColor};"></span>
                <span class="halte-list-nama">${nama}</span>
            `;
                    li.querySelector('.halte-list-nama').onclick = () => {
                        map.setView(marker.getLatLng(), 16, {
                            animate: true
                        });
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
                    let nama = (layer.feature.properties.nama_halte || layer.feature.properties
                        .nama || '').toLowerCase();
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
                userLocationMarker = L.marker([lat, lng], {
                        icon: userIcon
                    })
                    .addTo(map)
                    .bindPopup(label)
                    .openPopup();
            }
            map.setView([lat, lng], 16, {
                animate: true
            });

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
                userLocationMarker.bindPopup(
                    `${label}<br>Halte terdekat: <strong>${nearestHalte.nama_halte || nearestHalte.nama}</strong><br>Jarak: ${minDist.toFixed(2)} km`
                ).openPopup();

                // Update info panel
                document.getElementById('nearest-halte-info').innerHTML = `
            Halte terdekat untuk lokasi Anda adalah <strong>${nearestHalte.nama_halte || nearestHalte.nama}</strong> dengan jarak sekitar <strong>${minDist.toFixed(2)} km</strong>.
        `;
            }
        }

        // Event klik peta untuk set posisi user manual
        map.on('click', function(e) {
            setUserLocationMarker(e.latlng.lat, e.latlng.lng, "Posisi Anda ");
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

        // Event highlight wisata dari Livewire
        window.addEventListener('show-on-map', function(event) {
            const data = event.detail[0];
            const lat = parseFloat(data.lat);
            const lng = parseFloat(data.lng);
            const nama = data.nama;

            if (isNaN(lat) || isNaN(lng)) return;

            map.setView([lat, lng], 16, {
                animate: true
            });

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
            document.getElementById('map-container')?.scrollIntoView({
                behavior: 'smooth'
            });
        });

        function findPathOnCorridor(startStopName, endStopName, corridorMarkers, corridorIdentity) {
        let segmentStepListObjects = []; // Akan menyimpan objek { name, corridor }
        const idxStart = corridorMarkers.findIndex(m => (m.feature.properties.nama_halte || m.feature.properties.nama) === startStopName);
        const idxEnd = corridorMarkers.findIndex(m => (m.feature.properties.nama_halte || m.feature.properties.nama) === endStopName);

        if (idxStart !== -1 && idxEnd !== -1) {
            let selectedMarkers;
            if (idxStart <= idxEnd) {
                selectedMarkers = corridorMarkers.slice(idxStart, idxEnd + 1);
            } else {
                selectedMarkers = corridorMarkers.slice(idxEnd, idxStart + 1).reverse();
            }
            // Map ke objek yang berisi nama dan koridor
            segmentStepListObjects = selectedMarkers.map(m => ({
                name: m.feature.properties.nama_halte || m.feature.properties.nama,
                corridor: corridorIdentity // Gunakan identitas koridor yang dilewatkan
            }));
            return segmentStepListObjects;
        }
        return null; // Tidak ada rute di koridor ini
    }

          // --- STEP BY STEP RUTE ---
        function setupStepByStepSelect() {
            // Tunggu data siap
            if (!Array.isArray(halteGeoFeatures) || halteGeoFeatures.length === 0 || !Array.isArray(allWisataFeatures) ||
                allWisataFeatures.length === 0) {
                setTimeout(setupStepByStepSelect, 300);
                return;
            }

            const halteSelect = document.getElementById("startHalteSelect");
            halteSelect.innerHTML = '<option value="">Pilih Halte Awal...</option>'; // Kosongkan dan tambahkan opsi default

            // --- MODIFIKASI TEKS OPSI DROPDOWN DIMULAI DI SINI ---

            // Buat Optgroup untuk Koridor K1
            const optgroupK1 = document.createElement('optgroup');
            optgroupK1.label = 'Koridor K1'; // Label grup tetap ada
            
            const k1Stops = halteGeoFeatures
                .filter(f => f.properties.rute === "K1")
                .sort((a, b) => (a.properties.urutan || 0) - (b.properties.urutan || 0)); // Urutkan berdasarkan 'urutan'

            k1Stops.forEach(feature => {
                const halteName = feature.properties.nama_halte || feature.properties.nama;
                if (!halteName) return; // Lewati jika tidak ada nama

                const opt = document.createElement('option');
                opt.value = halteName; // value tetap nama halte asli
                opt.textContent = halteName; // << PERUBAHAN: Hanya nama halte untuk tampilan dropdown
                optgroupK1.appendChild(opt);
            });

            if (optgroupK1.childNodes.length > 0) {
                halteSelect.appendChild(optgroupK1);
            }

            // Buat Optgroup untuk Koridor K2
            const optgroupK2 = document.createElement('optgroup');
            optgroupK2.label = 'Koridor K2'; // Label grup tetap ada

            const k2Stops = halteGeoFeatures
                .filter(f => f.properties.rute === "K2")
                .sort((a, b) => (a.properties.urutan || 0) - (b.properties.urutan || 0)); // Urutkan berdasarkan 'urutan'

            k2Stops.forEach(feature => {
                const halteName = feature.properties.nama_halte || feature.properties.nama;
                if (!halteName) return; // Lewati jika tidak ada nama

                const opt = document.createElement('option');
                opt.value = halteName; // value tetap nama halte asli
                opt.textContent = halteName; // << PERUBAHAN: Hanya nama halte untuk tampilan dropdown
                optgroupK2.appendChild(opt);
            });

            if (optgroupK2.childNodes.length > 0) {
                halteSelect.appendChild(optgroupK2);
            }

            // (Opsional) Tangani halte yang mungkin tidak memiliki properti 'rute'
            const otherStops = halteGeoFeatures.filter(f => !f.properties.rute || (f.properties.rute !== "K1" && f.properties.rute !== "K2"));
            if (otherStops.length > 0) {
                const optgroupLain = document.createElement('optgroup');
                optgroupLain.label = 'Halte Lain';
                otherStops.forEach(feature => {
                    const halteName = feature.properties.nama_halte || feature.properties.nama;
                    if (!halteName) return;
                    const opt = document.createElement('option');
                    opt.value = halteName;
                    opt.textContent = halteName; // Tanpa label koridor jika tidak jelas
                    optgroupLain.appendChild(opt);
                });
                halteSelect.appendChild(optgroupLain);
            }
            // --- AKHIR MODIFIKASI TEKS OPSI DROPDOWN ---

            // Populate options untuk wisata (tidak berubah)
            const wisataNames = allWisataFeatures.map(f => f.properties.nama);
            const wisataSelect = document.getElementById("endWisataSelect");
            wisataSelect.innerHTML = '<option value="">Pilih Tujuan Wisata...</option>';
            wisataNames.forEach(nama => {
                const opt = document.createElement('option');
                opt.value = nama;
                opt.textContent = nama;
                wisataSelect.appendChild(opt);
            });

            // EVENT BUTTON (logika di dalamnya SUDAH BENAR untuk menampilkan (Kx) di HASIL RUTE)
            document.getElementById('btn-stepbystep').addEventListener('click', function() {
                const startName = halteSelect.value; // Akan mengambil nama halte murni
                const endName = wisataSelect.value;

                const startHalte = halteGeoFeatures.find(f => (f.properties.nama_halte || f.properties.nama) ===
                    startName);
                const endWisata = allWisataFeatures.find(f => f.properties.nama === endName);

                if (!startHalte || !endWisata) {
                    document.getElementById('stepbystep-result').innerHTML =
                        "<span style='color:red'>Pilih halte awal dan tujuan wisata yang valid.</span>";
                    // tampilkanJalurKaki("", ""); // Jika fitur ini diabaikan, baris ini bisa dihapus/dikomentari
                    return;
                }

                const targetAlightingHalteNameFromWisata = endWisata.properties.halte_tujuan;
                // Pengecekan targetAlightingHalteNameFromWisata
                if (!targetAlightingHalteNameFromWisata) {
                    document.getElementById('stepbystep-result').innerHTML =
                        `<span style='color:red'>Informasi halte tujuan untuk wisata '${endName}' tidak tersedia di data.</span>`;
                    return;
                }
                const alightingStopFeature = halteGeoFeatures.find(f => (f.properties.nama_halte || f.properties.nama || '').toLowerCase() === targetAlightingHalteNameFromWisata.toLowerCase());
                // Pengecekan alightingStopFeature
                if (!alightingStopFeature) {
                    document.getElementById('stepbystep-result').innerHTML =
                        `<span style='color:red'>Halte tujuan '${targetAlightingHalteNameFromWisata}' yang direkomendasikan untuk wisata '${endName}' tidak ditemukan dalam data halte utama. Periksa kembali data GeoJSON wisata dan halte.</span>`;
                    return;
                }
                const alightingStopActualName = alightingStopFeature.properties.nama_halte || alightingStopFeature.properties.nama;
                
                let jarakHalteWisataKm = Infinity;
                const alightingCoords = alightingStopFeature.geometry.coordinates;
                const wisataCoords = endWisata.geometry.coordinates;
                const R = 6371; 
                const dLatRad = (wisataCoords[1] - alightingCoords[1]) * Math.PI / 180;
                const dLngRad = (wisataCoords[0] - alightingCoords[0]) * Math.PI / 180;
                const lat1Rad = alightingCoords[1] * Math.PI / 180;
                const lat2Rad = wisataCoords[1] * Math.PI / 180;
                const a_dist = Math.sin(dLatRad / 2) * Math.sin(dLatRad / 2) +
                        Math.cos(lat1Rad) * Math.cos(lat2Rad) *
                        Math.sin(dLngRad / 2) * Math.sin(dLngRad / 2);
                const c_dist = 2 * Math.atan2(Math.sqrt(a_dist), Math.sqrt(1 - a_dist));
                jarakHalteWisataKm = R * c_dist;

                let finalStepList = null;
                let routeType = ""; 

                let pathK1_Objects = findPathOnCorridor(startName, alightingStopActualName, halteK1Markers, "K1");
                if (pathK1_Objects) {
                    finalStepList = pathK1_Objects;
                    routeType = `Langsung Koridor K1`;
                }

                if (!finalStepList) {
                    let pathK2_Objects = findPathOnCorridor(startName, alightingStopActualName, halteK2Markers, "K2");
                    if (pathK2_Objects) {
                        finalStepList = pathK2_Objects;
                        routeType = `Langsung Koridor K2`;
                    }
                }

                if (!finalStepList && transferPoints.length > 0) {
                    for (const transferStopFeature of transferPoints) {
                        const transferStopName = transferStopFeature.properties.nama_halte || transferStopFeature.properties.nama;

                        let segment1_K1_Objects = findPathOnCorridor(startName, transferStopName, halteK1Markers, "K1");
                        if (segment1_K1_Objects && segment1_K1_Objects.length > 0) {
                            let segment2_K2_Objects = findPathOnCorridor(transferStopName, alightingStopActualName, halteK2Markers, "K2");
                            if (segment2_K2_Objects && segment2_K2_Objects.length > 1) {
                                finalStepList = segment1_K1_Objects.concat(segment2_K2_Objects.slice(1));
                                routeType = `Transit via ${transferStopName} (K1 -> K2)`;
                                break; 
                            }
                        }

                        if (finalStepList) break; 

                        let segment1_K2_Objects = findPathOnCorridor(startName, transferStopName, halteK2Markers, "K2");
                        if (segment1_K2_Objects && segment1_K2_Objects.length > 0) {
                            let segment2_K1_Objects = findPathOnCorridor(transferStopName, alightingStopActualName, halteK1Markers, "K1");
                            if (segment2_K1_Objects && segment2_K1_Objects.length > 1) {
                                finalStepList = segment1_K2_Objects.concat(segment2_K1_Objects.slice(1));
                                routeType = `Transit via ${transferStopName} (K2 -> K1)`;
                                break; 
                            }
                        }
                    }
                }

                if (finalStepList && finalStepList.length > 0) {
                    let displayTextList = finalStepList.map(item => `<li>${item.name} (${item.corridor})</li>`);
                    displayTextList.push(`<li>${endWisata.properties.nama} <span style='color:#244be4'>(Wisata)</span></li>`);
                    let ruteInfoTambahan = "";
                    if (routeType.includes("Transit")) {
                        const viaHalte = routeType.split("via ")[1].split(" (")[0];
                        const koridorAwal = routeType.includes("(K1 -> K2)") ? "K1" : "K2";
                        const koridorBerikutnya = routeType.includes("(K1 -> K2)") ? "K2" : "K1";
                        ruteInfoTambahan = `<br><small>Jenis Rute: ${routeType}. Turun di ${viaHalte} (${koridorAwal}), lalu lanjutkan dengan Koridor ${koridorBerikutnya}.</small>`;
                    } else if (routeType) {
                        ruteInfoTambahan = `<br><small>Jenis Rute: ${routeType}.</small>`;
                    }
                    document.getElementById('stepbystep-result').innerHTML =
                        "<b>Rute:</b><ol style='margin:0; padding-left:22px;'>" +
                        displayTextList.join('') +
                        "</ol>" +
                        `<div style="margin-top:8px; color:#555;">Total halte bus dilewati: <b>${finalStepList.length}</b> <br>Jarak dari halte terakhir ke wisata: <b>${jarakHalteWisataKm.toFixed(2)} km</b>${ruteInfoTambahan}</div>`;
                } else {
                    document.getElementById('stepbystep-result').innerHTML =
                        `<span style='color:red'>Tidak ditemukan rute (langsung maupun dengan satu kali transit) dari '${startName}' ke halte tujuan '${alightingStopActualName}'.</span>`;
                }
                // Tampilkan JALUR KAKI otomatis
                tampilkanJalurKaki(
                    nearestHalte.properties.nama_halte || nearestHalte.properties.nama,
                    endWisata.properties.nama
                );
            });
        }
        setupStepByStepSelect();


        // JALUR JALAN KAKI INTEGRASI
        let jalurKakiGeoJSON = null;
        let jalurKakiLayer = L.layerGroup().addTo(map);

        fetch('/maps/jalur_kaki.geojson')
            .then(res => res.json())
            .then(data => {
                jalurKakiGeoJSON = data;
            });

        function tampilkanJalurKaki(halteTujuan, wisataTujuan) {
            jalurKakiLayer.clearLayers();
            if (!jalurKakiGeoJSON) return;
            let fitur = jalurKakiGeoJSON.features.find(f =>
                (f.properties.halte_tujuan || '').toLowerCase() === (halteTujuan || '').toLowerCase() &&
                (f.properties.wisata_bogor || '').toLowerCase() === (wisataTujuan || '').toLowerCase()
            );
            if (fitur) {
                fitur.geometry.coordinates.forEach(line => {
                    let latlngs = line.map(([lng, lat]) => [lat, lng]);
                    L.polyline(latlngs, {
                        color: 'purple',
                        weight: 5,
                        dashArray: '8, 8'
                    }).addTo(jalurKakiLayer);
                    L.marker(latlngs[0], {
                        icon: L.divIcon({
                            className: '',
                            html: '<b style="color:purple;">Mulai</b>'
                        })
                    }).addTo(jalurKakiLayer);
                    L.marker(latlngs[latlngs.length - 1], {
                        icon: L.divIcon({
                            className: '',
                            html: '<b style="color:purple;">Wisata</b>'
                        })
                    }).addTo(jalurKakiLayer);
                });
            }
        }

        // Fungsi Export GeoJSON dan download file
        function downloadGeoJSON(data, filename) {
            const blob = new Blob([JSON.stringify(data, null, 2)], {
                type: "application/json"
            });
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

        // END OF JS
    </script>




</x-layout>.
