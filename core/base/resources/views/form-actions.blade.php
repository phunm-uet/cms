<div class="widget meta-boxes form-actions form-actions-default action-{{ $direction or 'horizontal' }}">
    <div class="widget-title">
        <h4>
            @if (isset($icon) && !empty($icon))
                <i class="{{ $icon }}"></i>
            @endif
            <span>{{ apply_filters(BASE_ACTION_FORM_ACTIONS_TITLE, trans('bases::forms.publish')) }}</span>
        </h4>
    </div>
    <div class="widget-body">
        <div class="btn-set">
            @php do_action(BASE_ACTION_FORM_ACTIONS, 'default') @endphp
            <button type="submit" name="submit" value="save" class="btn btn-info">
                <i class="fa fa-save"></i> {{ trans('bases::forms.save') }}
            </button>
            <button type="submit" name="submit" value="apply" class="btn btn-success">
                <i class="fa fa-check-circle"></i> {{ trans('bases::forms.save_and_continue') }}
            </button>
        </div>
    </div>
</div>
<div id="waypoint"></div>
<div class="form-actions form-actions-fixed-top hidden">
    <div class="btn-set">
        @php do_action(BASE_ACTION_FORM_ACTIONS, 'fixed-top') @endphp
        <button type="submit" name="submit" value="save" class="btn btn-info">
            <i class="fa fa-save"></i> {{ trans('bases::forms.save') }}
        </button>
        <button type="submit" name="submit" value="apply" class="btn btn-success">
            <i class="fa fa-check-circle"></i> {{ trans('bases::forms.save_and_continue') }}
        </button>
    </div>
</div>
