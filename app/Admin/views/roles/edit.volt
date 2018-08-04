{% extends 'layouts/main.volt' %}

{% block title %}{{ app.name }}{% endblock %}

{% block header %}{% endblock %}

{% block content %}
	
    {% if object is defined %}
        {{ form( 'admin/' ~   controller | lower ~  '/' ~  object.id  ~ '/update', 'class' : 'form-horizontal ') }}
     {% else %}
         {{ form( 'admin/' ~   controller | lower ~  '/store', 'class' : 'form-horizontal ') }}
     {% endif %}


    <div class="row mb-5">
	    <div class="col-sm-8">
	   
        <div class="form-group">
            <strong>Name</strong>
            {{ form.render('name', ['class': 'form-control slug-in', 'autocomplete' : 'off']) }}
        </div>
 
	 	
	 	<div class="form-group">
            <strong>Description</strong>
            {{ form.render('description', ['class': 'form-control']) }}
        </div>

     
	    {{ form.render('csrf', ['value': this.security.getToken()]) }}

		</div>
	 	
	 	<div class="col-sm-4">

            <div class="card mb-2">
                <div class="card-header bg-light">Save</div>
                    <div class="card-body">                 
                                 
                        <button class="btn btn-secondary mr-1" value="1">SAVE</button>

                    </div>
                </div>
 
        </div>
		</div>
    {{ endform() }}
    
 

{% endblock %}

{% block footer %}
<script type="text/javascript"></script>
{% endblock %}