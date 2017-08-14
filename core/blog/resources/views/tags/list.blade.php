@extends('bases::layouts.master')
@section('content')
    @include('bases::elements.tables.datatables', ['title' => trans('blog::tags.list'), 'dataTable' => $dataTable])
@stop