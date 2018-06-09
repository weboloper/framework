<!DOCTYPE html>
<!-- saved from url=(0060)https://v4-alpha.getbootstrap.com/examples/starter-template/ -->
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="https://v4-alpha.getbootstrap.com/favicon.ico">

    <title>Starter Template for Bootstrap</title>
    <link rel="canonical" href="https://getbootstrap.com/docs/4.0/examples/starter-template/">

    <!-- Bootstrap core CSS -->
    <link href="/admin_assets/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/admin_assets/custom.css" rel="stylesheet">
  </head>

  <body>

    <nav class="navbar navbar-toggleable-md navbar-inverse bg-inverse fixed-top">
      <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <a class="navbar-brand" href="https://v4-alpha.getbootstrap.com/examples/starter-template/#">Navbar</a>

      <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item active">
            <a class="nav-link" href="https://v4-alpha.getbootstrap.com/examples/starter-template/#">Home <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="https://v4-alpha.getbootstrap.com/examples/starter-template/#">Link</a>
          </li>
          <li class="nav-item">
            <a class="nav-link disabled" href="https://v4-alpha.getbootstrap.com/examples/starter-template/#">Disabled</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="http://example.com/" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</a>
            <div class="dropdown-menu" aria-labelledby="dropdown01">
              <a class="dropdown-item" href="https://v4-alpha.getbootstrap.com/examples/starter-template/#">Action</a>
              <a class="dropdown-item" href="https://v4-alpha.getbootstrap.com/examples/starter-template/#">Another action</a>
              <a class="dropdown-item" href="https://v4-alpha.getbootstrap.com/examples/starter-template/#">Something else here</a>
            </div>
          </li>
        </ul>
        <form class="form-inline my-2 my-lg-0">
          <input class="form-control mr-sm-2" type="text" placeholder="Search">
          <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>
      </div>
    </nav>

    <div class="container">

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

      <div class="starter-template">
        {% block content %} {% endblock %}
      </div>

    </div><!-- /.container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/admin_assets/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="/admin_assets/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    <script src="/admin_assets/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="/admin_assets/ie10-viewport-bug-workaround.js"></script>
  

</body></html>