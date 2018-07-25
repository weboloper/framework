<div class="row">
<div class="col-sm-8">
<div class="bg-light  border">
<table class="table">
    <tbody>
         {% for meta,name  in objectType['metas']  %}

            {{ form( 'id' : 'meta-form-' ~ meta ,   'admin/' ~   controller | lower ~  '/add_meta', 'class' : 'object-meta-form') }}{{ endform() }}
            <tr>
                <td>{{  name }}</td>
                <td>{{ text_area( 'meta_value' , 'class': 'form-control' , 'value' : object.get_meta(meta)  , 'form' : 'meta-form-' ~ meta  ) }}</td>
                <td width="10%"><button href="#" class="btn btn-secondary add_meta_btn" form='meta-form-{{meta}}' value="save">Save</button></td>
                <td width="10%"><button href="#" class="btn btn-danger add_meta_btn" form='meta-form-{{meta}}' value="delete">Update</button></td>
     
            </tr>
            {{ hidden_field( 'objectId' , "value":   object.id , 'form' : 'meta-form-' ~meta  ) }}
            {{ hidden_field( 'meta_key' , "value":   meta , 'form' : 'meta-form-' ~ meta  ) }}
            {{ hidden_field( 'metaId' , "value":  meta , 'form' : 'meta-form-' ~ meta ) }}
            {{ hidden_field( 'objectType' , "value":   'post'    , 'form' : 'meta-form-' ~ meta )}}
            {{ endform() }}
       
        {% endfor %}

 
    </tbody>
</table>
</div>
</div>
</div>