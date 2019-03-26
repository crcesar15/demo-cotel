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
                                    <input type="checkbox" class="custom-control-input" value="1" id="terminal">
                                    <label class="custom-control-label pl-4 pt-1" for="terminal">Terminales</label>
                                </div>
                                <div class="custom-control custom-checkbox mr-sm-2">
                                    <input type="checkbox" class="custom-control-input" value="2" id="tap">
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
        let tapMarkers = [];
        let terminalMarkers = [];

        $(document).ready(() => {
            let height = ($( document ).height() - 50);
            $('#map').css('height',height);
            enable_map(false);
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
                        marker = addCustomMarker(position,map,'Terminal',parseInt(terminales[i].connections),parseInt(terminales[i].busy));
                        terminalMarkers.push(marker);
                    }
                }
            } else{
                for (let i = 0; i < terminalMarkers.length ; i++){
                    terminalMarkers[i].setMap(null);
                }
            }
        });

        $('input#tap').click(async function(){
            let tap;
            let id;
            let taps;
            let position;
            let marker;
            tap = $(this).prop('checked');
            console.log($(this));
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
                        marker = addCustomMarker(position,map,'Tap',parseInt(taps[i].connections),parseInt(taps[i].busy));
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

        const addCustomMarker = (location, map, device, connections, busy) => {
            let contentString = '';
            let device_type = [];

            device_type['Terminal'] = '{{asset('img/tv.png')}}';
            device_type['Tap'] = '{{asset('img/phone.png')}}';

            let image = {
                url: device_type[device],
                labelOrigin: new google.maps.Point(15,35)
            };

            let infowindow = new google.maps.InfoWindow();

            let marker = new google.maps.Marker({
                position: location,
                label: {
                    text: device,
                    color: '#141344',
                    fontWeight: 'bold',
                    fontSize: '13px'
                },
                icon:image,
                map: map
            });

            marker.addListener('dblclick', function() {
                current_marker = marker;
                open_panorama(marker);
            });

            marker.addListener('click', () => {
                current_marker = marker;
                contentString =
                    `<div id="content">
                    <div id="siteNotice">
                    </div>
                    <h4 id="firstHeading" class="firstHeading">Disponibilidad</h4>
                    <div id="bodyContent">
                        <p><b>Conexiones: </b>${connections}<br>
                        <b>Ocupadas: </b>${busy}<br>
                        <b>Libres: </b>${connections-busy}</p>
                    </div>
                    </div>`;
                infowindow.setContent(contentString);
                current_marker = marker;
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