{% extends 'layouts/main.volt' %}

{% block title %}{{ app.name }}{% endblock %}

{% block header %}{% endblock %}

{% block content %}
    <a href="{{ '/admin/users/' }}" class="btn btn-danger float-right">QUIT</a>
	<h3>Change Password</h3> 
	<hr>
    {{ form(  'class' : 'form-horizontal') }}
    <div class="row">
	    <div class="col-sm-8">
 
        <div class="form-group">
            <label>{{ lang.get('auth.login.password_label') }}</label>
            {{ password_field('password', 'class': 'form-control') }}
        </div>
        <div class="form-group">
            <label>{{ lang.get('auth.login.re_password_label') }}</label>
            {{ password_field('repassword', 'class': 'form-control') }}
        </div>
        <div class="pull-right">
            <button id="register-btn" class="btn btn-success" type="submit">SAVE </button>
        </div>

  
		 </div>
		 
		</div>
    {{ endform() }}

{% endblock %}

{% block footer %}
<script type="text/javascript"></script>
{% endblock %}