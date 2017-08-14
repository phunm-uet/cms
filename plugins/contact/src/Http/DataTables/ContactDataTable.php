<?php

namespace Botble\Contact\Http\DataTables;

use Botble\Base\Http\DataTables\DataTableAbstract;
use Botble\Contact\Repositories\Interfaces\ContactInterface;

class ContactDataTable extends DataTableAbstract
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
                return anchor_link(route('contacts.edit', $item->id), $item->name);
            })
            ->editColumn('checkbox', function ($item) {
                return table_checkbox($item->id);
            })
            ->editColumn('created_at', function ($item) {
                return date_from_database($item->created_at, 'd-m-Y');
            })
            ->editColumn('is_read', function ($item) {
                return table_status($item->is_read, trans('contact::contact.read'), trans('contact::contact.unread'));
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, CONTACT_MODULE_SCREEN_NAME)
            ->addColumn('operations', function ($item) {
                return table_actions('contacts.edit', 'contacts.delete', $item);
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
        return $this->applyScopes(app(ContactInterface::class)->select(['id', 'name', 'phone', 'email', 'created_at', 'is_read']));
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
                'class' => 'searchable searchable_id',
            ],
            'name' => [
                'title' => trans('bases::tables.name'),
                'class' => 'text-left searchable',
            ],
            'phone' => [
                'title' => trans('contact::contact.tables.phone'),
                'class' => 'searchable',
            ],
            'email' => [
                'title' => trans('contact::contact.tables.email'),
                'class' => 'searchable',
            ],
            'created_at' => [
                'title' => trans('bases::tables.created_at'),
                'width' => '100px',
                'class' => 'searchable',
            ],
            'is_read' => [
                'title' => trans('contact::contact.form.is_read'),
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
        $buttons = [];
        return apply_filters(BASE_FILTER_DATATABLES_BUTTONS, $buttons, CONTACT_MODULE_SCREEN_NAME);
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
                'link' => route('contacts.delete.many'),
                'text' => view('bases::elements.tables.actions.delete')->render()
            ],
            'activate' => [
                'link' => route('contacts.change.status', ['status' => 1]),
                'text' => view('contact::partials.actions.mark-read')->render()
            ],
            'deactivate' => [
                'link' => route('contacts.change.status', ['status' => 0]),
                'text' => view('contact::partials.actions.mark-unread')->render()
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
        return CONTACT_MODULE_SCREEN_NAME;
    }
}
