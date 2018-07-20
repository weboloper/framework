{% extends 'layouts/main.volt' %}

{% block title %}{{ app.name }}{% endblock %}

{% block header %}{% endblock %}

{% block content %}
	
    {{ form( 'admin/' ~   controller | lower ~  '/' ~  object.id  ~ '/update', 'class' : 'form-horizontal ') }}
    <div class="row mb-5">
	    <div class="col-sm-8">
	   
       <div class="form-group">
            <strong>Title</strong>
            <input class="form-control title-input" name="title" value="{{object.title}}"></input>
        </div> 

        <button class="btn btn-success">UPDATE</button>

        <a href="/admin/{{ controller |lower }}/{{ object.id }}/delete"  class="btn btn-danger delete-btn"  data-id="{{ object.id }}"><i class="fas fa-trash"></i> DELETE FILE </a>
 
		 </div>

		 <div class="col-sm-4">
		 	<div class="card bg-light">
		 		<div class="card-body">
			 
 

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
 
{% endblock %}