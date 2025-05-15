<!DOCTYPE html>
<html>
<head>
    <title>Peta BisKita Bogor</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        html, body { margin: 0; padding: 0; height: 100%; }
        #map { height: 100vh; width: 100%; }
        #legend {
            position: absolute;
            bottom: 10px; left: 10px;
            background: white;
            padding: 10px; font-size: 14px;
            border-radius: 5px; box-shadow: 0 0 5px rgba(0,0,0,0.3);
            z-index: 1000;
        }
        #search-container {
            position: absolute;
            top: 15px; right: 15px; z-index: 1200;
            background: white;
            padding: 8px 12px;
            border-radius: 7px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.14);
            font-size: 15px;
        }
        #searchHalte {
            width: 160px; padding: 3px 7px;
        }
    </style>
</head>
<body>
    <div id="search-container">
        <input type="text" id="searchHalte" placeholder="Cari Halte...">
    </div>
    <div id="map"></div>
    <div id="legend">
        <strong>Legenda:</strong><br>
        <span style="color:orange;">&#8212;</span> Jalur K1<br>
        <span style="color:cyan;">&#8212;</span> Jalur K1 (balik)<br>
        <span style="color:green;">&#8212;</span> Jalur K2<br>
        <span style="color:blue;">&#8212;</span> Jalur K2 (balik)<br>
        <span style="color:blue;">●</span> Halte K1<br>
        <span style="color:darkorange;">●</span> Halte K2
    </div>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        const map = L.map('map').setView([-6.595038, 106.816635], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Layer groups
        const jalurK1Layer = L.layerGroup();
        const jalurK1BalikLayer = L.layerGroup();
        const jalurK2Layer = L.layerGroup();
        const jalurK2BalikLayer = L.layerGroup();
        const halteK1Layer = L.layerGroup();
        const halteK2Layer = L.layerGroup();

        // Array penampung marker halte untuk search filter
        let halteK1Markers = [];
        let halteK2Markers = [];

        // Fetch & render all layers
        Promise.all([
            fetch('/maps/jalur_k1.geojson').then(res => res.json()),
            fetch('/maps/jalur_k1(balik).geojson').then(res => res.json()),
            fetch('/maps/jalur_k2.geojson').then(res => res.json()),
            fetch('/maps/jalur_k2(balik).geojson').then(res => res.json()),
            fetch('/maps/biskita_k1.geojson').then(res => res.json()),
            fetch('/maps/biskita_k2.geojson').then(res => res.json())
        ]).then(([jalurK1Data, jalurK1BalikData, jalurK2Data, jalurK2BalikData, halteK1Data, halteK2Data]) => {
            // Semua jalur pakai dashArray & outline hitam
            const jalurK1Geo = L.geoJSON(jalurK1Data, {
                style: { color: "black", weight: 1, opacity: 1, dashArray: "6,8" }
            }).addTo(jalurK1Layer);
            L.geoJSON(jalurK1Data, {
                style: { color: "orange", weight: 4, opacity: 1, dashArray: "6,8" }
            }).addTo(jalurK1Layer);

            const jalurK1BalikGeo = L.geoJSON(jalurK1BalikData, {
                style: { color: "black", weight: 1, opacity: 1, dashArray: "6,8" }
            }).addTo(jalurK1BalikLayer);
            L.geoJSON(jalurK1BalikData, {
                style: { color: "cyan", weight: 4, opacity: 1, dashArray: "6,8" }
            }).addTo(jalurK1BalikLayer);

            const jalurK2Geo = L.geoJSON(jalurK2Data, {
                style: { color: "black", weight: 1, opacity: 1, dashArray: "6,8" }
            }).addTo(jalurK2Layer);
            L.geoJSON(jalurK2Data, {
                style: { color: "green", weight: 4, opacity: 1, dashArray: "6,8" }
            }).addTo(jalurK2Layer);

            const jalurK2BalikGeo = L.geoJSON(jalurK2BalikData, {
                style: { color: "black", weight: 1, opacity: 1, dashArray: "6,8" }
            }).addTo(jalurK2BalikLayer);
            L.geoJSON(jalurK2BalikData, {
                style: { color: "blue", weight: 4, opacity: 1, dashArray: "6,8" }
            }).addTo(jalurK2BalikLayer);

            jalurK1Layer.addTo(map);
            jalurK1BalikLayer.addTo(map);
            jalurK2Layer.addTo(map);
            jalurK2BalikLayer.addTo(map);

            // Halte K1
            const halteK1Geo = L.geoJSON(halteK1Data, {
                pointToLayer: (feature, latlng) => L.circleMarker(latlng, {
                    radius: 7,
                    fillColor: "blue",
                    color: "black",      // stroke hitam
                    weight: 1.2,
                    opacity: 1,
                    fillOpacity: 0.85
                }),
                onEachFeature: (feature, layer) => {
                    const props = feature.properties;
                    halteK1Markers.push(layer); // simpan marker untuk search
                    const popup = `
                        <div style="min-width:180px;">
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
                pointToLayer: (feature, latlng) => L.circleMarker(latlng, {
                    radius: 7,
                    fillColor: "darkorange",
                    color: "black",      // stroke hitam
                    weight: 1.2,
                    opacity: 1,
                    fillOpacity: 0.85
                }),
                onEachFeature: (feature, layer) => {
                    const props = feature.properties;
                    halteK2Markers.push(layer); // simpan marker untuk search
                    const popup = `
                        <div style="min-width:180px;">
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

            // Fit bounds semua layer
            const allBounds = L.featureGroup([
                jalurK1Geo, jalurK1BalikGeo, jalurK2Geo, jalurK2BalikGeo,
                halteK1Geo, halteK2Geo
            ]).getBounds();
            map.fitBounds(allBounds);

            // SEARCH FILTER
            document.getElementById("searchHalte").addEventListener("input", function() {
                let searchVal = this.value.trim().toLowerCase();

                // Halte K1
                halteK1Markers.forEach(layer => {
                    let nama = (layer.feature.properties.nama_halte || layer.feature.properties.nama || '').toLowerCase();
                    if (nama.includes(searchVal) || searchVal === "") {
                        if (!halteK1Layer.hasLayer(layer)) halteK1Layer.addLayer(layer);
                    } else {
                        halteK1Layer.removeLayer(layer);
                    }
                });

                // Halte K2
                halteK2Markers.forEach(layer => {
                    let nama = (layer.feature.properties.nama_halte || layer.feature.properties.nama || '').toLowerCase();
                    if (nama.includes(searchVal) || searchVal === "") {
                        if (!halteK2Layer.hasLayer(layer)) halteK2Layer.addLayer(layer);
                    } else {
                        halteK2Layer.removeLayer(layer);
                    }
                });
            });
        });

        // Layer control (opsional, tetap bisa dipakai)
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
</body>
</html>
