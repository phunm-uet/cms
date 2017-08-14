<ul id='auto-checkboxes' data-name='foo' class="list-unstyled list-feature">
    <li id="mainNode">
        <input type="checkbox">&nbsp;&nbsp; <span class="label label-default allTree" onClick="return expandCollapseTree('mainNode');">{{ trans('acl::feature.all') }}</span>
        <ul>
            @foreach($featuresWithChildren[0] as $element)
                <li class="collapsed" id="node{{ $features[$element]->id }}">
                    <input type="checkbox" id="checkSelect{{ $features[$element]->id }}" name="features[]" value="{{ $features[$element]->id }}" @if (in_array($features[$element]->id, $active)) checked="checked" @endif>
                    <span class="label label-warning" style="margin:5px;" onClick="return expandCollapseTree('node{{ $features[$element]->id }}');">{{ $features[$element]->name }}</span>
                    @if (isset($featuresWithChildren[$element]))
                        <ul>
                            @foreach($featuresWithChildren[$element] as $subElements)
                                @if (in_array($features[$subElements]->name, ['Create','Edit','Delete']))
                                    <span class="createEditDeleteGroup">
                                        <input type="checkbox" id="checkSelect{{ $features[$subElements]->id }}" name="features[]" value="{{ $features[$subElements]->id }}" @if (in_array($features[$subElements]->id, $active))checked="checked" @endif>
                                        <span class="label label-info nameMargin">{{ $features[$subElements]->name }}</span>
                                    </span>
                                @else
                                    <li class="collapsed" id="node{{ $features[$subElements]->id }}">
                                        <input type="checkbox" id="checkSelect{{ $features[$subElements]->id }}" name="features[]" value="{{ $features[$subElements]->id }}" @if (in_array($features[$subElements]->id, $active))checked="checked" @endif>
                                        <span class="label label-primary nameMargin" onClick='return expandCollapseTree("node{{ $features[$subElements]->id }}");'>{{ $features[$subElements]->name }}</span>
                                        @if (isset($featuresWithChildren[$subElements]))
                                            <ul>
                                                @foreach($featuresWithChildren[$subElements] as $subSubElements)
                                                    @if (in_array($features[$subSubElements]->name, ['Create','Edit','Delete']))
                                                        <span class="createEditDeleteGroup">
                                                            <input type="checkbox" id="checkSelect{{ $features[$subSubElements]->id }}" name="features[]" value="{{ $features[$subSubElements]->id }}" @if (in_array($features[$subSubElements]->id, $active))checked="checked" @endif>
                                                            <span class="label label-info nameMargin">{{ $features[$subSubElements]->name }}</span>
                                                        </span>
                                                    @else
                                                        <li class="collapsed" id="node{{ $features[$subSubElements]->id }}">
                                                            <input type="checkbox" id="checkSelect{{ $features[$subSubElements]->id }}" name="features[]" value="{{ $features[$subSubElements]->id }}" @if (in_array($features[$subSubElements]->id, $active))checked="checked" @endif>
                                                            <span class="label label-success nameMargin" onClick='return expandCollapseTree("node{{ $features[$subSubElements]->id }}");'>{{ $features[$subSubElements]->name }}</span>
                                                            @if (isset($featuresWithChildren[$subSubElements]))
                                                                <ul>
                                                                    @foreach($featuresWithChildren[$subSubElements] as $grandChildrenElements)
                                                                        @if (in_array($features[$grandChildrenElements]->name, ['Create','Edit','Delete']))
                                                                            <span class="createEditDeleteGroup"><input type="checkbox" id="checkSelect{{ $features[$grandChildrenElements]->id }}" name="features[]" value="{{ $features[$grandChildrenElements]->id }}" @if (in_array($features[$subSubElements]->id, $active))checked="checked" @endif>
                                                                                <span class="label label-info nameMargin">{{ $features[$grandChildrenElements]->name }}</span>
                                                                            </span>
                                                                        @else
                                                                            <li class="collapsed" id="node{{ $features[$grandChildrenElements]->id }}">
                                                                                <input type="checkbox" id="checkSelect{{ $features[$grandChildrenElements]->id }}" name="features[]" value="{{ $features[$grandChildrenElements]->id }}" @if (in_array($features[$grandChildrenElements]->id, $active))checked="checked" @endif>
                                                                                <span class="label label-danger nameMargin" onClick='return expandCollapseTree("node{{ $features[$grandChildrenElements]->id }}");'>{{ $features[$grandChildrenElements]->name }}</span>
                                                                                @if (isset($featuresWithChildren[$grandChildrenElements]))
                                                                                    <ul>
                                                                                        @foreach($featuresWithChildren[$grandChildrenElements] as $greatGrandChildrenElements)
                                                                                            @if (in_array($features[$greatGrandChildrenElements]->name,array('Create','Edit','Delete')))
                                                                                                <span class="createEditDeleteGroup"><input type="checkbox" id="checkSelect{{ $features[$greatGrandChildrenElements]->id }}" name="features[]" value="{{ $features[$grandChildrenElements]->id }}" @if (in_array($features[$subSubElements]->id, $active))checked="checked" @endif>
                                                                                                    <span class="label label-info nameMargin">{{ $features[$greatGrandChildrenElements]->name }}</span>
                                                                                                </span>
                                                                                            @else
                                                                                                <li class="collapsed" id="node{{ $features[$grandChildrenElements]->id }}">
                                                                                                    <input type="checkbox" id="checkSelect{{ $features[$grandChildrenElements]->id }}" name="features[]" value="{{ $features[$grandChildrenElements]->id }}" @if (in_array($features[$grandChildrenElements]->id, $active))checked="checked" @endif>
                                                                                                    <span class="label label-info nameMargin" onClick='return expandCollapseTree("node{{ $features[$grandChildrenElements]->id }}");'>{{ $features[$grandChildrenElements]->name }}</span>
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

<div id="list_feature" data-features="{{ json_encode($features, JSON_HEX_APOS) }}"></div>
