<!-- Delete Modal -->
<div id="file_delete_modal" class="modal sub_modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header sub-modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><i class="til_img"></i><strong>{{ trans('media::media.confirm_delete_title') }}</strong></h4>
            </div>

            <div class="modal-body with-padding">
                <p>{!! trans('media::media.confirm_delete_content') !!}</p>
            </div>

            <div class="modal-footer">
                <a class="pull-left btn btn-danger" id="delete-file-confirm-bttn">{{ trans('media::media.yes_delete') }}</a>
                <button class="pull-right btn btn-primary" data-dismiss="modal">{{ trans('media::media.cancel') }}</button>
            </div>
        </div>
    </div>
</div>

<div id="folder_delete_modal" class="modal sub_modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header sub-modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><i class="til_img"></i><strong>{{ trans('media::media.confirm_delete_title') }}</strong></h4>
            </div>

            <div class="modal-body with-padding">
                <p>{!! trans('media::media.confirm_delete_folder_content') !!}</p>
            </div>

            <div class="modal-footer">
                <a class="pull-left btn btn-danger" id="delete-folder-confirm-bttn">{{ trans('media::media.yes_delete') }}</a>
                <button class="pull-right btn btn-primary" data-dismiss="modal">{{ trans('media::media.cancel') }}</button>
            </div>
        </div>
    </div>
</div>
<!-- End Delete Modal -->

<!-- Detail Modal -->
<div id="file_detail_modal" class="modal sub_modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header sub-modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><i
                        class="til_img"></i><strong>{{ trans('media::media.file_information') }}</strong></h4>
            </div>

            <div class="modal-body with-padding">
                <p>{{ trans('media::media.cannot_fetch_file_information') }}</p>
            </div>
        </div>
    </div>
</div>
<!-- End Detail Modal -->

<!-- UnShare Modal -->
<div id="file_unshare_modal" class="modal sub_modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><i class="til_img"></i><strong>{{ trans('media::media.confirm_share') }}</strong></h4>
            </div>

            <div class="modal-body with-padding">
                <p>{{ trans('media::media.confirm_unshare_content') }}</p>
            </div>

            <div class="modal-footer">
                <a class="pull-left btn btn-danger" id="unshare-file-confirm-bttn">{{ trans('media::media.yes_unshare') }}</a>
                <button class="pull-right btn btn-primary" data-dismiss="modal">{{ trans('media::media.cancel') }}</button>
            </div>
        </div>
    </div>
</div>
<!-- End UnShare Modal -->

<!-- Youtube modal -->
<div id="youtube_modal" class="modal sub_modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: #4C5D68">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="til_img"></i><strong>{{ trans('media::media.add_youtube_video') }}</strong></h4>
            </div>

            <div class="modal-body with-padding" id="add_modal">
                <div class="alert alert-warning fade in block-inner">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <i class="icon icon-warning"></i> {{ trans('media::media.youtube_notice') }}
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <input type="text" id="youtube_url" class="form-control"/>
                    </div>
                    <div class="col-md-4">
                        <button type="button" id="process_vid" class="btn btn-primary" value="{{ trans('media::media.check_video') }}">{{ trans('media::media.check_video') }}</button>
                    </div>
                </div>
                <div id="youtube_url_process"><p>{{ trans('media::media.waiting_url') }}</p></div>
            </div>
        </div>
    </div>
</div>
<!-- /Youtube modal -->

<div class="modal sub_modal fade" id="modal-file-upload">
    <div class="modal-dialog">
        <div class="modal-content modal-md">
            <form method="POST" action="{{ route('files.store') }}"
                  class="form-horizontal" enctype="multipart/form-data">
                <div class="modal-body" style="padding: 0; border: none">
                    <!-- Multiple file uploader with header -->
                    <div class="block">
                        <div class="plupload with-header">{{ trans('media::media.no_support_upload') }}</div>
                    </div>
                    <!-- /multiple file uploader with header -->
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal sub_modal fade" id="modal_new_folder">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header sub-modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title"><i class="til_img"></i><strong>{{ trans('media::media.new_folder') }}</strong>
                </h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>{{ trans('media::media.folder_name') }} </label>
                    <input type="text" class="form-control" name="name" id="folder_name">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" id="create_folder" class="btn btn-primary">{{ trans('media::media.apply') }}</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('media::media.cancel') }}</button>
            </div>
        </div>
    </div>
</div>


<!-- Detail Modal -->
<div id="edit_name_modal" class="modal sub_modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header sub-modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><i class="til_img"></i><strong>{{ trans('media::media.rename') }}</strong></h4>
            </div>
            <div class="modal-body with-padding">
                <div class="form-group">
                    <input type="text" class="form-control" id="new_name" placeholder="{{ trans('media::media.new_name_placeholder') }}">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" id="edit_name_btn" class="btn btn-primary">{{ trans('media::media.apply') }}</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('media::media.cancel') }}</button>
            </div>
        </div>
    </div>
</div>
<!-- End Detail Modal -->
