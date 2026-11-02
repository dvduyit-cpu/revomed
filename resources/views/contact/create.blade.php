@extends('layouts.app')
@section('title', ($locale === 'en' ? 'Contact' : 'Liên hệ').' | '.($site['site_title'] ?? 'REVOMED'))
@section('content')
@include('partials.contact-layout')
@endsection
