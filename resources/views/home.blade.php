@extends('layouts.app')

@section('navbar')
    @include('partials.navbar')
@stop

@section('sidebar')
    @include('partials.sidebar')
@stop

@section('content')
    <div class="page-wrapper">
        <h1 class="text-center"> DEMO</h1>
    </div>
    @include('partials.footer')
@stop