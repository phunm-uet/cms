<script type="text/javascript">

    var Botble = Botble || {};

    Botble.variables = {
        youtube_api_key: '{{ env('YOUTUBE_DATA_API_KEY') }}'
    };

    Botble.routes = {
        home: '{{ url('/') }}',
        change_plugin_status: '{{ route('plugins.change.status') }}'
    };

    Botble.languages = {
        'tables': {
            'filter': '{{ trans('bases::tables.filter') }}',
            'activated': '{{ trans('bases::tables.activated') }}',
            'deactivated': '{{ trans('bases::tables.deactivated') }}',
            'excel': '{{ trans('bases::tables.excel') }}',
            'export': '{{ trans('bases::tables.export') }}',
            'csv': '{{ trans('bases::tables.csv') }}',
            'pdf': '{{ trans('bases::tables.pdf') }}',
            'print': '{{ trans('bases::tables.print') }}',
            'reset': '{{ trans('bases::tables.reset') }}',
            'reload': '{{ trans('bases::tables.reload') }}'
        },
        'notices_msg': {
            'success': '{{ trans('bases::layouts.success') }}!',
            'error': '{{ trans('bases::layouts.error') }}!'
        },
        'pagination': {
            'previous': '{{ trans('pagination.previous') }}',
            'next': '{{ trans('pagination.next') }}'
        },
        'media': {
            'processing': '{{ trans('media::media.processing') }}',
            'not_valid_youtube_link': '{{ trans('media::media.not_valid_youtube_link') }}',
            'env_not_config': '{{ trans('media::media.env_not_config') }}'
        },
        'system': {
            'character_remain': '{{ trans('bases::forms.character_remain') }}'
        }
    };

</script>

@if (session()->has('success_msg') || session()->has('error_msg') || isset($errors) || isset($error_msg))
    <script type="text/javascript">
        $(document).ready(function () {

            @if (session()->has('success_msg'))
                Botble.showNotice('success', '{{ session('success_msg') }}', Botble.languages.notices_msg.success);
            @endif
            @if (session()->has('error_msg'))
                Botble.showNotice('error', '{{ session('error_msg') }}', Botble.languages.notices_msg.error);
            @endif
            @if (isset($error_msg))
                Botble.showNotice('error', '{{ $error_msg }}', Botble.languages.notices_msg.error);
            @endif
            @if (isset($errors))
                @foreach ($errors->all() as $error)
                   Botble.showNotice('error', '{{ $error }}', Botble.languages.notices_msg.error);
                @endforeach
            @endif
        });
    </script>
@endif

{!! Form::modalAction('delete-crud-modal', trans('bases::tables.confirm_delete'), 'danger',  trans('bases::tables.confirm_delete_msg'), 'delete-crud-entry', trans('bases::tables.delete')) !!}
{!! Form::modalAction('delete-many-modal', trans('bases::tables.confirm_delete'), 'danger',  trans('bases::tables.confirm_delete_msg'), 'delete-many-entry', trans('bases::tables.delete')) !!}
