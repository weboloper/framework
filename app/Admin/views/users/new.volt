{% extends 'layouts/main.volt' %}

{% block title %}{{ app.name }}{% endblock %}

{% block header %}{% endblock %}

{% block content %}
	<h3>New User</h3>
	<hr>
    {{ form( 'admin/users/store', 'class' : 'form-horizontal') }}
    <div class="row">
	    <div class="col-sm-8">
	       
        <input type="hidden" name="{{ security.getTokenKey() }}" value="{{ security.getToken() }}"/>

        <div class="form-group">
            <label>{{ lang.get('auth.login.name_label') }}</label>
            {{ form.render('name', ['class': 'form-control']) }}
        </div>
        <div class="form-group">
            <label>{{ lang.get('auth.login.username_label') }}</label>
            {{ form.render('username', ['class': 'form-control']) }}
        </div>
        <div class="form-group">
            <label>{{ lang.get('auth.login.email_label') }}</label>
            {{ form.render('email', ['class': 'form-control']) }}
        </div>
        <div class="form-group">
            <label>{{ lang.get('auth.login.password_label') }}</label>
            {{ password_field('password', 'class': 'form-control',  'placeholder' : 'Password' ) }}
        </div>
        <div class="form-group">
            <label>{{ lang.get('auth.login.re_password_label') }}</label>
            {{ password_field('repassword', 'class': 'form-control',  'placeholder' : 'Repeat password' ) }}
        </div>
        <div class="form-group">
            <label>{{ lang.get('auth.login.status_label') }}</label>
            {{ form.render('status', ['class': 'form-control']) }}
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