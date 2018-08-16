<!DOCTYPE html>
<!-- saved from url=(0060)https://v4-alpha.getbootstrap.com/examples/starter-template/ -->
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="https://v4-alpha.getbootstrap.com/favicon.ico">

    <title>Projeksen CMS</title>

    <!-- Bootstrap core CSS -->
    {{ stylesheet_link("resources/statics/plugins/bootstrap/dist/css/bootstrap.min.css")}}

    <!-- Plugin styles -->
    {{ stylesheet_link("resources/statics/plugins/DataTables/datatables.min.css")}}
    {{ stylesheet_link("resources/statics/plugins/select2/dist/css/select2.min.css")}}
    {{ stylesheet_link("resources/statics/plugins/font-awesome/css/fontawesome-all.min.css")}}

    
    {{ stylesheet_link("resources/statics/modules/admin/css/admin.css")}}
    
    <script type="text/javascript">
        var baseUri     = '/admin/';
        var controller  = '{{ router.getControllerName() | lower}}';
        var action      = '{{ router.getActionName() }}';
    </script>

    <style>
    html {
      font-size:14px;
    }
    </style>

    {% block header %} {% endblock %}
    

  </head>

  <body class="header-fixed">
    <div class="page-wrapper">

      <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
          <a href="#" class="btn btn-link sidebar-mobile-toggle">
              <i class="fa fa-bars"></i>
          </a>

          <a class="navbar-brand" href="#">Projeksen CMS</a>
 
          <a href="#" class="btn btn-link ml-auto d-md-none" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
              <i class="fa fa-bars"></i>
          </a>

          <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            <ul class="navbar-nav ml-auto">

              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <img src="{{ gravatar(user.email)}}" class="avatar avatar-sm" alt="logo" style="height:16px">
                      <span class="small ml-1 d-md-down-none"> {{ user.name }} </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                  <a class="dropdown-item" href="/oauth/change-password"> <i class="fa fa-user"></i> Change Password</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="/oauth/logout"><i class="fa fa-lock"></i> Logout</a>
                </div>
              </li>
            </ul>
       
          </div>
        </nav>

        <div class="main-container">
          {% include "partials/sidebar.volt" %}
           <div class="content">
              <div class="container-fluid">
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
            </div>
        </div>

      </div><!-- /.container -->
    </div><!-- /.page-wrapper -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!-- script src="/carbon/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script> -->

    <script
        src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>

    <!-- Plugins -->
    {{ javascript_include("resources/statics/plugins/bootstrap/dist/js/bootstrap.min.js")}}
    {{ javascript_include('resources/statics/plugins/select2/dist/js/select2.full.min.js') }}
    {{ javascript_include('resources/statics/plugins/tinymce/tinymce.min.js') }}
    {{ javascript_include("resources/statics/plugins/DataTables/datatables.min.js")}}
    {{ javascript_include('https://unpkg.com/sweetalert/dist/sweetalert.min.js') }}
    
     <!-- custom -->
     <script
        src="/resources/statics/js/app.functions.js?v=<?php print rand(1,9999999); ?>"
        crossorigin="anonymous"></script>
    <script
        src="/resources/statics/js/app.ajax.js?v=<?php print rand(1,9999999); ?>"
        crossorigin="anonymous"></script>
    <script
        src="/resources/statics/modules/admin/js/admin.js?v=<?php print rand(1,9999999); ?>"
        crossorigin="anonymous"></script>

    <script
        src="/resources/statics/js/tinymce.settings.js?v=<?php print rand(1,9999999); ?>"
        crossorigin="anonymous"></script>
 

    {{ assets.outputInlineJs() }}
 

    {% block footer %} {% endblock %}
  

</body>
</html>