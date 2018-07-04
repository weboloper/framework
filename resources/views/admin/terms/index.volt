{% extends 'admin/layouts/main.volt' %}

{% block title %}{{ app.name }}{% endblock %}

{% block header %}{% endblock %}

{% block content %}
	<a href="/admin/{{ controller |lower }}/new?taxonomy={{ objectType['taxonomy'] }}" class="float-right btn btn-primary">Add New</a>
    <h3>All {{ objectType['name'] }}</h3>
    <hr>
	<table id="posts" class="table table-striped table-bordered dashboard-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Slug</th>
                <th>Count</th>
                <th>Parent</th>
                <th></th>
                <th></th>
 
            </tr>
        </thead>
        <thead class="searchfilter"> </thead>
        <tbody>
        	{% for object in objects %}
        	 	<tr>
        	 		<td>{{ object.term_id }}</td>
        	 		<td>{{ object.name | trim   ? object.name : '<i class="text-success ">auto draft</i>'}}</td>
                    <td>{{ object.slug }}</td>
        	 		<td>{{ object.count }}</td>
                    <td>{{ object.parent_id }}</td>
                    <td><a href="/admin/{{ controller |lower }}/{{ object.term_id }}/edit?type={{ objectType['taxonomy'] }}" class="text-success"><i class="fas fa-edit"></i></a></td>
        	 		<td><a href="/admin/{{ controller |lower }}/{{ object.term_id }}/delete"  class="text-danger delete-btn"  data-id="{{ object.term_id }}"><i class="fas fa-trash"></i></a></td>
            	</tr>
        	{% endfor %}

        </tbody>
 {% endblock %}

{% block footer %}
<!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.css"/> -->
<!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css">
 --> 

{% endblock %}