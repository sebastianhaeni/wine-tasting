<?php

namespace WineTasting\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use WineTasting\Model\User;
use WineTasting\Model\UserQuery;

/**
 * Registers users.
 *
 * @author Sebastian Häni <haeni.sebastian@gmail.com>
 */
class UserController extends BaseController
{

    /**
     *
     * @param Request $request            
     * @param Application $app            
     */
    public function register(Request $request, Application $app)
    {
        $name = $request->get('name');
        $existingUser = UserQuery::create()->findByName($name);
        if (count($existingUser) > 0) {
            return $this->json([ 
                'id' => $existingUser [0]->getIdUser() 
            ]);
        }
        
        $user = new User();
        $user->setName($name);
        
        $user->save();
        return $this->json([ 
            'id' => $user->getIdUser() 
        ]);
    }

}
