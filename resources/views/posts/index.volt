{% extends 'layouts/main.volt' %}

{% block title %}{{ app.name }}{% endblock %}

{% block header %}{% endblock %}

{% block content %}
    
    {{ partial('partials/list-posts')}}
	
{% endblock %}

{% block footer %}
<script type="text/javascript"></script>
{% endblock %}