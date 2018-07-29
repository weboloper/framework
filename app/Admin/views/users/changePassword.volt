{% extends 'layouts/main.volt' %}

{% block title %}{{ app.name }}{% endblock %}

{% block header %}{% endblock %}

{% block content %}
   
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
        <button id="register-btn" class="btn btn-success" type="submit">SAVE </button>
         <a href="{{ '/admin/users/' }}" class="btn btn-secondary">CANCEL</a>

  
		 </div>
		 
		</div>
    {{ endform() }}

{% endblock %}

{% block footer %}
<script type="text/javascript"></script>
{% endblock %}