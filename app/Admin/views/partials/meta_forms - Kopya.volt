<div class="row">
<div class="col-sm-8">
<div class="bg-light  border">
<table class="table">
    {{ form( 'id' : 'meta-form',   'admin/' ~   controller | lower ~  '/add_meta', 'class' : 'object-meta-form') }}{{ endform() }}
    <thead>
      <tr>
        {{ hidden_field( 'objectId' , "value" :   object.id  , 'form' : 'meta-form' ) }}
        {{ hidden_field( 'objectType' , "value":   'post'    , 'form' : 'meta-form'  ) }}
        <td>{{ select("meta_key", objectType['metas'] ,  'useEmpty': true ,  'class': 'form-control' , 'form' : 'meta-form'  )   }}</td>
        <td>{{ text_area( 'meta_value' , 'class': 'form-control' , 'form' : 'meta-form'  )   }}</td>
        <td colspan="2" width="20%"><button href="#" class="btn btn-primary" style="width:100%" form="meta-form">Add</button></td>
      </tr>
    </thead>
    <tbody>

         {% for meta in object.get_meta() %}
            {{ form( 'id' : 'meta-form-' ~ object.id ,   'admin/' ~   controller | lower ~  '/add_meta', 'class' : 'object-meta-form') }}{{ endform() }}
            <tr>
                <td>{{ meta['meta_key'] }}</td>
                <td>{{ text_area( 'meta_value' , 'class': 'form-control' , 'value' : meta['meta_value'] , 'form' : 'meta-form-' ~ object.id  ) }}</td>
                <td><button href="#" class="btn btn-secondary" form='meta-form-{{object.id}}'  >Update</button></td>
                <td><a href="#" 
                    class="delete-meta-btn btn btn-danger" 
                    data-object-id="{{ meta['meta_id'] }}"
                    data-object="postMeta" >Delete</a></td>
            </tr>
            {{ hidden_field( 'objectId' , "value":   object.id , 'form' : 'meta-form-' ~ object.id  ) }}
            {{ hidden_field( 'meta_key' , "value":   meta['meta_key'] , 'form' : 'meta-form-' ~ object.id  ) }}
            {{ hidden_field( 'metaId' , "value":   meta['meta_id'] , 'form' : 'meta-form-' ~ object.id ) }}
            {{ hidden_field( 'objectType' , "value":   'post'    , 'form' : 'meta-form-' ~ object.id )}}
            {{ endform() }}
       
        {% endfor %}

 
    </tbody>
</table>
</div>
</div>
</div>