<?php
namespace Components\Model;

// This is extending incubator\blameble
// session kullanıcı adı ve ip adresi orjinalde çalışmıyordu. user_name user id ile değiştirildi

use Phalcon\Mvc\Model\Behavior\Blameable\Audit as BaseAudit;

class Audit extends BaseAudit
{
    public $user_id;
    public $user_name;

     /**
     * Executes code to set audits all needed data, like ipaddress, username, created_at etc
     */
    public function beforeValidation()
    {
        if (empty($this->userCallback)) {
            //Get the username from session
            $this->user_name = auth()->getUserId();
        } else {
            $userCallback = $this->userCallback;
            $this->user_name = $userCallback($this->getDI());
        }
        //The model who performed the action
        $this->model_name = get_class($this->model);
        /** @var Request $request */
        $request = $this->getDI()->get('request');
        //The client IP address
        $this->ipaddress = ip2long(request()->getClientAddress());
        //Current time
        $this->created_at = date('Y-m-d H:i:s');
        $primaryKeys = $this->getModelsMetaData()->getPrimaryKeyAttributes($this->model);
        $columnMap = $this->getModelsMetaData()->getColumnMap($this->model);
        $primaryValues = [];
        if (!empty($columnMap)) {
            foreach ($primaryKeys as $primaryKey) {
                $primaryValues[] = $this->model->readAttribute($columnMap[$primaryKey]);
            }
        } else {
            foreach ($primaryKeys as $primaryKey) {
                $primaryValues[] = $this->model->readAttribute($primaryKey);
            }
        }
        $this->primary_key = json_encode($primaryValues);
    }

}