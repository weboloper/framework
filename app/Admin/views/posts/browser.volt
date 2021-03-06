{% extends 'layouts/main.volt' %}

{% block title %}{{ app.name }}{% endblock %}

{% block header %}
<style type="text/css">
.fluidMedia {
    position: relative;
    padding-bottom: 56.25%; /* proportion value to aspect ratio 16:9 (9 / 16 = 0.5625 or 56.25%) */
    padding-top: 30px;
    height: 0;
    overflow: hidden;
}

.fluidMedia iframe {
    position: absolute;
    top: 0; 
    left: 0;
    width: 100%;
    height: 100%;
</style>
{% endblock %}

{% block content %}

 
<iframe frameborder="0"  scrolling="auto" src="/media/browser?type=all" width="100%" height="900px"></iframe>
 
{% endblock %}

{% block footer %}
 
{% endblock %}