@extends('bases::layouts.master')
@section('content')
    @include('bases::elements.tables.datatables', ['title' => trans('acl::permissions.list_role'), 'dataTable' => $dataTable])
@stop