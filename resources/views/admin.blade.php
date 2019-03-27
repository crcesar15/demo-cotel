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
        <h1 class="text-center pt-3">DEMO Cotel - Bayoex S.R.L.</h1>
        <div class="container-fluid pt-0">
            <div id="map"></div>
        </div>
    </div>
    @include('partials.footer')
@stop

@push('scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDYfXp15LPz_VuK75gqcgNicuwK1H-1BOw"></script>
    <script src="{{asset('js/maps.js')}}"></script>
    <script>
        $(document).ready(async function () {
            let height = ($( document ).height() - 50);
            $('#map').css('height',height);

            enable_map(false);
            let position = {};
            let datos;
            datos = await get_devices();
            for (var i = 0; i < datos.length ; i++){
                position = {
                    lat: parseFloat(datos[i].lat),
                    lng: parseFloat(datos[i].lng)
                };
                addCustomMarker(position,map,datos[i].device_type.name,parseInt(datos[i].connections),parseInt(datos[i].busy),datos[i].id);
            }
        });

        let get_devices = function(){
            return new Promise(function(response, reject){
                $.ajax({
                    url: '{{route('devices')}}',
                    type: 'GET',
                    dataType: 'JSON',
                })
                .done(function(msg) {
                    response(msg);
                })
                .fail(function() {
                    response(0);
                });
            });
        };

        function addCustomMarker(location, map, device, connections, busy,id) {
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
                    text: device+id,
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
                    <h4 id="firstHeading" class="firstHeading">Disponibilidad</h4>
                    <div id="bodyContent">
                    <p><b>Conexiones: </b>${connections}<br>
                    <b>Ocupadas: </b>${busy}<br>
                    <b>Libres: </b>${connections-busy}</p>
                    </div>
                    </div>`;
                infowindow.setContent(contentString);
                if (infowindow.getMap()){
                    infowindow.close();
                }else{
                    infowindow.open(map, marker);
                }
            });
        }
    </script>
@endpush