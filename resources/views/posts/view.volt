{% extends 'layouts/main.volt' %}

{% block title %}{{ app.name }}{% endblock %}

{% block header %}{% endblock %}

{% block content %}
    
    {{ partial('partials/post', ['single': true, 'post' : post ])}}
	
{% endblock %}

{% block footer %}
<script type="text/javascript"></script>
{% endblock %}