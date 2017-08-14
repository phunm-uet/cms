@extends('bases::layouts.master')
@section('content')
    @include('bases::elements.tables.datatables', ['title' => trans('pages::pages.list'), 'dataTable' => $dataTable, 'icon' => 'fa fa-book'])
@stop