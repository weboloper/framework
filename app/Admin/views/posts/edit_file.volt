{% extends 'layouts/main.volt' %}

{% block title %}{{ app.name }}{% endblock %}

{% block header %}{% endblock %}

{% block content %}
	
    {{ form( 'admin/' ~   controller | lower ~  '/' ~  object.id  ~ '/update', 'class' : 'form-horizontal ') }}
    <div class="row mb-5">
	    <div class="col-sm-8">
	   
       <div class="form-group">
            <strong>Title</strong>
            {{ form.render('title', ['class': 'form-control slug-in', 'autocomplete' : 'off']) }}
        </div>

        {{ hidden_field('slug') }}
        {{ hidden_field('status') }}

        <button class="btn btn-success">UPDATE</button>

        <a href="/admin/{{ controller |lower }}/{{ object.id }}/delete"  class="btn btn-danger delete-btn"  data-id="{{ object.id }}"><i class="fas fa-trash"></i> DELETE FILE </a>
 
		 </div>

 
		</div>
    {{ endform() }}
 
    

{% endblock %}

{% block footer %}
 
{% endblock %}