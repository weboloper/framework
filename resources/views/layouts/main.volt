<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>{% block title %}{% endblock %}</title>
        
        <!-- Bootstrap core CSS -->
        {{ stylesheet_link("resources/statics/plugins/bootstrap/dist/css/bootstrap.min.css")}}

        <!-- Plugin styles -->
        {{ stylesheet_link("resources/statics/plugins/font-awesome/css/fontawesome-all.min.css")}}

        {% block header %}{% endblock %}
        <script type="text/javascript">
            var baseUri     = '/';
            var controller  = '{{ controller | lower}}';
            var action      = '{{ action }}';
        </script>

    </head>
    <body>
        <div class="container">
            {% block content %}{% endblock %}
        </div>
       <script
        src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>

        <!-- Plugins -->
        {{ javascript_include("resources/statics/plugins/bootstrap/dist/js/bootstrap.min.js")}}
        {{ javascript_include('https://unpkg.com/sweetalert/dist/sweetalert.min.js') }}


        {% block footer %}{% endblock %}
    </body>
</html>
