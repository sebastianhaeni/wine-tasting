<?php

namespace WineTasting\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use WineTasting\Model\User;
use WineTasting\Model\UserQuery;
use Symfony\Component\HttpFoundation\Response;

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
    
    public function getVotes($id, Application $app) {
        $user = UserQuery::create()->findOneByIdUser($id);
        
        if($user === null){
            return new Response('Error', 404);
        }
        
        $dto = [
            'vote1' => $user->getVote1(),
            'vote2' => $user->getVote2(),
            'vote3' => $user->getVote3()
        ];
        
        return $this->json($dto);
    }

}
