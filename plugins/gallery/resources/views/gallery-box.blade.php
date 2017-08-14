{!! Form::hidden('gallery', $value ? json_encode($value) : null, ['id' => 'gallery', 'class' => 'form-control']) !!}
<div>
    <ul class="list-photos-gallery">
        @if (!empty($value))
            @foreach ($value as $key => $item)
                <li data-id="{{ $key }}"><img src="{{ url(array_get($item, 'img')) }}" alt="{{ trans('gallery::gallery.item') }}"></li>
            @endforeach
        @endif
    </ul>
    <div class="clearfix"></div>
    <div>
        <a class="btn_gallery" data-mode="attach" data-result="gallery"
           data-action="gallery_image" data-backdrop="static" data-keyboard="false"
           data-toggle="modal"
           data-target=".media_modal">{{ trans('gallery::gallery.select_image') }}</a>
        <a href="#" class="text-danger reset-gallery @if (empty($value)) hidden @endif">{{ trans('gallery::gallery.reset') }}</a>
    </div>
</div>

<div id="edit-gallery-item" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><i class="til_img"></i><strong>{{ trans('gallery::gallery.update_photo_description') }}</strong></h4>
            </div>

            <div class="modal-body with-padding">
                <p><input type="text" class="form-control" id="gallery-item-description" placeholder="{{ trans('gallery::gallery.update_photo_description_placeholder') }}"></p>
            </div>

            <div class="modal-footer">
                <button class="pull-left btn btn-danger" id="delete-gallery-item" href="#">{{ trans('gallery::gallery.delete_photo') }}</button>
                <button class="pull-right btn btn-default" data-dismiss="modal">{{ trans('bases::forms.cancel') }}</button>
                <button class="pull-right btn btn-primary" id="update-gallery-item">{{ trans('bases::forms.update') }}</button>
            </div>
        </div>
    </div>
</div>
<!-- end Modal -->
