<?php
namespace Components\Model;

use Components\Model\Traits\Timestampable;
use Components\Model\Traits\SoftDeletable;

class FailedLogins extends Model
{
    use Timestampable;
    use SoftDeletable;

    public function getSource()
    {
        return 'failed_logins';
    }

    public function initialize()
    {
        $this->belongsTo('user_id', Users::class, 'id', ['alias' => 'user', 'reusable' => true]);
    }
}