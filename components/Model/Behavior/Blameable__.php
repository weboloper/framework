<?php
 
namespace Components\Model\Behavior;

use Components\Model\Audit;
use Phalcon\Security\Random;
use Phalcon\Mvc\ModelInterface;
use Phalcon\Mvc\Model\Behavior;
use Components\Model\AuditDetail;
use Phalcon\Mvc\Model\BehaviorInterface;
 

class Blameable extends Behavior implements BehaviorInterface
{	
	public function __construct($options = null)
    {
        parent::__construct($options);
    }
	public function notify($eventType, ModelInterface $model)
    {	
    	 
        // Fires 'logAfterUpdate' if the event is 'afterCreate'
        if ($eventType == 'afterCreate') {
            return $this->auditAfterCreate($model);
        }
        // Fires 'logAfterUpdate' if the event is 'afterUpdate'
        if ($eventType == 'afterUpdate') {
            return $this->auditAfterUpdate($model);
        }
        

        return true;
    }

	public function createAudit($type, ModelInterface $model)
    {	

        // Skip on console mode
        if (PHP_SAPI == 'cli') {
            return null;
        }
        // Get the session service
        if (session()->has('isAuthenticated')) {
            return null;
        }

        if (auth()->isAuthorizedVisitor()) {
            return null;
        }

        $audit  = new Audit();
        $audit->setUserId(auth()->getUserId());
        $audit->setModelName(get_class($model));
        $audit->setIpaddress(ip2long(request()->getClientAddress()));
        $audit->setType($type);
        $audit->setCreatedAt(date('Y-m-d H:i:s'));
        return $audit;
    }

    public function auditAfterCreate(ModelInterface $model)
    {
        // Create a new audit
        if (!$audit = $this->createAudit('C', $model)) {
            return false;
        }
        $metaData = $model->getModelsMetaData();
        $fields   = $metaData->getAttributes($model);
        $details  = [];

        // Ignore audit log posts when it create
        if ($model->getSource() == 'posts') {
            foreach ($fields as $field) {
                $auditDetail = new AuditDetail();
                $auditDetail->setFieldName($field);
                $auditDetail->setOldValue(null);
                $newValue = $model->readAttribute($field) ?: 'empty';
                $auditDetail->setNewValue($newValue);
                $details[] = $auditDetail;
            }
            $audit->details = $details;


            // @todo: Move this to a common place
            if (!$audit->save()) {

                // if ($this->getDI()->has('logger')) {
                    $messages = [];
                    foreach ($audit->getMessages() as $message) {
                        $messages[] = (string) $message;
                    }
                    // $this->getDI()->getShared('logger')->error(implode('; ', $messages));
                    // return false;
                // }
            }
        }
        return true;
    }

    public function auditAfterUpdate(ModelInterface $model)
    {
        $changedFields = $model->getChangedFields();
        if (!count($changedFields)) {
            return false;
        }
        //Create a new audit
        $audit = $this->createAudit('U', $model);
        if (is_object($audit)) {
            //Date the model had before modifications
            $originalData = $model->getSnapshotData();
            $details = [];
            foreach ($changedFields as $field) {
                $auditDetail = new AuditDetail();
                $auditDetail->setFieldName($field);
                $auditDetail->setOldValue($originalData[$field]);
                $newValue = $model->readAttribute($field) ? : 'empty';
                $auditDetail->setNewValue($newValue);
                $details[] = $auditDetail;
            }
            $audit->details = $details;
            // @todo: Move this to a common place
            if (!$audit->save()) {
                // if ($this->getDI()->has('logger')) {
                //     $messages = [];
                //     foreach ($audit->getMessages() as $message) {
                //         $messages[] = (string) $message;
                //     }
                //     $this->getDI()->getShared('logger')->error(implode('; ', $messages));
                //     return false;
                // }
            }
        }
        return true;
    }

}