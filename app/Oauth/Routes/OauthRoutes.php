<?php

namespace App\Oauth\Routes;

class OauthRoutes extends RouteGroup
{
    public function initialize()
    {
        $this->setPaths([
            'controller' => 'Oauth',
        ]);

        $this->setPrefix('/oauth');

        $this->addGet('/login/:params', [
            'action' => 'showLoginForm',
            'params' => 1,
        ])->setName('showLoginForm');

        $this->addPost('/attempt', [
            'action' => 'attemptToLogin',
        ])->setName('attemptToLogin');

        $this->addGet('/logout', [
            'action' => 'logout',
        ])->setName('logout');

        $this->addGet('/register', [
            'action' => 'showRegistrationForm',
        ])->setName('showRegistrationForm');

        $this->addPost('/register/store', [
            'action' => 'storeRegistrationForm',
        ])->setName('storeRegistrationForm');

        $this->addGet('/activation/{token}', [
            'action' => 'activateUser',
        ])->setName('activateUser');

        $this->add('/forget-password', [
            'action' => 'showForgetPasswordForm',
        ])->setName('showForgetPasswordForm');

        $this->add('/reset-password/{token}/{id}', [
            'action' => 'showResetPasswordForm',
        ])->setName('showResetPasswordForm');


        // facebook
        $this->add('/facebook/access_token', [
            'action'     => 'tokenFacebook'
        ]);

        $this->addGet('/login/facebook',   [
            'action'     => 'loginFacebook'
        ])
        ->setName('loginFacebook');



        $this->add('/islogged', [
             'action'     => 'isLogged'
        ]);

        $this->add('/test', [
             'action'     => 'test'
        ]);

    }

}
