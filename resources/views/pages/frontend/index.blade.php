@extends('pages.frontend.parent')

@section('title', 'Home')

@section('content')
    @include('pages.frontend.components.main-banner')
    @include('pages.frontend.components.carrousel')
@endsection
