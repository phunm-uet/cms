<div class="modal fade media-modal media_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog"
     aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="til_img"></i><strong>{{ trans('media::media.gallery') }}</strong></h4>
            </div>
            <div class="modal-body media-modal-body" id="medialibrary-myfiles">
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
    var BMedia = BMedia || {};

    BMedia.routes = {
        'files_show': '{{ route('files.gallery.show') }}',
        'files_store': '{{ route('files.store') }}',
        'files_quota_refresh': '{{ route('files.quota.refresh') }}',
        'files_destroy': '{{ route('files.destroy') }}',
        'folders_create': '{{ route('folders.create') }}',
        'folders_delete': '{{ route('folders.delete') }}',
        'share_item': '{{ route('item.share') }}',
        'share_show': '{{ route('files.shared.show') }}',
        'share_list': '{{ route('shares.list') }}',
        'share_remove': '{{ route('share.remove') }}',
        'files_rename': '{{ route('files.rename') }}',
        'folders_rename': '{{ route('folders.rename') }}',
    };
</script>

@include('media::partials.modal')
@include('media::partials.share-modal')
