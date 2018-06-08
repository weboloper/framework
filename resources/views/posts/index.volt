{% extends 'layouts/main.volt' %}

{% block title %}Slayer - Newsfeed{% endblock %}

{% block header %}
<style type="text/css">
    .marginTop {
        margin-top:5vw;
    }
</style>
{% endblock %}

{% block content %}
	<ul>
    {% for post in posts %}
     	<li>
     		title: {{ post.title }}</br>
            phone meta: {{ post.meta('phone')}}</br>
     		timeAgo: {{ timeAgo( post.created_at )}}</br>
            date: {{ post.created_at }}</br>
            {{ timeFormat("d.m.Y" , post.created_at )   }}
     	</li>
    {% endfor %}
 	</ul>
{% endblock %}

{% block footer %}
<script type="text/javascript"></script>
{% endblock %}