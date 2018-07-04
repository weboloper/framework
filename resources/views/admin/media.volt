{% extends 'admin/layouts/main.volt' %}

{% block title %}{{ app.name }}{% endblock %}

{% block header %}{% endblock %}

{% block content %}
 <iframe  width="100%" height="550" frameborder="0"
	src="/filemanager/dialog.php?type=0">
</iframe>
{% endblock %}

{% block footer %}
<script type="text/javascript"></script>
{% endblock %}