<?php

namespace Botble\Rss\Http\DataTables;

use Botble\Base\Http\DataTables\DataTableAbstract;
use Botble\Rss\Repositories\Interfaces\RssInterface;

class RssDataTable extends DataTableAbstract
{
    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     * @author Sang Nguyen
     * @since 2.1
     */
    public function ajax()
    {
        $data = $this->datatables
            ->eloquent($this->query())
            ->editColumn('name', function ($item) {
                return anchor_link(route('rss.edit', $item->id), $item->name);
            })
            ->editColumn('checkbox', function ($item) {
                return table_checkbox($item->id);
            })
            ->editColumn('created_at', function ($item) {
                return date_from_database($item->created_at, 'd-m-Y');
            })
            ->editColumn('status', function ($item) {
                return table_status($item->status);
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, RSS_MODULE_SCREEN_NAME)
            ->addColumn('operations', function ($item) {
                return table_actions('rss.edit', 'rss.delete', $item);
            })
            ->escapeColumns([])
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     * @author Sang Nguyen
     * @since 2.1
     */
    public function query()
    {
       $model = app(RssInterface::class)->getModel();
       $query = $model->select(['id', 'name', 'created_at', 'status']);
       return $this->applyScopes(apply_filters(BASE_FILTER_DATATABLES_QUERY, $query, $model, RSS_MODULE_SCREEN_NAME));
    }

    /**
     * @return array
     * @author Sang Nguyen
     * @since 2.1
     */
    public function columns()
    {
        return [
            'id' => [
                'title' => trans('bases::tables.id'),
                'width' => '20px',
            ],
            'name' => [
                'title' => trans('bases::tables.name'),
            ],
            'created_at' => [
                'title' => trans('bases::tables.created_at'),
                'width' => '100px',
            ],
            'status' => [
                'title' => trans('bases::tables.status'),
                'width' => '100px',
            ],
        ];
    }

    /**
     * @return array
     * @author Sang Nguyen
     * @since 2.1
     */
    public function buttons()
    {
        $buttons = [
            'create' => [
                'link' => route('rss.create'),
                'text' => view('bases::elements.tables.actions.create')->render()
            ],
        ];
        return apply_filters(BASE_FILTER_DATATABLES_BUTTONS, $buttons, RSS_MODULE_SCREEN_NAME);
    }

    /**
     * @return array
     * @author Sang Nguyen
     * @since 2.1
     */
    public function actions()
    {
        return [
            'delete' => [
                'link' => route('rss.delete.many'),
                'text' => view('bases::elements.tables.actions.delete')->render()
            ],
            'activate' => [
                'link' => route('rss.change.status', ['status' => 1]),
                'text' => view('bases::elements.tables.actions.activate')->render()
            ],
            'deactivate' => [
                'link' => route('rss.change.status', ['status' => 0]),
                'text' => view('bases::elements.tables.actions.deactivate')->render()
            ]
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     * @author Sang Nguyen
     * @since 2.1
     */
    protected function filename()
    {
        return RSS_MODULE_SCREEN_NAME;
    }
}
