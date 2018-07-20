{% extends 'layouts/main.volt' %}

{% block title %}{{ app.name }}{% endblock %}

{% block header %}{% endblock %}

{% block content %}
	
    {{ form( 'media/upload', 'class' : 'form-horizontal media-uploader' , "enctype" : "multipart/form-data" , 'method' : 'post' ) }}
    <div class="row mb-5">
	    <div class="col-sm-8">
	   
        <div class="form-group">
            <strong>Title</strong>
            <input class="form-control title-input" name="title"></input>
        </div>

        <div class="form-group">
            <strong>File</strong>
            <input class="form-control" type="file" name="file"></input>
        </div>
         
        <button class="btn btn-primary">UPLOAD</button>
		 </div>

 
		</div>
    {{ endform() }}
     

{% endblock %}

{% block footer %}
<script  type="text/javascript">
$('.media-uploader').on('submit', function(e){
    
  e.preventDefault();
  var form = $(this);
  var formdata = false;
  if (window.FormData){
    formdata = new FormData(form[0]);
  }


  $.ajax({
    type: "POST",
    enctype: 'multipart/form-data',
    url: $(this).attr('action'),
    data: formdata ? formdata : form.serialize(),
    contentType : false,
    processData : false,
    dataType: 'json',
    // async: false,
    // XMLHttpRequest:  true,
    success: function (data) {

        console.log(data);
    }
  });

});
</script>
{% endblock %}