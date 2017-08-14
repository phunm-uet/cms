@extends('bases::layouts.master')
@section('content')
    @include('bases::elements.tables.datatables', ['title' => trans('block::block.list'), 'dataTable' => $dataTable])
@stop