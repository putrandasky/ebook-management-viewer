@extends('layouts.app')

@section('meta')
<title>Reader App</title>

{{-- @include('include.meta') --}}
@endsection

@section('content')
<div id="app">

    {{-- <example-component></example-component> --}}
</div>
@endsection

@section('script')
  @include('scripts.reader')
@endsection
