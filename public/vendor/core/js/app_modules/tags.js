var route=$("div[data-tag-route]").data("tag-route"),tags=new Bloodhound({datumTokenizer:Bloodhound.tokenizers.obj.whitespace("name"),queryTokenizer:Bloodhound.tokenizers.whitespace,prefetch:{url:route,filter:function(e){return $.map(e,function(e){return{name:e}})}}});tags.initialize(),$("#tags").tagsinput({typeaheadjs:{name:"tags",displayKey:"name",valueKey:"name",source:tags.ttAdapter()}});