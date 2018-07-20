<article>
	{% if single is defined %}
	<h1>{{ post.title }}</h1>
	{% endif %}

	id: {{ post.id }}</br>
	title: {{ post.title }}</br>
	title: {{ post.body }}</br>
	phone meta: {{ post.get_meta('phone', true )}}</br>
	timeAgo: {{ timeAgo( post.created_at )}}</br>
	date: {{ post.created_at }}</br>
	{{ timeFormat("d.m.Y" , post.created_at )   }}
	
	{% if teaser is defined %}
	<p>link</p>
	{% endif %}

	{{ dump(get_the_post_thumbnail(post))}}
</article>
