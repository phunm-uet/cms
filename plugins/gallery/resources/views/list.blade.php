@extends('bases::layouts.master')
@section('content')
    @include('bases::elements.tables.datatables', ['title' => trans('gallery::gallery.list'), 'dataTable' => $dataTable, 'icon' => 'fa fa-photo'])
@stop