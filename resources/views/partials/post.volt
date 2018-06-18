<article>
	{% if single is defined %}
	<h1>{{ post.title }}</h1>
	{% endif %}

	id: {{ post.id }}</br>
	title: {{ post.title }}</br>
	phone meta: {{ post.get_post_meta('phone', true )}}</br>
	timeAgo: {{ timeAgo( post.created_at )}}</br>
	date: {{ post.created_at }}</br>
	{{ timeFormat("d.m.Y" , post.created_at )   }}
	
	{% if teaser is defined %}
	<p>link</p>
	{% endif %}
</article>
