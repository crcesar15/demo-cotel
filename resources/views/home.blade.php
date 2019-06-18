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
    <script src="https://maps.googleapis.com/maps/api/js?v=3.35&key=AIzaSyDYfXp15LPz_VuK75gqcgNicuwK1H-1BOw"></script>
    <script src="{{asset('js/maps.js')}}"></script>
    <script>
        $(document).ready(async function () {
            let height = ($( document ).height() - 50);
            $('#map').css('height',height);

            enable_map(false);
            let position = {};
            let datos;
            datos = await get_devices();
            for (var i = 0; i < datos.length; i++){
                data = datos[i];
                for(var j = 0; j < data.length; j++){
                    position = {
                        lat: parseFloat(data[j].lat),
                        lng: parseFloat(data[j].lng)
                    };
                    addCustomMarker(position, data[j], i);
                }
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
        }
    </script>
@endpush