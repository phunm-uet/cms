@extends('bases::layouts.master')
@section('content')
    {!! Breadcrumbs::render('pageTitle', trans('blog::categories.list'), Route::currentRouteName()) !!}
    @include('bases::elements.tables.datatables', ['title' => trans('blog::categories.list'), 'dataTable' => $dataTable])
@stop