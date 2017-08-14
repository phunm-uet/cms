@extends('bases::layouts.master')
@section('content')
    @include('bases::elements.tables.datatables', ['title' => trans('menu::menu.name'), 'dataTable' => $dataTable])
@stop