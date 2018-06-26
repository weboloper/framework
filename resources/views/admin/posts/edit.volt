{% extends 'admin/layouts/main.volt' %}

{% block title %}{{ app.name }}{% endblock %}

{% block header %}{% endblock %}

{% block content %}
	
    {{ form( 'admin/' ~   controller | lower ~  '/' ~  object.id  ~ '/update', 'class' : 'form-horizontal') }}
    <div class="row mb-5">
	    <div class="col-sm-8">
	   
        <div class="form-group">
            <strong>Title</strong>
            {{ form.render('title', ['class': 'form-control slug-in', 'autocomplete' : 'off']) }}
        </div>

        <div class="form-group">
          <strong>Slug</strong>
           {{ form.render('slug', ['class': 'form-control slug-out' , 'autocomplete' : 'off']) }}
           <span class="help">Alphanumeric characters and "-" only</span>
        </div>
	 	
	 	<div class="form-group">
            <strong>Content</strong>
            {{ form.render('body', ['class': 'form-control']) }}
        </div>

        {% if objectType['excerpt'] %}
        <div class="form-group">
            <strong>Excerpt</strong>
            {{ form.render('excerpt', ['class': 'form-control']) }}
        </div>
        {% endif %}
 
	    {{ form.render('csrf', ['value': this.security.getToken()]) }}
 
		 </div>
		 <div class="col-sm-4">
		 	<div class="card bg-light">
		 		<div class="card-body">
				 	<div class="d-flex justify-content-between">
					 	<button class="btn btn-primary mr-1" style="width:100%;" name="savePost" value="1">SAVE</button>
					 	<button class="btn btn-success" style="width:100%">PUBLISH</button>
					 </div>

				 	<hr>
				 	<div class="form-group">
			            <strong>Status</strong>
			            {{ form.render('status', ['class': 'form-control']) }}
			        </div>

				 	<ul class="list-group mt-4">
		                <li class="list-group-item list-group-item-light">
		                    <i class="fas fa-calendar"></i> Created At:
		                    {{ object.created_at }}
		                </li>
		                <li class="list-group-item list-group-item-light">
		                    <i class="fas fa-calendar"></i> Updated At
		                    {{ object.updated_at }}
		                </li>
		         
		            </ul>
	        	</div>
        	</div> 
  			{% for term in objectType['terms'] %}
  				{% if termTypes[term]['hierachical']  %}
  					<div class="card">
						<div class="card-header bg-light">{{ termTypes[term]['name']}}</div>
    						<div class="card-body">
  								{% include "admin/partials/term_tree"  with ['term' : termTypes[term] , 'terms' : terms_array[term] ] %}
  							</div>
  						</div>
  				{% else  %}
  					{% include "admin/partials/term_select"  with ['term' : termTypes[term] , 'terms' : terms_array[term] ] %}
  				{% endif  %}
 					
 			{% endfor %}
     
  		 </div>
		</div>
    {{ endform() }}
    
    {% if objectType['metas'] %}
    	{% include "admin/partials/meta_forms.volt" %}
    {% endif %}
    

{% endblock %}

{% block footer %}
<script type="text/javascript"></script>
{% endblock %}