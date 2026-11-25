@extends('layouts.app')

@section('content')
    @include('sections.hero')
    @include('sections.catalog')
    @include('sections.assistant')
    @include('sections.bento')
    @include('sections.interpreter')
    @include('sections.resources')
    @include('sections.contact')
    @include('partials.demo-fab')
@endsection
