{% extends 'layouts/main.volt' %}

{% block title %}{{ app.name }}{% endblock %}

{% block header %}{% endblock %}

{% block content %}
	
    {{ form( 'admin/users/' ~  object.id  ~'/update', 'class' : 'form-horizontal mb-5') }}
    <div class="row">
	    <div class="col-sm-8">
	   		
	   		
	        <div class="form-group">
            	<label>{{ lang.get('auth.login.name_label') }}</label>
	            {{ form.render('name', ['class': 'form-control']) }}
	        </div>
	        
	        <div class="form-group">
	        	{% if config.app.auth.usernames == true %}
            	<label>{{ lang.get('auth.login.username_label') }}</label>
	            {{ form.render('username', ['class': 'form-control']) }}
	            {% else %}
	            <label>{{ lang.get('auth.login.username_label') }}</label>
	            {{ form.render('username', ['class': 'form-control', 'disabled' : 'disabled']) }}
	            {% endif %}
	        </div>
	        

	        <div class="form-group">
            	<label>{{ lang.get('auth.login.email_label') }}</label>
	            {{ form.render('email', ['class': 'form-control']) }}
	        </div>
		 	
		 	<div class="form-group">
            	<label>{{ lang.get('auth.login.status_label') }}</label>
	            {{ form.render('status', ['class': 'form-control']) }}
	        </div>

	        <div class="form-group">
            	<label>{{ lang.get('auth.login.activated_label') }}</label>
	            {{ form.render('activated', ['class': 'form-control']) }}
	        </div>
	 		 
            <label>{{ lang.get('auth.login.role_label') }}</label>
            {{ form.render('roles[]', ['class': 'select2-select form-control', 'multiple' : true , 'value' : user_roles]) }}

		    {{ form.render('csrf', ['value': this.security.getToken()]) }}
		    <p><small>Registered at: {{ object.created_at }}</small></p>
	 		
	 		

	 		<button class="btn btn-success">UPDATE</button>
	 		<a href="{{ '/admin/users/' ~  object.id  ~ '/edit/password' }}" class="btn btn-secondary ">Change password</a>
	 		{{ endform() }}
	 	 	   		
		</div>
	 	
	 	<div class="col-md-4">
           	{% include "partials/uploader" with [ 'object' : object , 'objectid' :  object.id   , 'objectype' : 'user' ] %}
	    </div>
		</div>

		{% include "partials/meta_forms"  with ['metas' :avaibleMetas ] %}
 
{% endblock %}

{% block footer %}
<script type="text/javascript"></script>
{% endblock %}