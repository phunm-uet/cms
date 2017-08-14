@if ($status)
    <span class="label-success status-label">@if (!empty($activated_text)) {{ $activated_text }} @else {{ trans('bases::tables.activated') }} @endif</span>
@else
    <span class="label-danger status-label">@if (!empty($deactivated_text)) {{ $deactivated_text }} @else {{ trans('bases::tables.deactivated') }} @endif</span>
@endif