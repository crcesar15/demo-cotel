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
        <h1 class="text-center pt-3">DEMO</h1>
        <div class="container-fluid pt-0">
            <div id="example"></div>
        </div>
    </div>
    @include('partials.footer')
@stop

@push('scripts')
@endpush