{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <title>Document</title>

    <style>
        #map {
            height: 100vh;
            position: relative;
        }

        path.leaflet-interactive:focus {
            outline: none;
        }

        select {
            position: absolute;
            top: 30px;
            left: 20px;
            width: 35% !important;
            z-index: 1000;
            background-color: 'white';
            padding: 10px;
            border-radius: 5px;
        }

        .info h4 {
            margin: 0 0 5px;
            color: #777;
        }

        .legend {
            text-align: left;
            line-height: 30px;
            color: #555;
            background-color: white;
            padding: 15px;
            border-radius: 15px;
        }

        .legend i {
            width: 18px;
            height: 30px;
            float: left;
            margin-right: 8px;
            opacity: 0.7;
        }
    </style>
</head>

<body>
    <div class="col-lg-12">
        <div id="map" style="z-index: 0;"></div>
        <div class="container">
            <div class="col-md-3">
                <select name="kategori" id="kategori" class="form-select">
                    <option value="" selected>Luas Desa</option>
                    <option value="hutan_rapat">Hutan Rapat</option>
                    <option value="hutan_sedang">Hutan Sedang</option>
                    <option value="hutan_jarang">Hutan Jarang</option>
                    <option value="belukar">Belukar</option>

                </select>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <!-- Kode JavaScript untuk Leaflet -->
    <script>
        var initialCenter = [-6.6649917947475465, 106.53825208668195];
        var initialZoom = 12;
        var osm = new L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
        }).bringToBack();

        // SATELITE
        var MapBox = L.tileLayer(
            'https://api.mapbox.com/styles/v1/mapbox/satellite-streets-v10/tiles/256/{z}/{x}/{y}?access_token=pk.eyJ1IjoiYW50aGluc3QxIiwiYSI6ImNpbXJ1aGRtYTAxOGl2aG00dTF4ZTBlcmcifQ.k95ENmlDX1roCRKSFlgCNw', {
                attribution: 'Imagery from <a href="http://mapbox.com/about/maps/">MapBox</a> &mdash; Map data &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                id: 'vzett'
            }).bringToBack();

        // STREET MAP
        var worldmap = L.tileLayer(
            'https://api.mapbox.com/styles/v1/mapbox/light-v9/tiles/256/{z}/{x}/{y}?access_token=pk.eyJ1IjoiYW50aGluc3QxIiwiYSI6IjNkYjRiZTdhNTg4NjMyMjg0NTQ2ODlhYjk4NjNmOTEyIn0.NktrsSF9BBKAN1USj5ftCw', {
                attribution: 'Imagery from <a href="http://mapbox.com/about/maps/">MapBox</a> &mdash; Map data &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                id: 'vzett'
            })
        var StreetArc = L.tileLayer(
                'https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}', {})
            .bringToBack();


        // Bathymetry



        var baseMaps = {
            "World Street": StreetArc,
            "Open Street Maps": osm,
            "MapBox": MapBox,
            "World Map": worldmap,

        }
        var map = L.map('map', {
            maxZoom: 20,
            minZoom: 1,
            // zoomSnap: 0.25,
            zoomControl: false,
            layers: [StreetArc],
            attributionControl: false
        });
        map.setView(initialCenter, 5.5);
        map.flyTo([-6.6649917947475465, 106.53825208668195], 12, {
            animated: true,
            duration: 3
        })


        var layerControl = L.control.layers(baseMaps).addTo(map);
        var desa = @json($geojson->pluck('geojson')->toArray());
        var dataCarbon = @json($carbon->toArray());
        let geoJsonLayer = desa;
        let layerGroupDesa = L.layerGroup()
        let url;
        let geojson;

        getGeoJson = async (kategori = '') => {
            for (i in geoJsonLayer) {
                var data = geoJsonLayer[i]


                url = `{{ asset('GEOJSON') }}/${data}`
                let get = await fetch(url)
                let json = await get.json();
                // console.log(url)
                geojson = L.geoJson(json, {
                    onEachFeature: onEachFeature,
                    style: (feature) => style(feature, kategori)
                }).addTo(layerGroupDesa)
            }
        }
        getGeoJson().then(() => {
            layerGroupDesa.addTo(map)
        })

        function getColor(d, kategori = '') {
            if (kategori == '') {

                return d > 6000 ? '#FF0000' : // Merah tua
                    d > 5000 ? '#FF4000' : // Oranye tua
                    d > 4000 ? '#FF8000' : // Oranye
                    d > 3000 ? '#FFCC00' : // Kuning tua
                    d > 2000 ? '#FFFF00' : // Kuning
                    d > 1000 ? '#FFFF60' : // Kuning muda
                    d > 0 ? '#ffff9e' : // Kuning pucat
                    '#808080'; // Abu-abu
            } else if (kategori == 'hutan_rapat' || kategori == 'hutan_sedang') {
                return d > 2000 ? '#FF0000 ' :
                    d > 1500 ? '#FF4000' :
                    d > 1000 ? '#FF8000' :
                    d > 500 ? '#FFCC00' :
                    d > 200 ? '#FFFF00' :
                    d > 50 ? '#FFFF60' :
                    d > 0 ? '#ffff9e' :
                    '#808080 ';
            } else {
                return d > 600 ? '#FF0000 ' :
                    d > 300 ? '#FF4000' :
                    d > 100 ? '#FF8000' :
                    d > 50 ? '#FFCC00' :
                    d > 20 ? '#FFFF00' :
                    d > 10 ? '#FFFF60' :
                    d > 0 ? '#ffff9e ' :
                    '#808080 ';
            }
        }

        function style(feature, kategori = '') {
            // console.log(feature)
            let totaldata = 0
            // console.log(totaldata)


            for (i in dataCarbon) {
                if (dataCarbon[i].desa.nama_desa == feature.properties.WADMKD) {
                    if (kategori === '') {

                        totaldata = feature.properties.Luas
                    } else {

                        totaldata = dataCarbon[i][`${kategori}`]
                    }
                }
            }

            return {
                color: 'white',
                fillColor: getColor(totaldata, kategori),
                weight: 2,
                opacity: 1,
                fillOpacity: 0.7,
                dashArray: 3
            }
        }






        function onEachFeature(feature, layer) {
            let kategori;
            let html = 'Silahkan Filter terlebih dahulu untuk melihat jumlah inovasi, inovator, dan grafik';
            if (feature.properties.WADMKD) {

                for (i in dataCarbon) {
                    var data = dataCarbon[i]
                    if (data.desa.nama_desa == feature.properties.WADMKD) {

                        kategori = $('#kategori').find(':selected').val()
                        textKategori = $('#kategori').find(':selected').text()
                        if (kategori === '') {
                            html = '<b style="font-size:15px">' + data.desa.nama_desa + '</b><hr>';
                            html += 'Kategori: ' + textKategori + '<br>';
                            html += `Luas:  <b>${data['luas_area']}  Ha</b>`;
                        } else {
                            // Filter data berdasarkan kategori terpilih
                            if (dataCarbon.length > 0) {
                                html = '<b style="font-size:15px">' + data.desa.nama_desa + '</b><hr>';
                                html += 'Kategori: ' + textKategori + '<br>';
                                html += 'Luas: <b>' + data[`${kategori}`] + ' Ha</b>';
                            } else {
                                html = '<b style="font-size:15px">' + data.desa.nama_desa +
                                    '</b><hr>Tidak ada data untuk kategori ' + kategori;
                            }
                        }
                    }


                }

                layer.bindPopup(html), layer.bindTooltip(feature.properties.WADMKD, {
                    permanent: false,
                    direction: "center",
                    className: "leaf"
                })
            }
            layer.on({
                mouseover: highlightFeature,
                mouseout: resetHighlight,

            });
        }

        function highlightFeature(e) {
            var layer = e.target;

            layer.setStyle({
                weight: 5,
                color: '#4E0101',
                dashArray: '',
                fillOpacity: 0.7
            });

            if (!L.Browser.ie && !L.Browser.opera && !L.Browser.edge) {
                layer.bringToFront();
            }
        }

        function resetHighlight(e) {
            geojson.resetStyle(e.target);
        }


        var legend = L.control({
            position: 'bottomleft'
        });

        function updatedLegend(kategori = '') {
            if (legend) {
                map.removeControl(legend)
            }
            var grades;
            if (kategori == '') {

                grades = [0, 1000, 2000, 3000, 4000, 5000, 6000]
            } else if (kategori == 'hutan_rapat' || kategori == 'hutan_sedang') {

                grades = [0, 50, 200, 500, 1000, 1500, 2000]
            } else {

                grades = [0, 10, 20, 50, 100, 300, 600]
            }
            legend = L.control({
                position: 'bottomleft'
            });
            legend.onAdd = function(map) {
                var div = L.DomUtil.create('div', 'info legend'),
                    labels = [];
                div.innerHTML = '<i style="background:#686869"></i> 0 Ha<br>';
                for (var i = 0; i < grades.length; i++) {
                    var lowValue = (grades[i] >= 0) ? (grades[i] + 1) : grades[i];
                    var highValue = grades[i + 1];
                    div.innerHTML +=
                        '<i style="background:' +
                        getColor(grades[i] + 1, kategori) +
                        '"></i> ' +
                        lowValue +
                        (highValue ? " &ndash; " + highValue + " Ha<br> " : "+ Ha");
                }
                return div;
            };

            legend.addTo(map);
        };

        updatedLegend()
        $('#kategori').on('change', function() {
            var kategoriTerpilih = $(this).val()
            layerGroupDesa.clearLayers()
            getGeoJson(kategoriTerpilih).then(() => {
                layerGroupDesa.addTo(map)

                updatedLegend(kategoriTerpilih)
            })
        })
    </script>
</body>

</html> --}}

@extends('templates.layouts.master')
@section('css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <style>
        #map {
            height: 80vh;
            position: relative;

        }

        path.leaflet-interactive:focus {
            outline: none;
        }

        select.form-select {
            color: #000
        }

        .info h4 {
            margin: 0 0 5px;
            color: #777;
        }

        .legend {
            text-align: left;
            line-height: 30px;
            color: #555;
            background-color: white;
            padding: 15px;
            border-radius: 15px;
        }

        .legend i {
            width: 18px;
            height: 30px;
            float: left;
            margin-right: 8px;
            opacity: 0.7;
        }
    </style>
@endsection

@section('content')
    <div class="row">

        <select name="kategori" id="kategori" class="form-select mb-4" style="width: 40%">
            <option value="" selected>Luas Desa</option>
            <option value="hutan_rapat">Hutan Rapat</option>
            <option value="hutan_sedang">Hutan Sedang</option>
            <option value="hutan_jarang">Hutan Jarang</option>
            <option value="belukar">Belukar</option>
        </select>
    </div>
    <div class="row">

        <div id="map"></div>
    </div>
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <!-- Kode JavaScript untuk Leaflet -->
    <script>
        $(document).ready(function() {
            $('.page-header').remove()
        })
        var initialCenter = [-6.6649917947475465, 106.53825208668195];
        var initialZoom = 12;
        var osm = new L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
        }).bringToBack();

        // SATELITE
        var MapBox = L.tileLayer(
            'https://api.mapbox.com/styles/v1/mapbox/satellite-streets-v10/tiles/256/{z}/{x}/{y}?access_token=pk.eyJ1IjoiYW50aGluc3QxIiwiYSI6ImNpbXJ1aGRtYTAxOGl2aG00dTF4ZTBlcmcifQ.k95ENmlDX1roCRKSFlgCNw', {
                attribution: 'Imagery from <a href="http://mapbox.com/about/maps/">MapBox</a> &mdash; Map data &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                id: 'vzett'
            }).bringToBack();

        // STREET MAP
        var worldmap = L.tileLayer(
            'https://api.mapbox.com/styles/v1/mapbox/light-v9/tiles/256/{z}/{x}/{y}?access_token=pk.eyJ1IjoiYW50aGluc3QxIiwiYSI6IjNkYjRiZTdhNTg4NjMyMjg0NTQ2ODlhYjk4NjNmOTEyIn0.NktrsSF9BBKAN1USj5ftCw', {
                attribution: 'Imagery from <a href="http://mapbox.com/about/maps/">MapBox</a> &mdash; Map data &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                id: 'vzett'
            })
        var StreetArc = L.tileLayer(
                'https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}', {})
            .bringToBack();


        // Bathymetry



        var baseMaps = {
            "World Street": StreetArc,
            "Open Street Maps": osm,
            "MapBox": MapBox,
            "World Map": worldmap,

        }
        var map = L.map('map', {
            maxZoom: 20,
            minZoom: 1,
            // zoomSnap: 0.25,
            zoomControl: false,
            layers: [StreetArc],
            attributionControl: false
        });
        map.setView(initialCenter, 5.5);
        map.flyTo([-6.6649917947475465, 106.53825208668195], 12, {
            animated: true,
            duration: 3
        })


        var layerControl = L.control.layers(baseMaps).addTo(map);
        var desa = @json($geojson->pluck('geojson')->toArray());
        var dataCarbon = @json($carbon->toArray());
        let geoJsonLayer = desa;
        let layerGroupDesa = L.layerGroup()
        let url;
        let geojson;

        getGeoJson = async (kategori = '') => {
            for (i in geoJsonLayer) {
                var data = geoJsonLayer[i]


                url = `{{ asset('GEOJSON') }}/${data}`
                let get = await fetch(url)
                let json = await get.json();
                // console.log(url)
                geojson = L.geoJson(json, {
                    onEachFeature: onEachFeature,
                    style: (feature) => style(feature, kategori)
                }).addTo(layerGroupDesa)
            }
        }
        getGeoJson().then(() => {
            layerGroupDesa.addTo(map)
        })

        function getColor(d, kategori = '') {
            if (kategori == '') {

                return d > 6000 ? '#FF0000' : // Merah tua
                    d > 5000 ? '#FF4000' : // Oranye tua
                    d > 4000 ? '#FF8000' : // Oranye
                    d > 3000 ? '#FFCC00' : // Kuning tua
                    d > 2000 ? '#FFFF00' : // Kuning
                    d > 1000 ? '#FFFF60' : // Kuning muda
                    d > 0 ? '#ffff9e' : // Kuning pucat
                    '#808080'; // Abu-abu
            } else if (kategori == 'hutan_rapat' || kategori == 'hutan_sedang') {
                return d > 2000 ? '#FF0000 ' :
                    d > 1500 ? '#FF4000' :
                    d > 1000 ? '#FF8000' :
                    d > 500 ? '#FFCC00' :
                    d > 200 ? '#FFFF00' :
                    d > 50 ? '#FFFF60' :
                    d > 0 ? '#ffff9e' :
                    '#808080 ';
            } else {
                return d > 600 ? '#FF0000 ' :
                    d > 300 ? '#FF4000' :
                    d > 100 ? '#FF8000' :
                    d > 50 ? '#FFCC00' :
                    d > 20 ? '#FFFF00' :
                    d > 10 ? '#FFFF60' :
                    d > 0 ? '#ffff9e ' :
                    '#808080 ';
            }
        }

        function style(feature, kategori = '') {
            // console.log(feature)
            let totaldata = 0
            // console.log(totaldata)


            for (i in dataCarbon) {
                if (dataCarbon[i].desa.nama_desa == feature.properties.WADMKD) {
                    if (kategori === '') {

                        totaldata = feature.properties.Luas
                    } else {

                        totaldata = dataCarbon[i][`${kategori}`]
                    }
                }
            }

            return {
                color: 'white',
                fillColor: getColor(totaldata, kategori),
                weight: 2,
                opacity: 1,
                fillOpacity: 0.7,
                dashArray: 3
            }
        }






        function onEachFeature(feature, layer) {
            let kategori;
            let html = 'Silahkan Filter terlebih dahulu untuk melihat jumlah inovasi, inovator, dan grafik';
            if (feature.properties.WADMKD) {

                for (i in dataCarbon) {
                    var data = dataCarbon[i]
                    if (data.desa.nama_desa == feature.properties.WADMKD) {

                        kategori = $('#kategori').find(':selected').val()
                        textKategori = $('#kategori').find(':selected').text()
                        if (kategori === '') {
                            html = '<b style="font-size:15px">' + data.desa.nama_desa + '</b><hr>';
                            html += 'Kategori: ' + textKategori + '<br>';
                            html += `Luas:  <b>${data['luas_area']}  Ha</b>`;
                        } else {
                            // Filter data berdasarkan kategori terpilih
                            if (dataCarbon.length > 0) {
                                html = '<b style="font-size:15px">' + data.desa.nama_desa + '</b><hr>';
                                html += 'Kategori: ' + textKategori + '<br>';
                                html += 'Luas: <b>' + data[`${kategori}`] + ' Ha</b>';
                            } else {
                                html = '<b style="font-size:15px">' + data.desa.nama_desa +
                                    '</b><hr>Tidak ada data untuk kategori ' + kategori;
                            }
                        }
                    }


                }

                layer.bindPopup(html), layer.bindTooltip(feature.properties.WADMKD, {
                    permanent: false,
                    direction: "center",
                    className: "leaf"
                })
            }
            layer.on({
                mouseover: highlightFeature,
                mouseout: resetHighlight,

            });
        }

        function highlightFeature(e) {
            var layer = e.target;

            layer.setStyle({
                weight: 5,
                color: '#4E0101',
                dashArray: '',
                fillOpacity: 0.7
            });

            if (!L.Browser.ie && !L.Browser.opera && !L.Browser.edge) {
                layer.bringToFront();
            }
        }

        function resetHighlight(e) {
            geojson.resetStyle(e.target);
        }


        var legend = L.control({
            position: 'bottomleft'
        });

        function updatedLegend(kategori = '') {
            if (legend) {
                map.removeControl(legend)
            }
            var grades;
            if (kategori == '') {

                grades = [0, 1000, 2000, 3000, 4000, 5000, 6000]
            } else if (kategori == 'hutan_rapat' || kategori == 'hutan_sedang') {

                grades = [0, 50, 200, 500, 1000, 1500, 2000]
            } else {

                grades = [0, 10, 20, 50, 100, 300, 600]
            }
            legend = L.control({
                position: 'bottomleft'
            });
            legend.onAdd = function(map) {
                var div = L.DomUtil.create('div', 'info legend'),
                    labels = [];
                div.innerHTML = '<i style="background:#686869"></i> 0 Ha<br>';
                for (var i = 0; i < grades.length; i++) {
                    var lowValue = (grades[i] >= 0) ? (grades[i] + 1) : grades[i];
                    var highValue = grades[i + 1];
                    div.innerHTML +=
                        '<i style="background:' +
                        getColor(grades[i] + 1, kategori) +
                        '"></i> ' +
                        lowValue +
                        (highValue ? " &ndash; " + highValue + " Ha<br> " : "+ Ha");
                }
                return div;
            };

            legend.addTo(map);
        };

        updatedLegend()
        $('#kategori').on('change', function() {
            var kategoriTerpilih = $(this).val()
            layerGroupDesa.clearLayers()
            getGeoJson(kategoriTerpilih).then(() => {
                layerGroupDesa.addTo(map)

                updatedLegend(kategoriTerpilih)
            })
        })
    </script>
@endsection
