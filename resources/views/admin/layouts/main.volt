<!DOCTYPE html>
<!-- saved from url=(0060)https://v4-alpha.getbootstrap.com/examples/starter-template/ -->
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="https://v4-alpha.getbootstrap.com/favicon.ico">

    <title>Projeksen Admin</title>
    <link rel="canonical" href="https://getbootstrap.com/docs/4.0/examples/starter-template/">

    <!-- Bootstrap core CSS -->
    <link href="/admin_assets/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/admin_assets/css/fontawesome-all.min.css" rel="stylesheet">
    <link href="/admin_assets/custom.css" rel="stylesheet">
    
    <script type="text/javascript">
        var baseUri     = '/admin/';
        var controller  = '{{ router.getControllerName() | lower}}';
        var action      = '{{ router.getActionName() }}';
    </script>

    <style type="text/css" media="screen">
      .dataTables_filter {
        display: none; 
        }
    </style>

    

  </head>

  <body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
      <a class="navbar-brand" href="/admin">Admin Section</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">

          {%- set menu =
              [
                  'Posts': 'posts',
                  'Pages': 'pages',
                  'Users': 'users' 
              ]
          -%}

          {%- for value, key in menu -%}
              <li class="nav-item {{  controller | lower  == key ? 'active' : '' }}">
                  {{ link_to('admin/' ~ key,  value , 'title': value , 'class': 'nav-link') }}
              </li>
          {%- endfor -%}
 
        </ul>

        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="/">Visit Site</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/oauth/logout">Logout</a>
          </li>
   
        </ul>

      </div>
    </nav>

 
    </nav>

    <div class="container">

      <div class="col-xs-12">
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
      <div>
        {% block content %} {% endblock %}
      </div>

    </div><!-- /.container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!-- script src="/admin_assets/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script> -->

    <script
    src="https://code.jquery.com/jquery-3.3.1.min.js"
    integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
    crossorigin="anonymous"></script>

    <script src="/admin_assets/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    <script src="/admin_assets/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="/admin_assets/ie10-viewport-bug-workaround.js"></script>
    


    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>

    <script src="/js/bootstrap-notify.js?v=<?php echo rand(111111,999999);?>"></script>
    <script src="/js/app.ajax.js?v=<?php echo rand(111111,999999);?>"></script>
    <script src="/admin_assets/scripts.js?v=<?php echo rand(111111,999999);?>"></script>
 

    {% block footer %} {% endblock %}
  

</body></html>