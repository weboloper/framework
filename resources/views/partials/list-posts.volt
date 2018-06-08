{% for post in posts %}
    {{ partial('partials/post', ['teaser': true, 'post' : post])}}
{% endfor %}