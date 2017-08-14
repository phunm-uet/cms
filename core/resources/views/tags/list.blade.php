@extends('bases::layouts.master')
@section('content')
    {!! Breadcrumbs::render('pageTitle', trans('blog::tags.list'), Route::currentRouteName()) !!}
    @include('bases::elements.tables.datatables', ['title' => trans('blog::tags.list'), 'dataTable' => $dataTable])
@stop