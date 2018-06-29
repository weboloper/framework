{% extends 'admin/layouts/main.volt' %}

{% block title %}{{ app.name }}{% endblock %}

{% block header %}{% endblock %}

{% block content %}
	<a href="/admin/{{ controller |lower }}/new" class="float-right btn btn-primary">Add New</a>
    <h3>{{ controller }} </h3>
    <hr>
	<table  id="users" class="table table-striped table-bordered dashboard-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>Name</th>
                <th>Roles</th>
                <th>Activated</th>
                <th></th>
                <th></th>
 
            </tr>
        </thead>
        <thead class="searchfilter"> </thead>
        <tbody>
        	{% for object in objects %}
        	 	<tr>
        	 		<td>{{ object.id }}</td>
        	 		<td>{{ object.email }}</td>
                    <td>{{ object.name }}</td>
        	 		<td>
                        {% for role in object.roles %}
                            {{ role.name }} </br>
                        {% endfor %}
                    </td>
                    <td>{{ object.activated }}</td>
         	 		<td><a href="/admin/{{ controller |lower }}/{{ object.id }}/edit" class="text-success"><i class="fas fa-edit"></i></a></td>
                    <td><a href="/admin/{{ controller |lower }}/{{ object.id }}/delete"  class="text-danger delete-btn"  data-id="{{ object.id }}"><i class="fas fa-trash"></i></a></td>
            	</tr>
        	{% endfor %}

        </tbody>
 {% endblock %}

{% block footer %}
<!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.css"/> -->
<!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css">
 --> 

{% endblock %}