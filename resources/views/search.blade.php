@extends('layouts.app')

@push('styles')
    <link href="{{asset('css/toastr.min.css')}}" rel="stylesheet">
    <style>
        /* Set the size of the div element that contains the map */
        #map {  /* The height is 400 pixels */
            width: 100%;  /* The width is the width of the web page */
            border: solid 2px black;
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
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h5 class="card-title text-center text-white"><i class="fa fa-search"></i> Búsqueda</h5>
                        </div>
                        <div class="card-body">
                            <h6 class="card-title">Selecciones los parametros de busqueda </h6>
                            <form id="search" action="{{route('get_devices_near')}}" method="POST">
                                @csrf
                                <div class="form-group row">
                                    <div class="col-12 col-md-4">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">Dirección</span>
                                            <input class="form-control" type="text" id="from_places" name="from_places" required placeholder="Escriba una direccion o elija un punto en el mapa">
                                            <input type="hidden" required name="origin" id="origin">
                                        </div>
                                    </div>
                                    <br>
                                    <br>
                                    <div class="col-12 col-md-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">Radio de Busqueda</span>
                                            <select class="select2 form-control custom-select" id="radio" required style="width: 100%; height:36px;">
                                                <option value="">Seleccione una opción</option>
                                                <option value="50">50 mts.</option>
                                                <option value="100">100 mts.</option>
                                                <option value="200">200 mts.</option>
                                                <option value="500">500 mts.</option>
                                                <option value="1000">1 Km.</option>
                                            </select>
                                        </div>
                                    </div>
                                    <br>
                                    <br>
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
                                <div class="row">
                                    <br>
                                    <br>
                                    <div class="col-12 offset-5 col-md-2">
                                        <button type="submit" class="btn btn-success btn-block">
                                            <i class="fa fa-search"> Buscar</i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div id="map"></div>
                </div>
            </div>
        </div>
    </div>
    @include('partials.footer')
@stop

@push('scripts')
    <script src="{{asset('js/libs/toastr/build/toastr.min.js')}}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDYfXp15LPz_VuK75gqcgNicuwK1H-1BOw&libraries=places"></script>
    <script src="{{asset('js/maps.js')}}"></script>
    <script>
        $(document).ready(() => {
            let height = ($( document ).height() - 50);
            $('#map').css('height',height);
            enable_map(true);
            set_places();
        });

        $('input#from_places').focus(() => {
           const data = $('input#from_places').val();
           if (data === 'Selección en el Mapa'){
               $('input#from_places').val('');
            }
        });

        $('input#from_places').change(() => {
            if (searchMarkers.length){
                for(let i = 0; i < searchMarkers.length; i++){
                    searchMarkers[i].setMap(null);
                }
                searchMarkers = [];
            }
        });

        $('form#search').submit(async (event) => {
            event.preventDefault();
            let data;
            let marker;
            let line;
            let points;
            let check_flag = false;

            data =  $('input[type = checkbox]');

            for (let i = 0; i < data.length ; i++){
                if (data[i].checked){
                    check_flag = true;
                }
            }

            if (!selected_position){
                toastr.info('Ingrese una dirección Valida.', 'La dirección seleccionada no es valida, consulte la documentación');
            }

            if (!check_flag){
                toastr.info('Seleccione al menos una capa.', 'Es necesario una capa de busqueda');
            }

            if(selected_position && check_flag){
                let data_to_markers = await get_devices_near();
                if (data_to_markers[0].length || data_to_markers[1].length || data_to_markers[2].length || data_to_markers[3].length || data_to_markers[4].length ){
                    delete_search_lines();
                    delete_search_markers();
                    for (var i = 0; i < data_to_markers.length; i++){
                        data = data_to_markers[i];
                        for(var j = 0; j < data.length; j++){
                            position = {
                                lat: parseFloat(data[j].lat),
                                lng: parseFloat(data[j].lng)
                            };
                            marker = addCustomMarker(position, data[j], i);
                            points = [selected_position, {lat:parseFloat(data[j].lat),lng:parseFloat(data[j].lng)}];
                            line = addLine(points, map,'#514d8e');
                            get_data_two_points(points);
                            searchLines.push(line);
                            searchMarkers.push(marker);
                        }
                    }
                }else{
                    delete_search_lines();
                    delete_search_markers();
                    toastr.info('No se encontraron coincidencias', 'La búsqueda bajo los parámetros establecidos no generó ninguna coincidencia, intente nuevamente');
                }
            }
        });

        const get_devices_near = () => {
            return new Promise((response, reject) => {
                let url = '{{url('get_devices_near')}}';
                let data = {
                    _token: '{{csrf_token()}}',
                    lat: selected_position.lat,
                    lng: selected_position.lng,
                    radio: $('select#radio').val(),
                    armario: ($('input#armario').is(':checked')?1:0),
                    terminal: ($('input#terminal').is(':checked')?1:0),
                    camara: ($('input#camara').is(':checked')?1:0),
                    poste: ($('input#poste').is(':checked')?1:0),
                    tap: ($('input#tap').is(':checked')?1:0)
                };
                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'JSON',
                    data: data
                })
                .done((data) => {
                    response(data);
                })
                .fail(() => {
                    reject([]);
                });
            });
        };

        const set_places = () => {
            const defaultBounds = new google.maps.LatLngBounds(
                new google.maps.LatLng(-16.509262, -68.143087), //down-16.491157,-16.509262
                new google.maps.LatLng(-16.491157, -68.123174) //up(-68.123174,-68.143087),
            );

            const options = {
                bounds: defaultBounds,
                types: []
            };

            let from_places = new google.maps.places.Autocomplete(document.getElementById('from_places'),options);
            from_places.setOptions({strictBounds: true});

            google.maps.event.addListener(from_places, 'place_changed',function(){
                delete_search_markers();
                delete_search_lines()
                let from_place = from_places.getPlace();
                let from_address = from_place.formatted_address;
                selected_position = {
                    lat: from_place.geometry.location.lat(),
                    lng: from_place.geometry.location.lng()
                };
                map.setCenter(selected_position);
                ant_marker = addCustomMarker(selected_position, map, 'Here',0,0);
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