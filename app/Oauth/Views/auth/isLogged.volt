{% extends 'layouts/main.volt' %}

{% block title %}{{ app.name }}{% endblock %}

{% block header %}
<style type="text/css">
    .marginTop {
        margin-top:5vw;
    }
</style>
{% endblock %}

{% block content %}
    <div class="text-center">
        <h1>You have successfully landed to newsfeed</h1>
        <p>Try to change your <code>Auth Intended Url</code> at <code>config/app.php</code> under auth block. </p>
        <a href="{{ url('admin') }}" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-log-out"></span> Admin Section</a>
        <a href="{{ url('oauth/logout') }}" class="btn btn-danger btn-sm"><span class="fas fa-sign-out-alt"></span> Logout</a>
    </div>
{% endblock %}

{% block footer %}
<script type="text/javascript"></script>
{% endblock %}