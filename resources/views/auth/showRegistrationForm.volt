{% extends 'layouts/main.volt' %}

{% block title %}{{ app.name }}{% endblock %}

{% block header %}
{% endblock %}

{% block content %}
    <div class="row mt-5">
        <div class="col-md-10 offset-md-1">
            {% if flash().session().has('info')  %}
                <div class="alert alert-info">
                    {{ flash().session().output() }}
                </div>
            {% endif %}

            {# Success Message #}
            {% if flash().session().has('success')  %}
                <div class="alert alert-success">
                    {{ flash().session().output() }}
                </div>
            {% endif %}

            {# Error Messages #}
            {% if flash().session().has('error')  %}
                <div class="alert alert-danger">
                    {{ flash().session().output() }}
                </div>
            {% endif %}
        </div>
        <div class="col-md-4 offset-md-1">
            <div class="border p-5">
                <h4>Registration Form</h4>
                <hr>
                <div class="alert alert-info">
                    <span class="glyphicon glyphicon-info-sign"></span> At first, you must run <code>php&nbsp;brood&nbsp;queue:worker</code> in your console. Check your mailer configuration if you can't send an email request.
                </div>
                <hr>
                <h5>Registration Procedure:</h5>
                <ul>
                    <li>Type in your Email</li>
                    <li>Type in your Password</li>
                    <li>Type in your Repeat Password</li>
                    <li>Click <code>Register</code> button</li>
                </ul>
            </div>
        </div>

        <div class="col-md-6">
            <div class="border p-5">
                <form class="form-vertical" method="POST" action="{{ route('storeRegistrationForm') }}" autocomplete="off">
                    <input type="hidden" name="{{ security.getTokenKey() }}" value="{{ security.getToken() }}"/>

                    {% if config.app.auth.usernames %}
                    <div class="form-group">
                        <label>{{ lang.get('auth.login.username_label') }}</label>
                        {{ text_field('username', 'class': 'form-control') }}
                        <small class="inline-helper">{{ lang.get('auth.login.username_helper') }}</small>
                    </div>
                    {% endif %}
                  
 

                    <div class="form-group">
                        <label>{{ lang.get('auth.login.email_label') }}</label>
                        {{ text_field('email', 'class': 'form-control') }}
                    </div>
                    <div class="form-group">
                        <label>{{ lang.get('auth.login.password_label') }}</label>
                        {{ password_field('password', 'class': 'form-control') }}
                    </div>
                    <div class="form-group">
                        <label>{{ lang.get('auth.login.re_password_label') }}</label>
                        {{ password_field('repassword', 'class': 'form-control') }}
                    </div>
                    <div class="pull-right">
                        <button id="register-btn" class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-ok"></span> Register </button>
                    </div>
                    <div class="clearfix"></div>
                    {{ partial('layouts/processingTime') }}
                </form>
            </div>
        </div>
    </div>
{% endblock %}

{% block footer %}
{% endblock %}
