{% extends 'admin/layouts/main.volt' %}

{% block title %}{{ app.name }}{% endblock %}

{% block header %}{% endblock %}

{% block content %}
	{% include "admin/partials/grid.volt" %}
{% endblock %}

{% block footer %}
<script type="text/javascript"></script>
{% endblock %}