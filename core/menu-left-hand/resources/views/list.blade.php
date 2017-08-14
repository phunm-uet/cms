@extends('bases::layouts.master')

@section('content')
    <div class="main-form">
        {!! Form::open(['id' => 'menuLeftHandForm', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
        {!! Form::hidden('items', null, ['id' => 'items']) !!}
        {!! Form::close() !!}

        <div id="menu-left-hand-administration" data-json='{{ $current }}' data-defaults-json='{{ $default }}'>
            <div role="toolbar" aria-label="Options" class="action-groups">
                <span class="action-group action-group-left">
                    <a href="{{ route('system.menu.left-hand.item.create') }}" class="btn btn-primary btn_addnew">
                        {{ trans('menu-left-hand::menu_left_hand.create') }}
                    </a>
                </span>

                <span class="spacer"> </span>

                <span class="action-group action-group-center">
                    <button type="button" class="action-expand-all btn btn-default">
                        {{ trans('menu-left-hand::menu_left_hand.expand_all') }}
                    </button>
                    <button type="button" class="action-collapse-all btn btn-default">
                        {{ trans('menu-left-hand::menu_left_hand.collapse_all') }}
                    </button>
                    <div class="btn-group">
                        <button
                            type="button"
                            data-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false"
                            class="btn btn-default dropdown-toggle"
                        >{{ trans('menu-left-hand::menu_left_hand.more_options') }}
                            <span aria-hidden="true" class="icon icon-arrow-down2"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="action-reset-defaults">{{ trans('menu-left-hand::menu_left_hand.reset_default') }}</a></li>
                            <li><a class="action-reset-saved">{{ trans('menu-left-hand::menu_left_hand.reset_last_save') }}</a></li>
                        </ul>
                    </div>
                </span>

                <span class="spacer"> </span>

                <span class="action-group action-group-right">
                    <a class="action-cancel-tree btn btn-wide btn-default" href="{{ route('system.options') }}">
                        {{ trans('menu-left-hand::menu_left_hand.cancel') }}
                    </a>
                    <button type="button" class="action-save-tree btn btn-wide btn-primary"
                            @if (session()->get('created'))disabled="disabled" @endif>
                        {{ trans('menu-left-hand::menu_left_hand.save') }}
                    </button>
                </span>
            </div>

            <div class="tree"></div>

            <div role="toolbar" aria-label="Options" class="action-groups">
                <span class="spacer"> </span>

                <span class="action-group action-group-right">
                    <a class="action-cancel-tree btn btn-wide btn-default" href="{{ route('system.options') }}">
                        {{ trans('menu-left-hand::menu_left_hand.cancel') }}
                    </a>
                    <button type="button" class="action-save-tree btn btn-wide btn-primary"
                            @if (session()->get('created'))disabled="disabled" @endif>
                        {{ trans('menu-left-hand::menu_left_hand.save') }}
                    </button>
                </span>
            </div>

            <div class="modal fade modal-for-edit">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title"><i class="til_img"></i><strong><span
                                        class="modal-text-edit">{{ trans('menu-left-hand::menu_left_hand.edit') }}</span></strong>
                            </h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-horizontal">
                                <div class="form-group item-defaults-group">
                                    <label class="control-label col-sm-4">
                                        {{ trans('menu-left-hand::menu_left_hand.default_name') }}
                                    </label>

                                    <div class="col-sm-5">
                                        <p class="form-control-static">
                                            <span class="item-preview">
                                                <span aria-hidden="true" class="icon item-default-icon"></span>
                                                <span class="name item-default-name"></span>
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="item-name" class="control-label col-sm-4">
                                        {{ trans('menu-left-hand::menu_left_hand.new_name') }}
                                    </label>

                                    <div class="col-sm-5">
                                        <input type="text" name="item-name" class="form-control item-name" maxlength="120">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="item-icon">{{ trans('menu-left-hand::menu_left_hand.icon') }}</label>
                                    <div class="col-sm-5">
                                        <input type="text" name="item-icon" id="item-icon" placeholder ="{{ trans('bases::forms.icon_placeholder') }}" class="form-control item-icon" maxlength="30">
                                        <div class="help-ts">{!! trans('menu-left-hand::menu_left_hand.icon_helper') !!}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" data-dismiss="modal" class="action-delete btn btn-danger pull-left">
                                {{ trans('menu-left-hand::menu_left_hand.delete_btn') }}
                            </button>
                            <button type="button" data-dismiss="modal" class="btn btn-wide btn-default">
                                {{ trans('menu-left-hand::menu_left_hand.cancel') }}
                            </button>
                            <button type="button" class="action-modal-save btn btn-wide btn-primary">
                                <span
                                    class="modal-text-edit">{{ trans('menu-left-hand::menu_left_hand.update_btn') }}</span>
                                <span
                                    class="modal-text-new">{{ trans('menu-left-hand::menu_left_hand.create_btn') }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade modal-for-delete">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" data-dismiss="modal" aria-label="close" class="close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h4 class="modal-title"><i
                                    class="til_img"></i><strong>{{ trans('menu-left-hand::menu_left_hand.delete_btn') }}</strong>
                            </h4>
                        </div>
                        <div class="modal-body">
                            <p>{{ trans('menu-left-hand::menu_left_hand.confirm_delete_line') }}</p>

                            <p class="item-preview">
                                <span aria-hidden="true" class="icon"></span>
                                <span class="name"></span>
                            </p>?
                            <p>{{ trans('menu-left-hand::menu_left_hand.confirm_delete_content') }}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" data-dismiss="modal" class="action-modal-cancel btn btn-wide btn-default">
                                {{ trans('menu-left-hand::menu_left_hand.no') }}
                            </button>
                            <button type="button" class="action-modal-delete btn btn-wide btn-danger">{{ trans('menu-left-hand::menu_left_hand.yes') }}</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade modal-for-reset-defaults">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" data-dismiss="modal" aria-label="close" class="close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"><i class="til_img"></i><strong>{{ trans('menu-left-hand::menu_left_hand.reset_menu') }}</strong>
                            </h4>
                        </div>
                        <div class="modal-body">
                            <p>{{ trans('menu-left-hand::menu_left_hand.confirm_reset_default') }}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" data-dismiss="modal"
                                    class="action-modal-cancel btn btn-wide btn-default">
                                {{ trans('menu-left-hand::menu_left_hand.no') }}
                            </button>
                            <button type="button" class="action-modal-reset-defaults btn btn-wide btn-danger">
                                {{ trans('menu-left-hand::menu_left_hand.yes') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade modal-for-reset-saved">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" data-dismiss="modal" aria-label="close" class="close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h4 class="modal-title"><i class="til_img"></i><strong>{{ trans('menu-left-hand::menu_left_hand.reset_menu') }}</strong>
                            </h4>
                        </div>
                        <div class="modal-body">
                            <p>{{ trans('menu-left-hand::menu_left_hand.confirm_reset_last_save') }}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" data-dismiss="modal"
                                    class="action-modal-cancel btn btn-wide btn-default">
                                {{ trans('menu-left-hand::menu_left_hand.no') }}
                            </button>
                            <button type="button" class="action-modal-reset-saved btn btn-wide btn-danger">
                                {{ trans('menu-left-hand::menu_left_hand.yes') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <script type="text/x-kendo-template" class="item-template">
                <div class="item item-#= item.kind #">
                    <span aria-hidden="true" class="#= item.iconClass #"></span>
                    <span class="name">#= item.displayName #</span>#
                    if (item.kind === "category") {
                    #<span class="item-count">#= item.items.length #</span>#
                    }#
                </div>
                <button type="button"
                        class="action-edit btn btn-default">{{ trans('menu-left-hand::menu_left_hand.edit') }}</button>
            </script>

            <script type="text/x-kendo-template" class="drag-clue-template">
                <div class="k-header k-drag-clue">
                    <span class="k-icon k-drag-status"></span>
                    #= data.item.displayName #
                </div>
            </script>

            <script type="text/x-kendo-template" class="template-icon">
                <li data-name="#= data.name #">
                    #= data.item.iconClass #
                </li>
            </script>
        </div>
    </div>
@stop
