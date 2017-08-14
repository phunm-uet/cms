@extends('bases::layouts.master')
@section('content')
    @include('bases::elements.tables.datatables', ['title' => trans('rss::rss.list'), 'dataTable' => $dataTable])
@stop