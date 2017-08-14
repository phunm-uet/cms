<!-- Share Modal -->
<div id="share_modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header sub-modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><i class="til_img"></i><strong>{{ trans('media::media.manage_file_sharing') }}</strong></h4>
            </div>

            <div class="modal-body with-padding">
                <div id="modalExistingShares">
                    <div class="table-responsive" style="max-height: 200px; overflow-y:scroll;">
                        <table class="table table-striped" id="modalShareTable">
                            <thead class="active">
                            <tr>
                                <th>{{ trans('media::media.shared_type') }}</th>
                                <th>{{ trans('media::media.shared_with') }}</th>
                                <th>{{ trans('media::media.date_shared') }}</th>
                                <th>{{ trans('media::media.options') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td colspan="4">{{ trans('media::media.load_exists_share') }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <hr>
                <div class="form-group">
                    <div class="row">
                        <label class="col-sm-4 control-label text-right" for="selectMember">{{ trans('media::media.share_with_users') }}</label>
                        <div class="col-sm-8">
                            <select id="selectMember" name="shareWithUsers" class="select-multiple" multiple="multiple">
                                @foreach (\Botble\ACL\Models\User::where('id', '!=', Sentinel::getUser()->id)->get() as $user)
                                    <option value="{{ $user->id }}">{{ $user->getFullName() }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="pull-left btn btn-primary" data-dismiss="modal">{{ trans('media::media.close') }}</button>
                <a class="pull-right btn btn-success" id="share-confirm-bttn">{{ trans('media::media.add_share') }}</a>
            </div>
        </div>
    </div>
</div>
<!-- End Share Modal -->
