<!DOCTYPE html>
<!-- saved from url=(0060)https://v4-alpha.getbootstrap.com/examples/starter-template/ -->
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="https://v4-alpha.getbootstrap.com/favicon.ico">

    <title>Projeksen CMS</title>

    <!-- Bootstrap core CSS -->
    <link href="/assets/carbon/css/carbon.css" rel="stylesheet">
    <link href="/assets/carbon/css/custom.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/core/select2/dist/css/select2.min.css" rel="stylesheet">
    <link href="/core/font-awesome/css/fontawesome-all.min.css" rel="stylesheet">

 
    
    <script type="text/javascript">
        var baseUri     = '/admin/';
        var controller  = '{{ router.getControllerName() | lower}}';
        var action      = '{{ router.getActionName() }}';
    </script>

    <style type="text/css" media="screen">
      .dataTables_filter {
        display: none; 
        }

        a[href="https://froala.com/wysiwyg-editor"], a[href="https://www.froala.com/wysiwyg-editor?k=u"] {
          display: none !important;
          position: absolute;
          top: -99999999px;
        }

    </style>

    

  </head>

  <body class="header-fixed">
    <div class="page-wrapper">

      <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top page-header">
          <a href="#" class="btn btn-link sidebar-mobile-toggle d-md-none mr-auto">
              <i class="fa fa-bars"></i>
          </a>

          <a class="navbar-brand" href="/admin#">
              Projeksen CMS
          </a>

          <a href="#" class="btn btn-link sidebar-toggle d-md-down-none">
              <i class="fa fa-bars"></i>
          </a>

          <ul class="navbar-nav ml-auto">
              <li class="nav-item d-md-down-none">
                  <a href="#">
                      <i class="fa fa-bell"></i>
                   </a>
              </li>

 
              <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <img src="https://www.gravatar.com/avatar/00000000000000000000000000000000" class="avatar avatar-sm" alt="logo">
                      <span class="small ml-1 d-md-down-none">Account</span>
                  </a>

                  <div class="dropdown-menu dropdown-menu-right">
                      <div class="dropdown-header">Account</div>

                      <a href="/admin/users/{{ user.id}}/edit/password" class="dropdown-item">
                          <i class="fa fa-user"></i> Change Password
                      </a>
 
                      <a href="/oauth/logout" class="dropdown-item">
                          <i class="fa fa-lock"></i> Logout
                      </a>
                  </div>
              </li>
          </ul>
      </nav>
 
      <div class="main-container">
          {% include "admin/partials/sidebar.volt" %}
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

    <script src="/assets/carbon/js/popper.min.js"></script>
    <script src="/assets/carbon/js/bootstrap.min.js"></script>
    <script src="/core/select2/dist/js/select2.full.min.js"></script>
    <script src="/core/tinymce/tinymce.min.js"></script>
     

 

    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>

    <script src="/assets/js/bootstrap-notify/bootstrap-notify.js?v=<?php echo rand(111111,999999);?>"></script>
    <script src="/assets/carbon/js/carbon.js?v=<?php echo rand(111111,999999);?>"></script>
    <script src="/assets/carbon/js/demo.js?v=<?php echo rand(111111,999999);?>"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <script src="/assets/carbon/js/app.ajax.js?v=<?php echo rand(111111,999999);?>"></script>
    <script src="/assets/carbon/js/scripts.js?v=<?php echo rand(111111,999999);?>"></script>
    {{ assets.outputInlineJs() }}

    <script type="text/javascript">
      tinymce.init({
        selector: '.wysiwyg',
        plugins : 'code image media', 
        toolbar : 'undo redo | image code',
        // menubar: 'file edit insert view format table tools help',
        // menubar: false,


        images_upload_url: '/media/upload',
        
        // override default upload handler to simulate successful upload
        images_upload_handler: function (blobInfo, success, failure) {
            var xhr, formData;
          
            xhr = new XMLHttpRequest();
            xhr.withCredentials = false;
            xhr.open('POST', '/media/upload');
          
            xhr.onload = function() {
                var json;
            
                if (xhr.status != 200) {
                    failure('HTTP Error: ' + xhr.status);
                    return;
                }
            
                json = JSON.parse(xhr.responseText);
            
                if (!json || typeof json.location != 'string') {
                    failure('Invalid JSON: ' + xhr.responseText);
                    return;
                }
            
                success(json.location);
            };
          
            formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());
          
            xhr.send(formData);
        },

      });
    </script>

 

    {% block footer %} {% endblock %}
  

</body>
</html>