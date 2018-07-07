{% extends 'admin/layouts/main.volt' %}

{% block title %}{{ app.name }}{% endblock %}

{% block header %}{% endblock %}

{% block content %}
	
    {{ form( 'admin/' ~   controller | lower ~  '/' ~  object.id  ~ '/update', 'class' : 'form-horizontal ') }}
    <div class="row mb-5">
	    <div class="col-sm-8">
	   
        <div class="form-group">
            <strong>Title</strong>
            {{ form.render('title', ['class': 'form-control slug-in', 'autocomplete' : 'off']) }}
        </div>

        <div class="form-group">
          <strong>Slug</strong>
           {{ form.render('slug', ['class': 'form-control slug-out' , 'autocomplete' : 'off']) }}
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
		 	<div class="card bg-light">
		 		<div class="card-body">
				 	<div class="d-flex justify-content-between">
					 	
					 	<button class="btn btn-success" style="width:100%">PUBLISH</button>
					 </div>

				 	<hr>
				 	<div class="form-group d-flex flex-row">
 			            {{ form.render('status', ['class': 'form-control']) }}
                   <button class="btn btn-secondary ml-1"  name="savePost" value="1">SAVE</button>
			     </div>

          

				 	<ul class="list-group mt-4">
		                <li class="list-group-item list-group-item-light">
		                    <i class="fas fa-calendar"></i> Created At:
		                    {{ object.created_at }}
		                </li>
		                <li class="list-group-item list-group-item-light">
		                    <i class="fas fa-calendar"></i> Updated At
		                    {{ object.updated_at }}
		                </li>
		         
		            </ul>
	        	</div>
        	</div> 
  
  			{% for term in objectType['terms'] %}
  				{% if termTypes[term]['hierachical']  %}
  					<div class="card">
						<div class="card-header bg-light">{{ termTypes[term]['name']}}</div>
    						<div class="card-body">
  								{% include "admin/partials/term_tree"  with ['term' : termTypes[term] , 'terms' : terms_array[term] ] %}
  							</div>
  						</div>
  				{% else  %}
  					{% include "admin/partials/term_select"  with ['term' : termTypes[term] , 'terms' : terms_array[term] ] %}
  				{% endif  %}
 					
 			{% endfor %}
     
  		 </div>
		</div>
    {{ endform() }}
    
    {% if objectType['metas'] %}
    	{% include "admin/partials/meta_forms.volt" %}
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