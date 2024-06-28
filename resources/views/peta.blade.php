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
        <div class="col-md-6">
            <select name="kategori" id="kategori" class="form-select mb-4">
                <option value="4" selected>Luas Area</option>
                <option value="1">Biomasa</option>
                <option value="2">Carbon Stock</option>
                <option value="3">Potensi Emisi Carbon</option>
                {{-- <option value="belukar">Belukar</option> --}}
            </select>
        </div>
        <div class="col-md-6">
            <select name="subKategori" id="subKategori" class="form-select mb-4">
                {{-- <option value="" selected>Luas Area</option> --}}
                <option value="hutan_rapat">Hutan Rapat</option>
                <option value="hutan_sedang">Hutan Sedang</option>
                <option value="hutan_jarang">Hutan Jarang</option>
                <option value="belukar">Belukar</option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div id="map"></div>
        </div>
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
        let loadJsonData;
        let dataPeta;
        let layerGroupDesa = L.layerGroup()
        let url;
        let geojson;


        getGeoJson = async (subKategori = '') => {
            for (i in geoJsonLayer) {
                var data = geoJsonLayer[i]
                url = `{{ asset('GEOJSON') }}/${data}`
                let get = await fetch(url)
                let json = await get.json();
                // console.log(url)
                geojson = L.geoJson(json, {
                    onEachFeature: onEachFeature,
                    style: (feature) => style(feature, subKategori)
                }).addTo(layerGroupDesa)
            }
        }
        getGeoJson().then(() => {
            layerGroupDesa.addTo(map)
        })


        let changeData = async () => {
            var kategori = $('#kategori').find(':selected').val()
            var subKategori = $('#subKategori').find(':selected').val()
            // console.log(subKategori)
            let url = `{{ url('api/getDataPeta') }}?kategori_id=${kategori}&subCategory=${subKategori}`
            let get = await fetch(url)
            // console.log(get)
            loadJsonData = await get.json();
            dataPeta = loadJsonData['data']
            // console.log(dataPeta)
            layerGroupDesa.clearLayers()
            getGeoJson(subKategori).then(() => {
                updatedLegend(subKategori)
            })
        }
        changeData()
        // updatedLegend()
        $('#kategori , #subKategori').on('change', function() {
            changeData()
        })

        function popUp(f, l) {
            var kategori = $('#kategori').find(':selected').val()
            var subKategori = $('#subKategori').find(':selected').val()
            var textKategori = $('#subKategori').find(":selected").text()
            let html = ''
            // console.log(f)
            for (i in dataPeta) {
                var data = dataPeta[i]

                if (data.desa.nama_desa === f.properties.WADMKD) {
                    html = '<b style="font-size:15px">' + data.desa.nama_desa + '</b><hr>';
                    html += 'Luas Daerah: <b>' + data.desa.luas_desa + ' Km<sup>2</sup></b>' + '<br>';
                    html += 'Kategori: <b>' + data.kategori.nama_kategori + ' ' + '</b>(' + textKategori + ')<br>';
                    html += 'Nilai Karbon: <b>' + data[`${subKategori}`] + ' ' + data.kategori.satuan + '</b>';
                }
            }
            l.bindPopup(html), l.bindTooltip(f.properties.WADMKD)
        }

        function getColor(d, subKategori = '') {
            var kategori = $('#kategori').find(':selected').val();

            if (kategori == '4') {
                return d > 2000 ? '#FF0000' :
                    d > 1500 ? '#FF4000' :
                    d > 1000 ? '#FF8000' :
                    d > 500 ? '#FFCC00' :
                    d > 200 ? '#FFFF00' :
                    d > 50 ? '#FFFF60' :
                    d > 0 ? '#ffff9e' :
                    '#808080';
            } else {
                return d > 600 ? '#FF0000' :
                    d > 300 ? '#FF4000' :
                    d > 100 ? '#FF8000' :
                    d > 50 ? '#FFCC00' :
                    d > 20 ? '#FFFF00' :
                    d > 10 ? '#FFFF60' :
                    d > 0 ? '#ffff9e' :
                    '#808080';
            }
        }

        function style(feature, subKategori = '') {
            // console.log(feature)
            let totaldata = 0
            // console.log(totaldata)


            for (i in dataPeta) {
                if (dataPeta[i]['desa']['nama_desa'] === feature.properties.WADMKD) {

                    totaldata = dataPeta[i][`${subKategori}`]
                }
            }

            return {
                color: 'white',
                fillColor: getColor(totaldata, subKategori),
                weight: 2,
                opacity: 1,
                fillOpacity: 0.7,
                dashArray: 3
            }
        }






        function onEachFeature(feature, layer) {
            popUp(feature, layer)
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

        function updatedLegend(subKategori = '') {
            var kategori = $('#kategori').find(':selected').val();
            if (legend) {
                map.removeControl(legend)
            }
            var grades;
            if (kategori == '4') {

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

        // updatedLegend()
        // $('#kategori').on('change', function() {
        //     var kategoriTerpilih = $(this).val()
        //     layerGroupDesa.clearLayers()
        //     getGeoJson(kategoriTerpilih).then(() => {
        //         layerGroupDesa.addTo(map)

        //         updatedLegend(kategoriTerpilih)
        //     })
        // })
    </script>
@endsection
