{% extends 'admin/layouts/main.volt' %}

{% block title %}{{ app.name }}{% endblock %}

{% block header %}{% endblock %}

{% block content %}
	<a href="/admin/{{ controller |lower }}/new" class="float-right btn btn-primary">Add New</a>
    <h3>{{ controller }} </h3>

    <a href="/admin/{{ controller |lower }}" class="text-secondary">All</a>
    <a href="/admin/{{ controller |lower }}?status=trash" class="text-secondary">Trash</a>
    <hr>
	<table id="posts" class="table table-striped table-bordered dashboard-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Status</th>
                <th>Parent</th>
                <th>Created At</th>
                <th></th>
                <th></th>
 
            </tr>
        </thead>
        <thead class="searchfilter"> </thead>
        <tbody>
        	{% for object in objects %}
        	 	<tr>
        	 		<td>{{ object.id }}</td>
        	 		<td>{{ object.title | trim   ? object.title : '<i class="text-success ">auto draft</i>'}}</td>
                    <td>{{ object.status }}</td>
        	 		<td>{{ object.parent }}</td>
                    <td>{{ object.created_at }}</td>
                    <td><a href="/admin/{{ controller |lower }}/{{ object.id }}/edit" class="text-secondary"><i class="fas fa-edit"></i></a></td>
        	 		<td><a href="/admin/{{ controller |lower }}/{{ object.id }}/delete"  class="text-secondary delete-btn"  data-id="{{ object.id }}"><i class="fas fa-trash"></i></a></td>
            	</tr>
        	{% endfor %}

        </tbody>
 {% endblock %}

{% block footer %}
<!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.css"/> -->
<!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css">
 --> 

{% endblock %}