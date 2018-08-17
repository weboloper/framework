<!doctype html>
<html class="no-js h-100" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>{{ get_title(false) }}</title>
        <meta name="description" content="{{ app.description }}">
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

                    {% if is_authorized() %}
                        <a href="/oauth/logout" class="btn btn-danger">Logout</a>
                    {% else  %}
                        <a href="/oauth/login" class="btn btn-primary">Login</a>
                    {% endif  %}
                    <a href="https://phalconslayer.readme.io/" class="btn btn-secondary my-5" target="_blank">Docs</a>
                    
                    <p class="mt-5"><a href="#codes">Useful codes</a></p>

                     
                     
                    <h6>to do</h6>
                    <ul>
                      <li>resim video isert src olarak guid kullan</li>
                    </ul>
             
                </div>
            </div>

            <table  id="codes" class="table table-striped mb-5">
                <thead>
                    <tr>
                        <th>code</th>
                        <th>description</th>
                        <th>example</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>is_authorized()</td>
                        <td>check if user logged in</td>
                        <td>{{ is_authorized() }}</td>
                    </tr>

                    <tr>
                        <td>is_moderator()</td>
                        <td>check if user has moderator role</td>
                        <td>{ is_moderator()  }</td>
                    </tr>

                    <tr>
                        <td>is_admin()</td>
                        <td>check if user has admin role</td>
                        <td>{ is_admin()  }</td>
                    </tr>


                    <tr>
                        <td>user</td>
                        <td>get user credentials</td>
                        <td>{ dump(user) }</td>
                    </tr>

                    <tr>
                        <td>app</td>
                        <td>get app credentials</td>
                        <td>name: {{ app.name }}</br>description: {{ app.description }}</br>url: {{ app.url }}</br></td>
                    </tr>

                    <tr>
                        <td>teaser</td>
                        <td>excerpt long text, can be user as (text, lentgh, true|false) <br> default true: not cut text in half</td>
                        <td>{{ teaser( "Lorem ipsum dolor sit amet, consectetur adipiscing elit" , 12 ) }}</br></td>
                    </tr>

                    <tr>
                        <td>getImageSrc</td>
                        <td>excerpt long text, can be user as (text, lentgh, true|false) <br> default true: not cut text in half</td>
                        <td>{{ getImageSrc() }}</br></td>
                    </tr>


                    <tr>
                        <td>object.get_meta('meta_key',true|false)</td>
                        <td>get object meta  (meta_key, true|false) <br> default true: returns string, false: retuns array</td>
                        <td>object.get_meta('meta_key',true|false)</br></td>
                    </tr>

                </tbody>
            </table>

            </div>
        

         
    </body>
</html>