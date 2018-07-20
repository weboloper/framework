<ul class="tree " id="tree-{{term['name']}}">
{% for single_term in terms %}

	{% if single_term.countChildren() %}
		<li><input type="{{ (term['multiple']) ?  'checkbox' : 'radio' }}" name="term_{{ single_term.taxonomy }}[]" value="{{ single_term.term_id}}" {%if  in_array(single_term.term_id ,post_terms) %} checked {% endif %}> {{ single_term.getName()}}
		 {% include "admin/partials/term_tree" with ['terms' : single_term.getChildren() ] %}
		</li>
	{% else  %}
		<li><input type="{{ (term['multiple']) ?  'checkbox' : 'radio' }}" name="term_{{ single_term.taxonomy }}[]" value="{{ single_term.term_id}}"  {%if  in_array(single_term.term_id ,post_terms) %} checked {% endif %}> {{ single_term.getName()}} </li>
	{% endif %}

 {% endfor %}
</ul>