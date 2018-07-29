{% extends 'layouts/main.volt' %}

{% block title %}{{ app.name }}{% endblock %}

{% block header %}{% endblock %}

{% block content %}
 
    {{ form( 'admin/' ~   controller | lower ~  '/' ~  object.id  ~ '/update', 'class' : 'form-horizontal ') }}
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
 
	 	
        {% if in_array('body',  objectType['inputs']) %}
	 	    <div class="form-group">
            <strong>Content</strong>
            {{ form.render('body', ['class': 'form-control wysiwyg-full']) }}
        </div>
        {% endif %}

         {% if in_array('excerpt',  objectType['inputs']) %}
        <div class="form-group">
            <strong>Excerpt</strong>
            {{ form.render('excerpt', ['class': 'form-control']) }}
        </div>
        {% endif %}
 
	    {{ form.render('csrf', ['value': this.security.getToken()]) }}
 
		 </div>


		 <div class="col-sm-4">
		 	<div class="card bg-light mb-2">
		 		<div class="card-body">
				 
				 	
				 	  <div class="form-group d-flex flex-row">
 			            {{ form.render('status', ['class': 'form-control']) }}
 			       </div>

                <p> <small> Created At: {{ object.created_at }} </small></br>
                  <small> Updated At: {{ object.updated_at }} </small></p>
                </hr>
  		 
        
                <div class="text-right">
                <button class="btn btn-secondary mr-1" value="1">SAVE</button>
                <button class="btn btn-primary float-right" name="publish" value="publish">PUBLISH</button>
               </div>

	        	</div>
        	</div>
        {% if in_array('thumbnail',  objectType['inputs']) %}
        <div class="card bg-light mb-2">
          <div class="card-body">
              <div class="uploader-wrapper mb-2">
                <button class="btn">Select thumbnail</button>
                <input type="file" name="thumbnail" class="uploader-input" accept="image/png, image/jpeg"/>
                <input type="hidden" name="objectId"  class="uploader-object-id" value="{{object.getId()}}"  />
                
                <div class="uploader-preview thumbnail mt-1" >
                  {% if object.get_meta('thumbnail') %}
                    <img src="{{ object.get_meta('thumbnail')}}" class="img-thumbnail">
                  {% endif %}
                </div>
              </div>
            </div>
          </div>
        {% endif %}
            
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
     
  		 </div>
		</div>
    {{ endform() }}
    
    {% if objectType['metas'] %}
    	{% include "partials/meta_forms.volt" %}
    {% endif %}
    

{% endblock %}

{% block footer %}

<script type="text/javascript">
var ask_sure = false;

$('.form-horizontal').on('keyup change paste', 'input, select, textarea', function(){
    ask_sure = true;
});
</script>
{% if is_new %}
<script type="text/javascript">var ask_sure = true ;</script>
{% endif %}
<script type="text/javascript">
    console.log(ask_sure);
 
    $(window).on("beforeunload", function() {
      if(ask_sure){
        return "Are you sure? You didn't finish the form!";
      }
    });

    $(document).ready(function() {
      $(".form-horizontal").on("submit", function(e) {
     
        $(window).off("beforeunload");
        return true;
      });
    });
 
</script>

{% endblock %}