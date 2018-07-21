<html>
    <head>
        <title>{{ lang.get('emails.forgetpass.title') }}</title>
    </head>
    <body>
        {{ lang.get('emails.forgetpass.hi') }}<br><br>

        <div clas="well">
            {{ lang.get('emails.forgetpass.email_text') }}<br>
            
        </div>

        <a href="{{ url }}" class="btn btn-primary my-4">{{ lang.get('emails.forgetpass.button_text') }}</a>

        <p>
        {{ app.name }}
        </p>
    </body>
</html>