<?php
namespace Components\Model;

use Components\Model\Traits\Timestampable;
use Components\Model\Traits\SoftDeletable;

class Audit extends Model
{
    use Timestampable;
    use SoftDeletable;

    public function getSource()
    {
        return 'audit';
    }

    public function initialize()
    {
        $this->hasMany('id', AuditDetail::class, 'audit_id', ['alias' => 'details', 'reusable' => true]);
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
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
        return $this;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function setIpaddress($ipaddress)
    {
        $this->ipaddress = $ipaddress;
        return $this;
    }

    public function getIpaddress()
    {
        return $this->ipaddress;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setModelName($model_name)
    {
        $this->model_name = $model_name;
        return $this;
    }

    public function getModelName()
    {
        return $this->model_name;
    }

}