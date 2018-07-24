<div class="form-group p-3 pb-5 bg-light card">
    <strong>Meta</strong>
    {{ form(  'admin/' ~   controller | lower ~  '/add_meta', 'class' : 'object-meta-form') }}
    <div class="row">
        {{ hidden_field( 'objectId' , "value":   object.id   ) }}
        {{ hidden_field( 'objectType' , "value":   'post'     ) }}
        <div class="col-5">
         	{{ select("meta_key", objectType['metas'] ,  'useEmpty': true ,  'class': 'form-control' ) }}
     	</div>
     	<div class="col-5">
         	{{ text_area( 'meta_value' , 'class': 'form-control' ) }}
     	</div>
     	<div class="col-2">
     		<button href="#" class="btn btn-secondary">add</button>
     	</div>
 	</div>
    {{ endform() }}

          
     	  {% for meta in object.get_meta() %}
            {{ form(  'admin/' ~   controller | lower ~  '/add_meta', 'class' : 'object-meta-form') }}
            <table id="object-meta-table" class="table table-bordered mt-3">
     		<tr>
                <td>{{ meta['meta_key'] }}</td>
     			<td>{{ text_area( 'meta_value' , 'class': 'form-control' , 'value' : meta['meta_value']  ) }}</td>
                <td><button href="#" class="btn btn-secondary">update</button></td>
     			<td><a href="#" 
     				class="delete-meta-btn" 
     				data-object-id="{{ meta['meta_id'] }}"
     				data-object="postMeta"><i class="fas fa-trash"></i></a></td>
     		</tr>
            {{ hidden_field( 'objectId' , "value":   object.id   ) }}
            {{ hidden_field( 'meta_key' , "value":   meta['meta_key']   ) }}
            {{ hidden_field( 'metaId' , "value":   meta['meta_id']  ) }}
            {{ hidden_field( 'objectType' , "value":   'post'     ) }}
             </table>
            {{ endform() }}
       
     	{% endfor %}
  

</div>
