{{ form(  'admin/' ~   controller | lower ~  '/' ~  object.id  ~ '/add_meta', 'id' : 'object-meta-form') }}
  <div class="form-group p-3 pb-5 bg-light card">
        <strong>Meta</strong>
        <div class="row">
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

     	<table id="object-meta-table" class="table table-bordered mt-3">
     	{% for meta in object.get_meta() %}
     		<tr><td>{{ meta['meta_key'] }}</td>
     			<td>{{ meta['meta_value'] }}</td>
     			<td><a href="#" 
     				class="delete-meta-btn" 
     				data-object-id="{{ meta['meta_id'] }}"
     				data-object="postMeta"><i class="fas fa-trash"></i></a></td>
     		</tr>
     	{% endfor %}
     	</table>

    </div>
{{ endform() }}