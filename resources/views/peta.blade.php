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
                /* tambahkan bottom space */
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

            @media (max-width: 700px) {
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

            .artikel-scroll-container {
                overflow-x: auto;
                padding: 80px;
                user-select: none;
            }

            .artikel-scroll-wrapper {
                display: flex;
                gap: 20px;
                flex-wrap: nowrap;
            }

            .artikel-card {
                flex: 0 0 auto;
                width: 300px;
                border: 1px solid #ddd;
                border-radius: 8px;
                overflow: hidden;
                background: #fff;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                display: flex;
                flex-direction: column;
                user-select: none;
            }

            .artikel-card img {
                width: 100%;
                height: 180px;
                object-fit: cover;
            }

            .artikel-content {
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                padding: 15px;
                height: 100%;
                flex-grow: 1;
            }

            .artikel-content h3 {
                margin-bottom: 5px;
                font-size: 18px;
            }

            .artikel-content p {
                margin-bottom: auto;
                font-size: 14px;
                color: #666;
            }

            .btn-wrapper {
                margin-top: 15px;
                text-align: center;
            }

            .btn-lg {
                padding: 8px 20px;
                font-size: 14px;
            }
        </style>
    @endsection
    <x-slot:title>
        Explore Bogor - Peta Interaktif
    </x-slot>
    <livewire:company.show-wisatas />
    <div id="nearest-halte-info" style="text-align:center; font-size:15px; margin-top:20px; color:#333;">
    </div>
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
            <span style="color:darkorange;">●</span> Halte K2
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
    <!-- List Halte K1/K2 di kanan bawah, dengan tab -->

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        const map = L.map('map').setView([-6.595038, 106.816635], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const wisataData = @json($wisatas);
        const wisataLayer = L.layerGroup().addTo(map);
        const rekomendasiLayer = L.layerGroup().addTo(map);
        let halteGeoFeatures = [];

        // Fetch GeoJSON halte
        Promise.all([
            fetch('/maps/biskita_k1.geojson').then(res => res.json()),
            fetch('/maps/biskita_k2.geojson').then(res => res.json())
        ]).then(([halteK1Data, halteK2Data]) => {
            halteGeoFeatures = [...halteK1Data.features, ...halteK2Data.features];

            wisataData.forEach(w => {
                const marker = L.marker([w.latitude, w.longitude]).addTo(wisataLayer);
                marker.bindPopup(`<strong>${w.nama}</strong><br>${w.kategori || ''}`);

                marker.on('mouseover', function() {
                    rekomendasiLayer.clearLayers();

                    let nearestHalte = null;
                    let minDist = Infinity;

                    halteGeoFeatures.forEach(h => {
                        const coords = h.geometry.coordinates;
                        const hLat = coords[1];
                        const hLng = coords[0];
                        const dLat = (w.latitude - hLat) * Math.PI / 180;
                        const dLng = (w.longitude - hLng) * Math.PI / 180;
                        const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                            Math.cos(w.latitude * Math.PI / 180) * Math.cos(hLat * Math.PI /
                                180) *
                            Math.sin(dLng / 2) * Math.sin(dLng / 2);
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
                            [w.latitude, w.longitude],
                            [nearestHalte.latitude, nearestHalte.longitude]
                        ], {
                            color: 'red',
                            weight: 2,
                            dashArray: '5, 5',
                            opacity: 0.8
                        }).addTo(rekomendasiLayer);

                        const halteMarker = L.marker([nearestHalte.latitude, nearestHalte
                            .longitude
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

                marker.on('mouseout', function() {
                    rekomendasiLayer.clearLayers();
                });
            });
        });

        // Tampilkan marker wisata dari database
        wisataData.forEach(w => {
            const marker = L.marker([w.latitude, w.longitude]).addTo(map);
            marker.bindPopup(`<strong>${w.nama}</strong><br>${w.kategori || ''}`);
        });

        // Layer groups
        const jalurK1Layer = L.layerGroup();
        const jalurK1BalikLayer = L.layerGroup();
        const jalurK2Layer = L.layerGroup();
        const jalurK2BalikLayer = L.layerGroup();
        const halteK1Layer = L.layerGroup();
        const halteK2Layer = L.layerGroup();

        // Array penampung marker halte untuk search/filter & list
        let halteK1Markers = [];
        let halteK2Markers = [];

        // Fetch & render semua jalur & halte
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
                        iconUrl: '/icons/bus_k1.png', // path ikon
                        iconSize: [28, 32], // ukuran ikon
                        iconAnchor: [14, 32], // titik bawah ikon
                        popupAnchor: [0, -30] // posisi popup relatif
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
                        iconUrl: '/icons/bus_k2.png', // path ikon
                        iconSize: [28, 32], // ukuran ikon
                        iconAnchor: [14, 32], // titik bawah ikon
                        popupAnchor: [0, -30] // posisi popup relatif
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

            // Fit bounds ke semua data
            const allBounds = L.featureGroup([
                jalurK1Geo, jalurK1BalikGeo, jalurK2Geo, jalurK2BalikGeo,
                halteK1Geo, halteK2Geo
            ]).getBounds();
            map.fitBounds(allBounds);

            // ==== LIST HALTE (K1/K2) DI KANAN BAWAH ====
            let halteList = document.getElementById("listHalte");
            let tabK1 = document.getElementById("tabK1");
            let tabK2 = document.getElementById("tabK2");

            function showHalteListKoridor(koridor) {
                halteList.innerHTML = '';
                const markers = koridor === "K1" ? halteK1Markers : halteK2Markers;
                markers.forEach((marker, idx) => {
                    const nama = marker.feature.properties.nama_halte || marker.feature.properties.nama || (
                        "Halte " + koridor);
                    const li = document.createElement('li');
                    li.innerHTML = `<span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:${koridor==='K1'?'orange':'darkorange'};margin-right:7px;vertical-align:middle;border:2px solid #e09d00"></span>
                                    <span class="halte-list-nama">${nama}</span>`;
                    li.querySelector('.halte-list-nama').onclick = () => {
                        map.setView(marker.getLatLng(), 16, {
                            animate: true
                        });
                        marker.openPopup();
                    };
                    halteList.appendChild(li);
                });
            }

            // default: tampilkan K1
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

            // ==== SEARCH FILTER ====
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

        // Layer control (optional)
        const overlayMaps = {
            "Jalur K1": jalurK1Layer,
            "Jalur K1 (balik)": jalurK1BalikLayer,
            "Jalur K2": jalurK2Layer,
            "Jalur K2 (balik)": jalurK2BalikLayer,
            "Halte K1": halteK1Layer,
            "Halte K2": halteK2Layer
        };
        L.control.layers(null, overlayMaps).addTo(map);
    </script>

    <script>
        const slider = document.querySelector('.artikel-scroll-container');
        let isDown = false;
        let startX;
        let scrollLeft;

        slider.addEventListener('mousedown', (e) => {
            isDown = true;
            slider.classList.add('active');
            startX = e.pageX - slider.offsetLeft;
            scrollLeft = slider.scrollLeft;
        });
        slider.addEventListener('mouseleave', () => {
            isDown = false;
            slider.classList.remove('active');
        });
        slider.addEventListener('mouseup', () => {
            isDown = false;
            slider.classList.remove('active');
        });
        slider.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - slider.offsetLeft;
            const walk = (x - startX) * 2;
            slider.scrollLeft = scrollLeft - walk;
        });
    </script>

    <script>
        window.addEventListener('show-on-map', function(event) {
            const data = event.detail[0];

            const lat = parseFloat(data.lat);
            const lng = parseFloat(data.lng);
            const nama = data.nama;

            console.log("✅ Parsed:", lat, lng, nama);

            if (isNaN(lat) || isNaN(lng)) {
                console.error("Invalid coordinates after parse:", lat, lng);
                return;
            }

            map.setView([lat, lng], 16, {
                animate: true
            });

            L.popup()
                .setLatLng([lat, lng])
                .setContent(`<strong>${nama}</strong>`)
                .openOn(map);

            // 3. Hapus garis & marker sebelumnya (jika ada)
            rekomendasiLayer.clearLayers();

            // 4. Cari halte terdekat dari lokasi wisata
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

            // 5. Tampilkan garis & marker halte terdekat
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

            // 6. Scroll otomatis ke peta
            document.getElementById('map-container')?.scrollIntoView({
                behavior: 'smooth'
            });
        });
    </script>





</x-layout>
