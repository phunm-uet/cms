<?php

namespace Botble\Base\Http\DataTables;

use Yajra\Datatables\Services\DataTable;

abstract class DataTableAbstract extends DataTable
{

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     * @author Sang Nguyen
     * @since 2.1
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->parameters([
                'dom' => "Brt<'datatables__info_wrap'plfi<'clearfix'>>",
                'buttons' => $this->getBuilderParameters(),
                'initComplete' => 'function () {$(\'.checkboxes\').uniform(); $(\'.dataTables_wrapper\').css({\'width\': $(\'.dataTable\').width()});
                    var index = 0;
                    var totalLength = this.api().columns().count();
                    var self = this;
                    this.api().columns().every(function () {
                        var column = this;
                        
                        index++;
                        if (index == totalLength) {
                            var searchBtn = document.createElement("a");
                            $(searchBtn).addClass("btn btn-info btn-sm btn-search-table tip").attr("data-original-title", "Search").appendTo($(column.footer())).html("<i class=\'fa fa-search\'></i>");
                            var clearBtn = document.createElement("a");
                            $(clearBtn).addClass("btn btn-warning btn-sm btn-reset-table tip").attr("data-original-title", "Clear search").appendTo($(column.footer())).html("<i class=\'fa fa-times\'></i>");
                        } else {
                            if ($(column.footer()).hasClass("searchable")) {
                                var input = document.createElement("input");
                                $(input).addClass("form-control input-sm");
                                //if ($(column.footer()).hasClass("datepicker")) {
                                //    $(column.footer()).removeClass("datepicker");
                                //    $(input).addClass("datepicker");
                                //}
                                
                                var placeholder = "Search...";
                                if ($(column.footer()).hasClass("searchable_id")) {
                                    placeholder = "...";
                                }
                                $(input).prop("type", "text").css("width", "100%").prop("placeholder", placeholder).appendTo($(column.footer()).empty())
                                .on("change", function () {
                                    column.search($(this).val()).draw();
                                });
                            }
                        }
                    });
                }',
                'drawCallback' => 'function () {
                    $(".checkboxes").uniform();
                    
                    //$("#dataTableBuilder tfoot tr").prependTo($("#dataTableBuilder tbody"));
                    //$("#dataTableBuilder tfoot").remove();
                    
                    $(document).on("click", ".btn-search-table", function () {
                        $("#dataTableBuilder tfoot tr input").trigger("change");
                    });
                    
                    $(".tip").tooltip({placement: "top"});
                }',
                'paging' => true,
                'searching' => true,
                'info' => true,
                'searchDelay' => 350,
                'bStateSave' => true,
                'lengthMenu' => [
                    [10, 30, 50, -1],
                    [10, 30, 50, trans('bases::tables.all')]
                ],
                'pageLength' => 10,
                'processing' => true,
                'serverSide' => true,
                'bServerSide' => true,
                'bDeferRender' => true,
                'bProcessing' => true,
                'language' => [
                    'aria' => [
                        'sortAscending' => 'orderby asc',
                        'sortDescending' => 'orderby desc'
                    ],
                    'emptyTable' => trans('bases::tables.no_data'),
                    'info' => '<span class="dt-length-records"><i class="fa fa-globe"></i> <span class="hidden-xs">' . trans('bases::tables.show_from') . '</span> _START_ ' . trans('bases::tables.to') . ' _END_ ' . trans('bases::tables.in') . ' <span class="badge bold badge-dt">_TOTAL_</span> <span class="hidden-xs">' . trans('bases::tables.records') . '</span></span>',
                    'infoEmpty' => trans('bases::tables.no_record'),
                    'infoFiltered' => '(' . trans('bases::tables.filtered_from') . ' _MAX_ ' . trans('bases::tables.records') . ')',
                    'lengthMenu' => '<span class="dt-length-style">_MENU_</span>',
                    'search' => '',
                    'zeroRecords' => trans('bases::tables.no_record'),
                    'processing' => '<img src="' . url('/vendor/core/images/loading-spinner-blue.gif') . '" />',
                ],
            ]);
    }

    /**
     * @return array
     * @author Sang Nguyen
     * @since 2.1
     */
    protected function getBuilderParameters()
    {
        return [
            'stateSave' => true,
            'buttons' => [
                $this->getButtons(),
                [
                    'extend' => 'collection',
                    'text' => '<span>' . trans('bases::forms.actions') . ' <span class="caret"></span></span>',
                    'buttons' => $this->getActions()
                ],
                'export',
                'print',
                'reset',
                'reload',
            ],
        ];
    }

    /**
     * @return mixed
     * @author Sang Nguyen
     * @since 2.1
     */
    abstract function columns();

    /**
     * @return mixed
     * @author Sang Nguyen
     * @since 2.1
     */
    abstract function buttons();

    /**
     * @return mixed
     * @author Sang Nguyen
     * @since 2.1
     */
    abstract function actions();

    /**
     * @return array
     * @author Sang Nguyen
     * @since 2.1
     */
    private function getButtons() {
        $buttons = [];
        foreach ($this->buttons() as $key => $button) {
            if (array_get($button, 'extend') == 'collection') {
                $buttons[] = $button;
            } else {
                $buttons[] = [
                    'className' => 'action-item',
                    'text' => '<span data-action="' . $key . '" data-href="' . $button['link'] . '"> ' . $button['text'] . '</span>',
                ];
            }
        }
        return $buttons;
    }

    /**
     * @return array
     * @author Sang Nguyen
     * @since 2.1
     */
    private function getActions()
    {
        $actions = [];
        foreach ($this->actions() as $key => $action) {
            $actions[] = [
                'className' => 'action-item',
                'text' => '<span data-action="' . $key . '" data-href="' . $action['link'] . '"> ' . $action['text'] . '</span>',
            ];
        }
        return $actions;
    }

    /**
     * Get columns.
     *
     * @return array
     * @author Sang Nguyen
     * @since 2.1
     */
    private function getColumns()
    {
        $headings = [
            'checkbox' => [
                'width' => '10px',
                'class' => 'text-left no-sort',
                'title' => '<div class="checkbox checkbox-primary"><input type="checkbox" class="group-checkable" data-set=".dataTable .checkboxes" /></div>',
                'orderable' => false,
                'searchable' => false,
                'exportable' => false,
                'printable' => false,
            ],
        ];

        $headings = array_merge($headings, $this->columns());

        $extra = apply_filters(BASE_FILTER_TABLE_HEADINGS, $headings, $this->filename());

        return array_merge($extra, [
            'operations' => [
                'title' => trans('bases::tables.operations'),
                'width' => '134px',
                'class' => 'text-center',
                'orderable' => false,
                'searchable' => false,
                'exportable' => false,
                'printable' => false,
            ]
        ]);
    }
}
