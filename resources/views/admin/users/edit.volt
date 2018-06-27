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

 

	    {{ form.render('csrf', ['value': this.security.getToken()]) }}
 		
 		<hr>
   		<a href="{{ '/admin/users/' ~  object.id  ~ '/edit/password' }}" class="btn btn-secondary">Change password</a>

		 </div>
		 <div class="col-sm-4">
		 	<div class="card bg-light">
		 		<div class="card-body">
				 	<div class="d-flex justify-content-between">
					 	<button class="btn btn-success" style="width:100%">SAVE</button>
					 </div>

				 	<hr>
		 

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
		  
  		 </div>
		</div>
    {{ endform() }}

{% endblock %}

{% block footer %}
<script type="text/javascript"></script>
{% endblock %}