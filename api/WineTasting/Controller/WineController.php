<?php

namespace WineTasting\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use WineTasting\Model\WineQuery;
use WineTasting\Model\Wine;
use WineTasting\Model\UserQuery;
use WineTasting\Util\SuccessResponse;
use WineTasting\Model\User;

/**
 * Provides information about wines.
 *
 * @author Sebastian Häni <haeni.sebastian@gmail.com>
 */
class WineController extends BaseController
{

    public function getWines(Request $request, Application $app)
    {
        $wines = WineQuery::create()->find();
        $dtos = [];

        foreach ($wines as $wine) {
            $dtos [] = $this->createWineDto($wine);
        }

        return $this->json($dtos);
    }

    private static function compareWine($a, $b)
    {
        if ($a ['points'] == $b ['points']) {
            return 0;
        }
        return ($a ['points'] > $b ['points']) ? -1 : 1;
    }

    public function getRankedWines(Request $request, Application $app)
    {
        $wines = WineQuery::create()->find();
        $dtos = [];

        foreach ($wines as $wine) {
            $dtos [] = $this->createWineDto($wine);
        }

        usort($dtos, [
            $this,
            'compareWine'
        ]);

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

        return $this->json($this->createWineDto((new WineQuery())->findPk($id)));
    }

    public function create(Request $request, Application $app)
    {
        $file = $request->files->get('picture');

        if ($file === null) {
            return $this->json([
                'status' => 'picture missing'
            ]);
        }

        $path = __DIR__ . '/../../../www/upload/';

        $originalFilename = $file->getClientOriginalName();
        $ext = strtolower(substr($originalFilename, strrpos($originalFilename, '.')));
        $filename = uniqid() . $ext;
        $file->move($path, $filename);

        // resizing
        self::resize($path . $filename, 680, 800);

        $username = $request->get('username');
        $user = UserQuery::create()->findOneByName($username);
        if ($user == null) {
            $user = new User();
            $user->setName($username);
            $user->save();
        }

        $wine = new Wine();
        $wine->setName($request->get('name'));
        $wine->setSubmitter($user->getIdUser());
        $wine->setYear($request->get('year'));
        $wine->setPicture($filename);
        $wine->save();

        return $this->json([
            'id' => $wine->getIdWine()
        ]);
    }

    public function vote1(Request $request, Application $app)
    {
        $idUser = $request->get('idUser');
        $idWine = $request->get('idWine');

        $user = UserQuery::create()->findOneByIdUser($idUser);
        $user->setVote1(empty($idWine) ? null : $idWine);
        $user->save();
        return new SuccessResponse();
    }

    public function vote2(Request $request, Application $app)
    {
        $idUser = $request->get('idUser');
        $idWine = $request->get('idWine');

        $user = UserQuery::create()->findOneByIdUser($idUser);
        $user->setVote2(empty($idWine) ? null : $idWine);
        $user->save();
        return new SuccessResponse();
    }

    public function vote3(Request $request, Application $app)
    {
        $idUser = $request->get('idUser');
        $idWine = $request->get('idWine');
        $user = UserQuery::create()->findOneByIdUser($idUser);
        $user->setVote3(empty($idWine) ? null : $idWine);
        $user->save();
        return new SuccessResponse();
    }

    private function createWineDto(Wine $wine)
    {
        $submitter = UserQuery::create()->findOneByIdUser($wine->getSubmitter());
        return [
            'id' => $wine->getIdWine(),
            'name' => $wine->getName(),
            'submitter' => $submitter->getName(),
            'points' => $this->calculatePoints($wine),
            'picture' => $wine->getPicture(),
            'year' => $wine->getYear()
        ];
    }

    private function calculatePoints(Wine $wine)
    {
        $vote3 = count(UserQuery::create()->findByVote3($wine->getIdWine()));
        $vote2 = count(UserQuery::create()->findByVote2($wine->getIdWine()));
        $vote1 = count(UserQuery::create()->findByVote1($wine->getIdWine()));
        return ($vote3 * 3) + ($vote2 * 2) + ($vote1);
    }

    static function resize($sourcePath, $newWidth = 100, $newHeight = 100)
    {
        // Create image resource from source path
        switch (exif_imagetype($sourcePath)) {
            case IMAGETYPE_JPEG :
                $sourceImage = imagecreatefromjpeg($sourcePath);
                break;
            case IMAGETYPE_PNG :
                $sourceImage = imagecreatefrompng($sourcePath);
                break;
            case IMAGETYPE_GIF :
                $sourceImage = imagecreatefromgif($sourcePath);
                break;
            default:
                return;
        }

        // Create the new image (still blank/empty)
        $newImage = imagecreatetruecolor($newWidth, $newHeight);

        // Determine the source image Dimensions
        $sourceImageWidth = imagesx($sourceImage);
        $sourceImageHeight = imagesy($sourceImage);
        $sourceImageAspectRatio = $sourceImageWidth / $sourceImageHeight;
        $newImageAspectRatio = $newWidth / $newHeight;

        // Determine parameters and copy part of the source image into the new image
        if ($newImageAspectRatio >= $sourceImageAspectRatio) { // width is the limiting factor for the source image
            $src_x = 0;
            $src_w = $sourceImageWidth;
            $src_h = $src_w / $newImageAspectRatio;
            $src_y = ($sourceImageHeight - $src_h) / 2;
        } else { // height of source image is limiting factor
            $src_y = 0;
            $src_h = $sourceImageHeight;
            $src_w = $src_h * $newImageAspectRatio;
            $src_x = ($sourceImageWidth - $src_w) / 2;
        }

        imagecopyresampled($newImage, $sourceImage, 0, 0, $src_x, $src_y, $newWidth, $newHeight, $src_w, $src_h);

        // Save new image to destination path
        switch (exif_imagetype($sourcePath)) {
            case IMAGETYPE_JPEG :
                imagejpeg($newImage, $sourcePath, 90);
                break;
            case IMAGETYPE_PNG :
                imagepng($newImage, $sourcePath);
                break;
            case IMAGETYPE_GIF :
                imagegif($newImage, $sourcePath);
                break;
        }

        // Remove image resources to reallocate space
        imagedestroy($sourceImage);
        imagedestroy($newImage);
    }

}
