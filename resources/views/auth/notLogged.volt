<!doctype html>
<html class="no-js h-100" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>{{ get_title(false) }}</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="manifest" href="site.webmanifest">
        <!-- Place favicon.ico in the root directory -->

                <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">

    </head>
    <body class="h-100">
         
         <div class="container h-100">
            <div class="row h-100 d-flex">
              <div class="text-center my-auto mx-auto">
                <h1>Welcome to {{ app.name }}</h1>

                <p>{{ teaser( "welcome to solid layer teaser", 12 ) }}</p>
                <a href="/oauth/login" class="btn btn-primary">Login</a>

                <p><a href="https://phalconslayer.readme.io/">Docs</a></p>
                <h5>Todo</h5>
                {{  dump(app) }}

                {% if is_authorized() %}
                    <a href="/oauth/logout" class="btn btn-danger">Logout</a>
                {% endif  %}
              </div>
            </div>
         </div>

         
    </body>
</html>