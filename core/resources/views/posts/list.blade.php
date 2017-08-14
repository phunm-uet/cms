@extends('bases::layouts.master')
@section('content')
    {!! Breadcrumbs::render('pageTitle', trans('blog::posts.list'), Route::currentRouteName()) !!}
    @include('bases::elements.tables.datatables', ['title' => trans('blog::posts.list'), 'dataTable' => $dataTable, 'icon' => 'fa fa-edit'])
@stop