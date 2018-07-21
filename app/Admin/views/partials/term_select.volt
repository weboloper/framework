<div class="card">
	<div class="card-header bg-light">{{ term['name']}}</div>
    <div class="card-body">
     	{% set classes = (term['tagging']) ?  'tagging-true' : 'tagging-false'  %}
    	{% if term['multiple'] == true %}
    		{{ select(   "term_" ~ term['taxonomy'] ~ "[]" , terms , 'using' : [  'term_id',  'name'  ],  'useEmpty': false ,  'class': 'select2-select form-control ' ~ classes , 'multiple' : term['multiple']  , 'value' : post_terms  ) }}
    	{% else %}
    		{{ select(  "term_" ~ term['taxonomy'] ~ "[]"  , terms , 'using' : [  'term_id',  'name'  ],  'useEmpty': true ,  'class': 'select2-select form-control ' ~ classes , 'value' : post_terms  ) }}
    	{% endif %}
    </div>
</div>
 