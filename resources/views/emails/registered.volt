<html>
    <head>
        <title>{{ lang.get('emails.register.title') }}</title>
    </head>
    <body>
        {{ lang.get('emails.register.hi') }}<br><br>

        <div clas="well">
            {{ lang.get('emails.register.email_text') }}<br>
            
        </div>

        <a href="{{ url }}" class="btn btn-primary my-4">{{ lang.get('emails.register.button_text') }}</a>

        <p>
        {{ app.name }}
        </p>
    </body>
</html>