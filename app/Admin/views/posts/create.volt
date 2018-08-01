{% extends 'layouts/main.volt' %}

{% block title %}{{ app.name }}{% endblock %}

{% block header %}{% endblock %}

{% block content %}
 
    {{ form( 'admin/' ~   controller | lower ~  '/store', 'class' : 'form-horizontal ') }}
    
    <div class="row">
	    <div class="col-sm-8">
	   
        <div class="form-group">
            <strong>Title</strong>
            {{ form.render('title', ['class': 'form-control slug-in', 'autocomplete' : 'off']) }}
        </div>
        
         <div class="form-group">
          <strong>Slug</strong>
           {{ form.render('slug', ['class': 'form-control slug-out' , 'autocomplete' : 'off'  ]) }}
           <span class="help">Alphanumeric characters and "-" only</span>
        </div>
 
	 	   {% if in_array('excerpt',  objectType['inputs']) %}
        <div class="form-group">
            <strong>Excerpt</strong>
            {{ form.render('excerpt', ['class': 'form-control']) }}
        </div>
        {% endif %}

        {% if in_array('body',  objectType['inputs']) %}
	 	    <div class="form-group">
            <strong>Content</strong>
            {{ form.render('body', ['class': 'form-control wysiwyg-full']) }}
        </div>
        {% endif %}

      {{ hidden_field('status',  'value' : 'draft' )}}
      {{ hidden_field('type',  'value' : objectType['slug'] )}}
	    {{ form.render('csrf', ['value': this.security.getToken()]) }}
 
		 </div>


		 <div class="col-sm-4">
		 	  
  
            
  			{% for term in objectType['terms'] %}

  				{% if termTypes[term]['hierachical']  %}
             {% set cachename =  'metabox' ~  termTypes[term]['taxonomy'] %}
             {% cache cachename %}
                <div class="card mb-2">
                <div class="card-header bg-light">{{ termTypes[term]['name']}}</div>
                <div class="card-body" style="max-height:200px; overflow-y: scroll">
                  {% include "partials/term_tree"  with ['term' : termTypes[term] , 'terms' : terms_array[term] ] %}
                </div>
              </div>
            {% endcache %}

  					
  				{% else  %}
  					{% include "partials/term_select"  with ['term' : termTypes[term] , 'terms' : terms_array[term] ] %}
  				{% endif  %}
 					
 			  {% endfor %}

      <button class="btn btn-primary">Next Step</button>
 
     
  		 </div>
		</div>
    {{ endform() }}
    
 

{% endblock %}

{% block footer %}
 
{% endblock %}