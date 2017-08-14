<div class="col-md-4">
    <div class="list-group config-item">
        <a href="{{ route('backups.list') }}" class="list-group-item">
            <i class="icon-refresh"></i>
            <h4 class="list-group-item-heading">{{ trans('backup::backup.name') }}</h4>
            <p class="list-group-item-text">{{ trans('backup::backup.backup_description') }}</p>
        </a>
    </div>
</div>