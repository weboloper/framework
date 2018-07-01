{% extends 'admin/layouts/main.volt' %}

{% block title %}{{ app.name }}{% endblock %}

{% block header %}{% endblock %}

{% block content %}
	
    {{ form( 'admin/users/' ~  object.id  ~'/update', 'class' : 'form-horizontal') }}
    <div class="row">
	    <div class="col-sm-8">
	   
	        <div class="form-group">
	            <strong>Name</strong>
	            {{ form.render('name', ['class': 'form-control']) }}
	        </div>

	        <div class="form-group">
	            <strong>Email</strong>
	            {{ form.render('email', ['class': 'form-control',  'disabled' : 'disabled']) }}
	        </div>
		 	
		 	<div class="form-group">
	            <strong>Status</strong>
	            {{ form.render('status', ['class': 'form-control']) }}
	        </div>

	 		 
            <strong>Roles</strong>
            {{ form.render('roles[]', ['class': 'select2-select form-control', 'multiple' : true , 'value' : user_roles]) }}

		    {{ form.render('csrf', ['value': this.security.getToken()]) }}
		    <p><small>Registered at: {{ object.created_at }}</small></p>
	 		
	 		<a href="{{ '/admin/users/' ~  object.id  ~ '/edit/password' }}" class="btn btn-secondary float-right">Change password</a>

	 		<button class="btn btn-success">UPDATE</button>
	 		{{ endform() }}
	 		<hr>

	 		<h4>User Roles</h4>
	 		
	 		
	  
	   		
		</div>
	 
		</div>
 
{% endblock %}

{% block footer %}
<script type="text/javascript"></script>
{% endblock %}