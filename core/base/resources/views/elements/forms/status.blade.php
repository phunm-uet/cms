<div class="widget meta-boxes">
    <div class="widget-title">
        <h4><span class="required">{{ trans('bases::tables.status') }}</span></h4>
    </div>
    <div class="widget-body">
        {!! Form::select('status', isset($values) ? $values : [1 => trans('bases::system.activated'), 0 => trans('bases::system.disabled')], isset($selected) ? $selected : old('status', 1), ['class' => 'form-control']) !!}
    </div>
</div>