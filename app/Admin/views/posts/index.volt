{% extends 'layouts/main.volt' %}

{% block title %}{{ app.name }}{% endblock %}

{% block header %}{% endblock %}

{% block content %}

	<a href="/admin/{{ controller |lower }}/new?type={{ objectType['slug'] }}" class="float-right btn btn-primary">Add New</a>
    <h3>All {{ objectType['name'] }}</h3>
    <a href="{{ url('admin/' ~ controller |lower  ~ "?type=" ~ objectType['slug'])}}">All</a> | 
    <a href="{{ url('admin/' ~ controller |lower  ~ "?type=" ~ objectType['slug'] ~ "&status=trash")}}">Trash</a>
    <hr>
	<table id="posts" class="table table-striped table-bordered dashboard-table">
        <thead>
            <tr>
                <th>ID</th>
                {% if objectType['slug'] == 'attachment' %}<th>File</th>{% endif %}
                <th>Title</th>
                <th>Slug</th>
                {% if objectType['slug'] == 'attachment' %}<th>Type</th>{% endif %}
                <th>User</th>
                <th>Status</th>
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
                    {% if objectType['slug'] == 'attachment' %}
                    <td>{{ get_file_icon( object)}}</td>
                    {% endif %}
                    <td>
                        {{ object.title | trim   ? object.title : '<i class="text-success ">auto draft</i>'}}
                        {% if objectType['slug'] == 'attachment' %}
                            <p><a href="{{object.guid}}" target="_blank" class="text-primary">{{object.slug}}</a></p>
                        {% endif %}
                    </td>
                    <td>{{object.slug}}</td>
                    {% if objectType['slug'] == 'attachment' %}
                    <td>{{ object.mime_type }}</td>
                    {% endif %}
                    <td>{{ object.user_id }}</td>
        	 		<td>{{ object.status }}</td>
                    <td>{{ object.created_at }}</td>
                    <td><a href="/admin/{{ controller |lower }}/{{ object.id }}/edit?type={{ objectType['slug'] }}" class="text-success"><i class="fas fa-edit"></i></a></td>
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