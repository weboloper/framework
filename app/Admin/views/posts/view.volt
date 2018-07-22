{% extends 'layouts/main.volt' %}

{% block title %}{{ app.name }}{% endblock %}

{% block header %}{% endblock %}

{% block content %}
  <h1>{{ object.getTitle() }}</h1>
  <div>
    {{object.getBody()}}
  </div>
{% endblock %}

{% block footer %}
 
{% endblock %}