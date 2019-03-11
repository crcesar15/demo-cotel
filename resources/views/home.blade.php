@extends('layouts.app')

@push('styles')
    <style>
        /* Set the size of the div element that contains the map */
        #map {
            height: 450px;  /* The height is 400 pixels */
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
        <h1 class="text-center pt-3">DEMO</h1>
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
        $(document).ready(function () {
            enable_map(true);
        });
    </script>
@endpush