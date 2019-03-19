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
                            <h5 class="card-title text-center text-white"><i class="fa fa-search"></i> Busqueda</h5>
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
                                            </select>
                                        </div>
                                    </div>
                                    <br>
                                    <br>
                                    <div class="custom-control custom-checkbox mr-sm-2">
                                        <input type="checkbox" class="custom-control-input" value="1" name="terminal" id="terminal">
                                        <label class="custom-control-label pl-4 pt-1" for="terminal">Terminales</label>
                                    </div>
                                    <div class="custom-control custom-checkbox mr-sm-2">
                                        <input type="checkbox" class="custom-control-input" name="tab" value="1" id="tab">
                                        <label class="custom-control-label pl-4 pt-1" for="tab">Tabs</label>
                                    </div>
                                    <br>
                                    <br>
                                    <div class="col-12 col-md-2">
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
        let tabMarkers = [];
        let terminalMarkers = [];

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

        $('form#search').submit(async (event) => {
            event.preventDefault();

            let data;
            let check_flag = false;

            data =  $('input[type = checkbox]');

            for (i = 0; i < data.length ; i++){
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
                console.log(data_to_markers);
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
                    terminal: $('input#terminal').is(':checked'),
                    tab: $('input#tab').is(':checked')
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
                    reject(0);
                });
            });
        };

        const set_places = () => {
            const defaultBounds = new google.maps.LatLngBounds(
                new google.maps.LatLng(-16.506336, -68.143524), //down
                new google.maps.LatLng(-16.482357, -68.121815) //up
            );

            const options = {
                bounds: defaultBounds,
                types: []
            };

            let from_places = new google.maps.places.Autocomplete(document.getElementById('from_places'),options);
            from_places.setOptions({strictBounds: true});

            google.maps.event.addListener(from_places, 'place_changed',function(){
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

        const addCustomMarker = (location, map, device, connections, busy) => {
            let contentString = '';
            let device_type = [];

            device_type['Here'] = '{{asset('img/here.png')}}';
            device_type['Terminal'] = '{{asset('img/tv.png')}}';
            device_type['Tab'] = '{{asset('img/phone.png')}}';

            let image = {
                url: device_type[device],
                labelOrigin: new google.maps.Point(15,35)
            };

            if (device === 'Here' ){ device = "Referencia"}

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

            if (connections){
                let infowindow = new google.maps.InfoWindow();

                marker.addListener('click', () => {
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
                    infowindow.open(map, marker);
                });
            }else{
                if (ant_marker != null) {
                    ant_marker.setMap(null);
                }
            }
            return marker;
        };
    </script>
@endpush