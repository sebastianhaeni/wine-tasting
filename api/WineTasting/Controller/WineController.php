<?php

namespace WineTasting\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use WineTasting\Model\WineQuery;
use WineTasting\Model\Wine;
use WineTasting\Model\UserQuery;
use WineTasting\Util\SuccessResponse;

/**
 * Provides information about wines.
 *
 * @author Sebastian Häni <haeni.sebastian@gmail.com>
 */
class WineController extends BaseController
{

    public function getWines(Request $request, Application $app)
    {
        $wines = WineQuery::create()->orderByName()->find();
        $dtos = [ ];
        
        foreach ($wines as $wine) {
            $dts [] = $this->createWineDto($wine);
        }
        
        return $this->json($dtos);
    }

    public function getWine(Application $app, $id)
    {
        $wine = (new WineQuery())->findPk($id);
        
        if ($wine === null) {
            return $this->json([ 
                'status' => 'not found' 
            ]);
        }
        
        return $this->createWineDto((new WineQuery())->findPk($id));
    }

    public function create(Request $request, Application $app)
    {
        $file = $request->files->get('picture');
        
        if ($file === null) {
            return $this->json([ 
                'status' => 'picture missing' 
            ]);
        }
        
        $path = __DIR__ . '/../../../upload/';
        
        $originalFilename = $file->getClientOriginalName();
        $ext = strtolower(substr($originalFilename, strrpos($originalFilename, '.')));
        $filename = uniqid() . $ext;
        $file->move($path, $filename);
        
        $wine = new Wine();
        $wine->setName($request->get('name'));
        $wine->setSubmitter($request->get('idUser'));
        $wine->setYear($request->get('year'));
        $wine->setPicture($filename);
        $wine->save();
        
        return new SuccessResponse();
    }

    public function vote1(Request $request, Application $app)
    {
        $idUser = $request->get('idUser');
        $idWine = $request->get('idWine');

        $user = UserQuery::create()->findOneByIdUser($idUser);
        $user->setVote1($idWine);
        $user->save();
        return new SuccessResponse();
    }

    public function vote2(Request $request, Application $app)
    {
        $idUser = $request->get('idUser');
        $idWine = $request->get('idWine');
        
        $user = UserQuery::create()->findOneByIdUser($idUser);
        $user->setVote2($idWine);
        $user->save();
        return new SuccessResponse();
    }

    public function vote3(Request $request, Application $app)
    {
        $idUser = $request->get('idUser');
        $idWine = $request->get('idWine');
        
        $user = UserQuery::create()->findOneByIdUser($idUser);
        $user->setVote3($idWine);
        $user->save();
        return new SuccessResponse();
    }

    private function createWineDto(Wine $wine)
    {
        return [ 
            'name' => $wine->getName(),
            'submitter' => $wine->getSubmitter()->getName(),
            'points' => $this->calculatePoints($wine),
            'picture' => $wine->getPicture() 
        ];
    }

    private function calculatePoints(Wine $wine)
    {
        // TODO
        return 5;
    }

}

