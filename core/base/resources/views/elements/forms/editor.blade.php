<a class="btn_gallery" data-mode="attach" data-result="content" data-action="image_post"
   data-backdrop="static" data-keyboard="false" data-toggle="modal"
   data-target=".media_modal">{{ trans('media::media.add') }}</a>
{!! Form::textarea($name, $value, ['class' => 'form-control editor-' . config('cms.editor.primary'), 'id' => $name]) !!}