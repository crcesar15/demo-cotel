@extends('layouts.app')

@push('styles')
    <style>
        /* Set the size of the div element that contains the map */
        #map {  /* The height is 400 pixels */
            width: 100%;  /* The width is the width of the web page */
        }
    </style>
@endpush

@section('navbar')
    @include('partials.navbar')
@stop

@section('sidebar')
    @include('partials.sidebar')
@stop

@section('content')
    <div class="page-wrapper">
        <!--<h1 class="text-center pt-3">Demo - Bayoex S.R.L.</h1>-->
        <div class="container-fluid pt-2">
            <div class="row">
                <div class="col-12 col-md-10">
                    <div id="map"></div>
                </div>
                <div class="col-12 col-md-2">
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h5 class="card-title text-center text-white">Capas del Mapa</h5>
                        </div>
                        <div class="card-body">
                            <h6 class="card-title">Selecciones las capas que desea ver</h6>
                            <div class="form-group row">
                                <div class="custom-control custom-checkbox mr-sm-2">
                                    <input type="checkbox" class="custom-control-input" value="0" id="armario">
                                    <label class="custom-control-label pl-4 pt-1" for="armario">Armarios</label>
                                </div>
                                <div class="custom-control custom-checkbox mr-sm-2">
                                    <input type="checkbox" class="custom-control-input" value="1" id="terminal">
                                    <label class="custom-control-label pl-4 pt-1" for="terminal">Cajas Terminales</label>
                                </div>
                                <div class="custom-control custom-checkbox mr-sm-2">
                                    <input type="checkbox" class="custom-control-input" value="2" id="camara">
                                    <label class="custom-control-label pl-4 pt-1" for="camara">Camaras</label>
                                </div>
                                <div class="custom-control custom-checkbox mr-sm-2">
                                    <input type="checkbox" class="custom-control-input" value="3" id="poste">
                                    <label class="custom-control-label pl-4 pt-1" for="poste">Postes</label>
                                </div>
                                <div class="custom-control custom-checkbox mr-sm-2">
                                    <input type="checkbox" class="custom-control-input" value="4" id="tap">
                                    <label class="custom-control-label pl-4 pt-1" for="tap">Taps</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('partials.footer')
@stop

@push('scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDYfXp15LPz_VuK75gqcgNicuwK1H-1BOw"></script>
    <script src="{{asset('js/maps.js')}}"></script>
    <script>
        let armarioMarkers = [];
        let terminalMarkers = [];
        let camaraMarkers = [];
        let posteMarkers = [];
        let tapMarkers = [];

        $(document).ready(() => {
            let height = ($( document ).height() - 50);
            $('#map').css('height',height);
            enable_map(false);
        });

        $('input#armario').click(async function() {
            let armario;
            let id;
            let armarios;
            let position;
            let marker;

            armario = $(this).prop('checked');
            if (armario){
                if (armarioMarkers.length){
                    for (let i = 0; i < armarioMarkers.length ; i++){
                        armarioMarkers[i].setMap(map);
                    }
                } else {
                    id = $(this).val();
                    armarios = await get_devices_by_type(id);
                    for (let i = 0; i < armarios.length ; i++){
                        position = {
                            lat: parseFloat(armarios[i].lat),
                            lng: parseFloat(armarios[i].lng)
                        };
                        marker = addCustomMarker(position, armarios[i], id);
                        armarioMarkers.push(marker);
                    }
                }
            } else{
                for (let i = 0; i < armarioMarkers.length ; i++){
                    armarioMarkers[i].setMap(null);
                }
            }
        });

        $('input#terminal').click(async function() {
            let terminal;
            let id;
            let terminales;
            let position;
            let marker;

            terminal = $(this).prop('checked');
            if (terminal){
                if (terminalMarkers.length){
                    for (let i = 0; i < terminalMarkers.length ; i++){
                        terminalMarkers[i].setMap(map);
                    }
                } else {
                    id = $(this).val();
                    terminales = await get_devices_by_type(id);
                    for (let i = 0; i < terminales.length ; i++){
                        position = {
                            lat: parseFloat(terminales[i].lat),
                            lng: parseFloat(terminales[i].lng)
                        };
                        marker = addCustomMarker(position, terminales[i], id);
                        terminalMarkers.push(marker);
                    }
                }
            } else{
                for (let i = 0; i < terminalMarkers.length ; i++){
                    terminalMarkers[i].setMap(null);
                }
            }
        });

        $('input#camara').click(async function() {
            let camara;
            let id;
            let camaras;
            let position;
            let marker;

            camara = $(this).prop('checked');
            if (camara){
                if (camaraMarkers.length){
                    for (let i = 0; i < camaraMarkers.length ; i++){
                        camaraMarkers[i].setMap(map);
                    }
                } else {
                    id = $(this).val();
                    camaras = await get_devices_by_type(id);
                    for (let i = 0; i < camaras.length ; i++){
                        position = {
                            lat: parseFloat(camaras[i].lat),
                            lng: parseFloat(camaras[i].lng)
                        };
                        marker = addCustomMarker(position, camaras[i], id);
                        camaraMarkers.push(marker);
                    }
                }
            } else{
                for (let i = 0; i < camaraMarkers.length ; i++){
                    camaraMarkers[i].setMap(null);
                }
            }
        });

        $('input#poste').click(async function() {
            let poste;
            let id;
            let postes;
            let position;
            let marker;

            poste = $(this).prop('checked');
            if (poste){
                if (posteMarkers.length){
                    for (let i = 0; i < posteMarkers.length ; i++){
                        posteMarkers[i].setMap(map);
                    }
                } else {
                    id = $(this).val();
                    postes = await get_devices_by_type(id);
                    for (let i = 0; i < postes.length ; i++){
                        position = {
                            lat: parseFloat(postes[i].lat),
                            lng: parseFloat(postes[i].lng)
                        };
                        marker = addCustomMarker(position, postes[i], id);
                        posteMarkers.push(marker);
                    }
                }
            } else{
                for (let i = 0; i < posteMarkers.length ; i++){
                    posteMarkers[i].setMap(null);
                }
            }
        });

        $('input#tap').click(async function() {
            let tap;
            let id;
            let taps;
            let position;
            let marker;

            tap = $(this).prop('checked');
            if (tap){
                if (tapMarkers.length){
                    for (let i = 0; i < tapMarkers.length ; i++){
                        tapMarkers[i].setMap(map);
                    }
                } else {
                    id = $(this).val();
                    taps = await get_devices_by_type(id);
                    for (let i = 0; i < taps.length ; i++){
                        position = {
                            lat: parseFloat(taps[i].lat),
                            lng: parseFloat(taps[i].lng)
                        };
                        marker = addCustomMarker(position, taps[i], id);
                        tapMarkers.push(marker);
                    }
                }
            } else{
                for (let i = 0; i < tapMarkers.length ; i++){
                    tapMarkers[i].setMap(null);
                }
            }
        });

        const get_devices_by_type = (id) => {
            return new Promise((response, reject) => {
                let url = '{{url('devices_by_type')}}';
                url = url + '/' +id;
                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'JSON',
                })
                .done((data) => {
                    response(data);
                })
                .fail(() => {
                    reject(0);
                });
            });
        };

        function addCustomMarker(location, data, index) {
            let contentString = '';
            let device_type = [];
            let device_text = [];

            device_type[0] = '{{asset('img/armario.png')}}';
            device_type[1] = '{{asset('img/tv.png')}}';
            device_type[2] = '{{asset('img/camera.png')}}';
            device_type[3] = '{{asset('img/poste.png')}}';
            device_type[4] = '{{asset('img/phone.png')}}';

            device_text[0] = 'Armario';
            device_text[1] = 'Terminal';
            device_text[2] = 'Camara';
            device_text[3] = 'Poste';
            device_text[4] = 'Tap';

            let image = {
                url: String(device_type[index]),
                labelOrigin: new google.maps.Point(15,35)
            };

            let infowindow = new google.maps.InfoWindow();

            let marker = new google.maps.Marker({
                position: location,
                label: {
                    text: device_text[index],
                    color: '#141344',
                    textShadow:'-10px 0 black, 0 10px black, 10px 0 black, 0 -10px black',
                    fontWeight: 'bold',
                    fontSize: '15px'
                },
                icon:image,
                map: map
            });

            marker.addListener('dblclick', function() {
                current_marker = marker;
                open_panorama(marker);
            });

            marker.addListener('click', function () {
                current_marker = marker;
                contentString =
                    `<div id="content">
                        <div id="siteNotice">
                        </div>

                        <h4 id="firstHeading" class="firstHeading">Detalles</h4>

                        <div id="bodyContent">
                            <pre>
                                 ${JSON.stringify(data, null, 2)}
                            </pre>
                        </div>
                    </div>`;
                infowindow.setContent(contentString);
                if (infowindow.getMap()){
                    infowindow.close();
                }else{
                    infowindow.open(map, marker);
                }
            });

            return marker;
        }
    </script>
@endpush