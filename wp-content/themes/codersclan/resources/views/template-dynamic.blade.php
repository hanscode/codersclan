{{--
  Template Name: Dynamic Template
--}}

@extends('layouts.app')

@section('content')
  @while(have_posts()) @php(the_post())
    @include('partials.sections')
  @endwhile
@endsection
