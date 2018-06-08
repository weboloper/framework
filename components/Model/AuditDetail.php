<?php
namespace Components\Model;

use Components\Model\Traits\Timestampable;
use Components\Model\Traits\SoftDeletable;

class AuditDetail extends Model
{
    use Timestampable;
    use SoftDeletable;

    public function getSource()
    {
        return 'audit_detail';
    }

    public function initialize()
    {
        $this->belongsTo('audit_id', Audit::class, 'id'  );
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setAuditId($id)
    {
        $this->id = $audit_id;
        return $this;
    }

    public function getAuditId()
    {
        return $this->audit_id;
    }
    public function setFieldName($field_name)
    {
        $this->field_name = $field_name;
        return $this;
    }

    public function getFieldName()
    {
        return $this->field_name;
    }
    public function setOldValue($old_value)
    {
        $this->old_value = $old_value;
        return $this;
    }

    public function getOldValue()
    {
        return $this->old_value;
    }
    public function setNewValue($new_value)
    {
        $this->new_value = $new_value;
        return $this;
    }

    public function getNewValue()
    {
        return $this->new_value;
    }

}