{% extends 'admin/layouts/main.volt' %}

{% block title %}{{ app.name }}{% endblock %}

{% block header %}{% endblock %}

{% block content %}
	
    {{ form( 'admin/' ~   controller | lower ~   '/store', 'class' : 'form-horizontal') }}
    <div class="row mb-5">
	    <div class="col-sm-8">
	   
        <div class="form-group">
            <strong>Name</strong>
            {{ form.render('name', ['class': 'form-control slug-in', 'autocomplete' : 'off']) }}
        </div>

        <div class="form-group">
            <strong>Slug</strong>
            {{ form.render('slug', ['class': 'form-control slug-out', 'autocomplete' : 'off'  ]) }}
            <span class="help">Alphanumeric characters and "-" only</span>
        </div>
	 	
	 	<div class="form-group">
            <strong>Description</strong>
            {{ form.render('description', ['class': 'form-control']) }}
        </div>

        {% if objectType['hierachical']  %}
        <div class="form-group">
        	 <strong>Parent</strong>
        	{{ select(  'parent'  , objects , 'using' : [  'term_id',  'name'  ],  'useEmpty': true ,  'class': 'select2-select form-control'  ) }}
        </div>
        {% endif %}

 		{{ form.render('taxonomy', ['value': objectType['taxonomy']]) }}

	    {{ form.render('csrf', ['value': this.security.getToken()]) }}

		</div>
	 	
	 	<div class="col-sm-4">
		 	<div class="card bg-light">
		 		<div class="card-body">
				 	<div class="d-flex justify-content-between">
					 	<button class="btn btn-primary mr-1" style="width:100%;" name="savePost" value="1">SAVE</button>
 					 </div>

				 	<hr>
				 	 
	        	</div>
        	</div> 
        </div>
		</div>
    {{ endform() }}
    
 

{% endblock %}

{% block footer %}
<script type="text/javascript"></script>
{% endblock %}