<ul {!! $options !!}>
    @foreach ($object as $key => $row)
        <li>
            {!! Form::checkbox('menu_id', $row->id, null, ['class' => 'styled', 'id' => 'menu_id_' . $row->id]) !!}
            <label for="menu_id_{{ $row->id }}" data-title="{{ $row->name }}" data-related-id="{{ $row->id }}"
                   data-type="{{ $model->getTable() }}">{{ $row->name }}</label>
            {!!
                Menu::generateSelect([
                    'model' => $model,
                    'parent_id' => $row->id
                ])
            !!}
        </li>
    @endforeach
</ul>
