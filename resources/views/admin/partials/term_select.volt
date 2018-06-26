<div class="card">
	<div class="card-header bg-light">{{ term['name']}}</div>
    <div class="card-body">
    	{% set classes = (term['multiple']) ?  'tagging-true' : 'tagging-false'  %}
    	{% if term['multiple'] == true %}
    		{{ select(   term['taxonomy']  , terms , 'using' : [  'term_id',  'name'  ],  'useEmpty': false ,  'class': 'select2-select form-control ' ~ classes , 'multiple' : true  ) }}
    	{% else %}
    		{{ select(   term['taxonomy']  , terms , 'using' : [  'term_id',  'name'  ],  'useEmpty': true ,  'class': 'select2-select form-control ' ~ classes  ) }}
    	{% endif %}
    </div>
</div>
 