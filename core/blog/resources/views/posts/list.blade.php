@extends('bases::layouts.master')
@section('content')
    @include('bases::elements.tables.datatables', ['title' => trans('blog::posts.list'), 'dataTable' => $dataTable, 'icon' => 'fa fa-edit'])
@stop