<div class="card bg-light mb-2">
  <div class="card-body">
      <div class="uploader-wrapper mb-2">
        <button class="btn mb-1">Select thumbnail</button>
        <input type="file" name="thumbnail" class="uploader-input" accept="image/png, image/jpeg"/>
        <input type="hidden" name="objectId"  class="uploader-object-id" value="{{ objectid }}"  />
        <input type="hidden" name="objecType"  class="uploader-object-type" value="{{ objectype }}"  />
        
        <div class="uploader-preview thumbnail mt-1" >
          {% if object.get_meta('thumbnail') %}
            <img src="{{ object.get_meta('thumbnail')}}" class="img-thumbnail">
            <a href="#" class="btn btn-danger uploader-delete" data-id="{{ objectid }}" data-type="{{ objectype }}"><i class="fas fa-trash"></i></a>
          {% endif %}
        </div>
      </div>
    </div>
  </div>