<div class="tree " id="tree-{{term['name']}}">
{% for single_term in terms %}
 	{% if single_term.countChildren() %}
		<div class="form-check">
			<input type="{{ (term['multiple']) ?  'checkbox' : 'radio' }}" name="term_{{ single_term.taxonomy }}[]" value="{{ single_term.term_id}}" {%if  in_array(single_term.term_id ,post_terms) %} checked {% endif %}  class="form-check-input"> 
			<label class="form-check-label">{{ single_term.getName()}}</label>
		 	{% include "partials/term_tree" with ['terms' : single_term.getChildren() ] %}
		</div>
	{% else  %}
		<div class="form-check">
			<input type="{{ (term['multiple']) ?  'checkbox' : 'radio' }}" name="term_{{ single_term.taxonomy }}[]" value="{{ single_term.term_id}}"  {%if  in_array(single_term.term_id ,post_terms) %} checked {% endif %} class="form-check-input"> 
			<label class="form-check-label">{{ single_term.getName()}}</label> 
		</div>
	{% endif %}

	 
  {% endfor %}

</div>