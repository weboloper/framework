<?php

namespace Components\Model\Services\Service;

use Components\Model\FailedLogins;
use Phalcon\Db\Column;

class FailedLogin extends \Components\Model\Services\Service
{
	const FROM_TIME_FETCH = 21600;
	
	public function create(array $data)
	{	
        $ipaddress  = ip2long($data['ipaddress']);
        $user_id  	= empty($data['user_id']) ? null : $data['user_id'];
        $time    	= time();

     
        $entity = new FailedLogins();
        $entity->ipaddress = $ipaddress;
		$entity->user_id =  $user_id;
		$entity->attempted = $time;

		if ($entity->save() === false) {
		    throw new EntityException(
                $entity,
                'There is an unknown error. Please try again later'
            );
		}  

        $attempts = $this->countAttempts($ipaddress, $time - self::FROM_TIME_FETCH);
        $this->throttling($attempts);
        return false;
    
	}
 


	public function countAttempts($ipaddress, $fromAttemptedTime)
    {
        return (int) FailedLogins::count([
            'condition' => 'ipaddress = :ipaddress: AND attempted >= :attempted:',
            'bind' => [
                'ipaddress'   => $ipaddress,
                'attempted' => $fromAttemptedTime,
            ],
            'bindTypes' => [
                'ipaddress'   => Column::BIND_PARAM_INT,
                'attempted' => Column::BIND_PARAM_INT,
            ],
        ]);
    }


 
    protected function throttling($attempts)
    {
        switch ($attempts) {
            case 1:
            case 2:
                break;
            case 3:
            case 4:
                sleep(2);
                break;
            default:
                sleep(4);
                break;
        }
    }


}
