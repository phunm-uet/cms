@extends('bases::layouts.master')
@section('content')
    @include('bases::elements.tables.datatables', ['title' => trans('custom-field::custom-field.custom_field_name'), 'dataTable' => $dataTable])
@stop