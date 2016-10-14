<?php

namespace WineTasting\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use WineTasting\Model\User;
use WineTasting\Model\UserQuery;
use Symfony\Component\HttpFoundation\Response;
use WineTasting\Model\WineQuery;
use WineTasting\Util\SuccessResponse;

/**
 * Registers users.
 *
 * @author Sebastian Häni <haeni.sebastian@gmail.com>
 */
class UserController extends BaseController
{

    /**
     * @param Request $request
     * @param Application $app
     * @return \Symfony\Component\HttpFoundation\JsonResponse
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

    public function getVotes($id, Application $app)
    {
        $user = UserQuery::create()->findOneByIdUser($id);

        if ($user === null) {
            return new Response('Error', 404);
        }

        $dto = [
            'vote1' => $user->getVote1(),
            'vote2' => $user->getVote2(),
            'vote3' => $user->getVote3()
        ];

        return $this->json($dto);
    }

    public function getTasted($id, Application $app)
    {
        $user = UserQuery::create()->findOneByIdUser($id);

        if ($user === null) {
            return new Response('Error', 404);
        }

        $list = [];

        foreach ($user->getTastedWines() as $wine) {
            $list[] = $wine->getIdWine();
        }

        return $this->json($list);
    }

    public function addTasted($id, $idWine, Application $app)
    {
        $user = UserQuery::create()->findOneByIdUser($id);
        if ($user === null) {
            return new Response('Error', 404);
        }

        $wine = WineQuery::create()->findOneByIdWine($idWine);
        if ($wine === null) {
            return new Response('Error', 404);
        }

        $user->addWine($wine);
        $user->save();

        return new SuccessResponse();
    }

    public function removeTasted($id, $idWine, Application $app)
    {
        $user = UserQuery::create()->findOneByIdUser($id);
        if ($user === null) {
            return new Response('Error', 404);
        }

        $wine = WineQuery::create()->findOneByIdWine($idWine);
        if ($wine === null) {
            return new Response('Error', 404);
        }

        $user->removeWine($wine);
        $user->save();

        return new SuccessResponse();
    }

}
