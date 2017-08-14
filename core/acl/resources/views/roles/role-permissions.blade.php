<ul id='auto-checkboxes' data-name='foo' class="list-unstyled list-feature">
    <li id="mainNode">
        <input type="checkbox">&nbsp;&nbsp;
        <span class="label label-default allTree" onClick="return expandCollapseTree('mainNode');">{{ trans('acl::permissions.all') }}</span>
        <ul>
            @foreach($children[0] as $element)
                <li class="collapsed" id="node{{ $flags[$element]->id }}">
                    <input type="checkbox" id="checkSelect{{ $flags[$element]->id }}" name="flags[]" value="{{ $flags[$element]->id }}" @if(in_array($flags[$element]->flag, $active)) checked="checked" @endif>
                    <span class="label label-warning" style="margin: 5px;" onClick="return expandCollapseTree('node{{ $flags[$element]->id }}');">{{ $flags[$element]->name }}</span>
                    @if(isset($children[$element]))
                        <ul>
                            @foreach($children[$element] as $subElements)
                                @if(in_array($flags[$subElements]->name, ['Create', 'Edit', 'Delete']))
                                    <span class="createEditDeleteGroup">
                                        <input type="checkbox" id="checkSelect{{ $flags[$subElements]->id }}" name="flags[]" value="{{ $flags[$subElements]->id }}" @if(in_array($flags[$subElements]->flag, $active)) checked="checked" @endif>
                                        <span class="label label-info nameMargin">{{ $flags[$subElements]->name }}</span>
                                    </span>
                                @else
                                    <li class="collapsed" id="node{{ $flags[$subElements]->id }}">
                                        <input type="checkbox" id="checkSelect{{ $flags[$subElements]->id }}" name="flags[]" value="{{ $flags[$subElements]->id }}" @if(in_array($flags[$subElements]->flag, $active)) checked="checked" @endif>
                                        <span class="label label-primary nameMargin" onClick='return expandCollapseTree("node{{ $flags[$subElements]->id }}");'>{{ $flags[$subElements]->name }}</span>
                                        @if (isset($children[$subElements]))
                                            <ul>
                                                @foreach($children[$subElements] as $subSubElements)
                                                    @if(in_array($flags[$subSubElements]->name, ['Create', 'Edit', 'Delete']))
                                                        <span class="createEditDeleteGroup">
                                                            <input type="checkbox" id="checkSelect{{ $flags[$subSubElements]->id }}" name="flags[]" value="{{ $flags[$subSubElements]->id }}" @if(in_array($flags[$subSubElements]->flag, $active)) checked="checked" @endif>
                                                            <span class="label label-info nameMargin">{{ $flags[$subSubElements]->name }}</span>
                                                        </span>
                                                    @else
                                                        <li class="collapsed" id="node{{ $flags[$subSubElements]->id }}">
                                                            <input type="checkbox" id="checkSelect{{ $flags[$subSubElements]->id }}" name="flags[]" value="{{ $flags[$subSubElements]->id }}" @if(in_array($flags[$subSubElements]->flag, $active)) checked="checked" @endif>
                                                            <span class="label label-success nameMargin" onClick='return expandCollapseTree("node{{ $flags[$subSubElements]->id }}");'>{{ $flags[$subSubElements]->name }}</span>
                                                            @if(isset($children[$subSubElements]))
                                                                <ul>
                                                                    @foreach($children[$subSubElements] as $grandChildrenElements)
                                                                        @if(in_array($flags[$grandChildrenElements]->name, ['Create', 'Edit', 'Delete']))
                                                                            <span class="createEditDeleteGroup">
                                                                                <input type="checkbox" id="checkSelect{{ $flags[$grandChildrenElements]->id }}" name="flags[]" value="{{ $flags[$grandChildrenElements]->id }}" @if(in_array($flags[$subSubElements]->flag, $active)) checked="checked" @endif>
                                                                                 <span class="label label-info nameMargin">{{ $flags[$grandChildrenElements]->name }}</span>
                                                                            </span>
                                                                        @else
                                                                            <li class="collapsed" id="node{{ $flags[$grandChildrenElements]->id }}">
                                                                                <input type="checkbox" id="checkSelect{{ $flags[$grandChildrenElements]->id }}" name="flags[]" value="{{ $flags[$grandChildrenElements]->id }}" @if(in_array($flags[$grandChildrenElements]->flag, $active)) checked="checked" @endif>
                                                                                <span class="label label-danger nameMargin" onClick='return expandCollapseTree("node{{ $flags[$grandChildrenElements]->id }}");'>{{ $flags[$grandChildrenElements]->name }}</span>
                                                                                @if(isset($children[$grandChildrenElements]))
                                                                                    <ul>
                                                                                        @foreach($children[$grandChildrenElements] as $greatGrandChildrenElements)
                                                                                            @if(in_array($flags[$greatGrandChildrenElements]->name, ['Create', 'Edit', 'Delete']))
                                                                                                <span class="createEditDeleteGroup">
                                                                                                    <input type="checkbox" id="checkSelect{{ $flags[$greatGrandChildrenElements]->id }}" name="flags[]" value="{{ $flags[$grandChildrenElements]->id }}" @if(in_array($flags[$subSubElements]->flag, $active)) checked="checked" @endif>
                                                                                                    <span class="label label-info nameMargin">{{ $flags[$greatGrandChildrenElements]->name }}</span>
                                                                                                </span>
                                                                                            @else
                                                                                                <li class="collapsed" id="node{{ $flags[$grandChildrenElements]->id }}">
                                                                                                    <input type="checkbox" id="checkSelect{{ $flags[$grandChildrenElements]->id }}" name="flags[]" value="{{ $flags[$grandChildrenElements]->id }}" @if(in_array($flags[$grandChildrenElements]->flag, $active)) checked="checked" @endif>
                                                                                                    <span class="label label-info nameMargin" onClick='return expandCollapseTree("node{{ $flags[$grandChildrenElements]->id }}");'>{{ $flags[$grandChildrenElements]->name }}</span>
                                                                                                </li>
                                                                                            @endif
                                                                                        @endforeach
                                                                                    </ul>
                                                                                @endif
                                                                            </li>
                                                                        @endif
                                                                    @endforeach
                                                                </ul>
                                                            @endif
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
    </li>
</ul>
