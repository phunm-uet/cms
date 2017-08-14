@extends('bases::layouts.master')
@section('content')
    @include('bases::elements.tables.datatables', ['title' => trans('contact::contact.list'), 'dataTable' => $dataTable, 'icon' => 'fa fa-envelope-o'])
@stop