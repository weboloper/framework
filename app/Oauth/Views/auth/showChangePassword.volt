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
                <h4>Change Password Form</h4>
                <hr>
                <div class="alert alert-info">
                    <span class="glyphicon glyphicon-info-sign"></span> At first, you must run <code>php&nbsp;brood&nbsp;queue:worker</code> in your console. Check your mailer configuration if you can't send an email request.
                </div>
                <hr>
                <h5>Reset Password Procedure:</h5>
                <ul>
                    <li>Type in your Current Password</li>
                    <li>Type in your Password</li>
                    <li>Type in your Repeat Password</li>
                    <li>Click <code>Change Password</code> button</li>
                </ul>
            </div>
        </div>

        <div class="col-md-6">
            <div class="border p-5">
                <form class="form-vertical" method="POST" action="" autocomplete="off">
                    <input type="hidden" name="{{ security.getTokenKey() }}" value="{{ security.getToken() }}"/>
                    <div class="form-group">
                        <label>Current Password</label>
                        {{ password_field('current', 'class': 'form-control') }}
                    </div>
                    <hr>
                    <div class="form-group">
                        <label>New {{ lang.get('auth.login.password_label') }}</label>
                        {{ password_field('password', 'class': 'form-control') }}
                    </div>
                    <div class="form-group">
                        <label>New {{ lang.get('auth.login.re_password_label') }}</label>
                        {{ password_field('repassword', 'class': 'form-control') }}
                    </div>
                    <div class="pull-right">
                        <button id="register-btn" class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-ok"></span> Change Password </button>
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
