@extends('layouts.guest')

@section('title', 'Laravel Moris')

@section('body')
    <x-site.ticker />

    <x-site.header />

    <main>
        @include('partials.home.hero')
        @include('partials.home.happening-now')
        @include('partials.home.meetups')
        @include('partials.home.speakers')
        @include('partials.home.community')
        @include('partials.home.sponsors')
    </main>

    <x-site.footer />
@endsection
