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
                <h4>Login Form</h4>
                <hr>
                <div class="alert alert-info">
                    <span class="glyphicon glyphicon-info-sign"></span> Were you able to activate your account through your email? If <code>No</code> activate it first.
                </div>
                <hr>
                <h5>Login Procedure:</h5>
                <ul>
                    <li>Type in your Email</li>
                    <li>Type in your Password</li>
                    <li>Click <code>Login</code> button</li>
                </ul>
            </div>
        </div>
        <div class="col-md-6">
            <div class="border p-5">
                <form class="form-vertical" method="POST" action="{{ route('attemptToLogin') }}" autocomplete="off">
                    {{ csrf_field() }}

                    <input type="hidden" name="ref" value="{{ request().get('ref') }}">

                    <div class="form-group">
                        <label>{{ lang.get('auth.login.email_label') }}</label>
                        {{ text_field('email', 'class': 'form-control', 'value' :'mod@slayer.com') }}
                    </div>

                    <div class="form-group">
                        <label>{{ lang.get('auth.login.password_label') }}</label>
                        {{ password_field('password', 'class': 'form-control' , 'value' :'123123123') }}
                    </div>

                    <div class="form-group">
                        <div class="text-center">
                            <button id="login-btn" class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-ok"></span> {{ lang.get('auth.button.login_button') }}</button>

                            <a href="{{ route('showRegistrationForm') }}" class="btn btn-info">{{ lang.get('auth.button.register_button') }}</a>

                            <a href="{{ route('showForgetPasswordForm') }}" class="btn btn-danger">{{ lang.get('auth.button.forgot_button') }}</a>
                        </div>
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
