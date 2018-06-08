<?php
namespace Components\Model;

use Components\Model\Traits\Timestampable;
use Components\Model\Traits\SoftDeletable;

class Access extends Model
{
    use Timestampable;
    use SoftDeletable;

    public function getSource()
    {
        return 'access';
    }

     /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $object;

    /**
     * @var string
     */
    protected $action;

    /**
     * @var int
     */
    protected $roleId;

    /**
     * @var string
     */
    protected $value;

    public function initialize()
    {
        $this->belongsTo('role_id', Roles::class, 'id', ['alias' => 'role', 'reusable' => true]);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @param string $object
     */
    public function setObject($object)
    {
        $this->object = $object;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param string $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * @return int
     */
    public function getRoleId()
    {
        return $this->role_id;
    }

    /**
     * @param int $roleId
     */
    public function setRoleId($roleId)
    {
        $this->role_id = $roleId;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }


}