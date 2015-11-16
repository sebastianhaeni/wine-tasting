<?php
namespace WineTasting\Controller;

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;

/**
 * Provides information about this API.
 *
 * @author Sebastian Häni <haeni.sebastian@gmail.com>
 */
class HomeController extends BaseController
{

    /**
     * Standard 'It works' response.
     *
     * @param Request $request
     * @param Application $app
     */
    public function info(Request $request, Application $app)
    {
        return $this->json(array(
            'date' => date('c'),
            'author' => 'Sebastian Häni',
            'version' => '1.0'
        ));
    }
}
