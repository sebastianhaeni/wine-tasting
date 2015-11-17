<?php

namespace WineTasting\Service;

use Silex\Application;

/**
 * Builds all the routes of the API.
 * Different URLs are delegated to the respective controller.
 * There are protected routes that can only be accessed when logged in.
 *
 * @author Sebastian Häni <haeni.sebastian@gmail.com>
 */
class Router
{

    /**
     * Set up routes of the API.
     *
     * @param \Silex\Application $app            
     */
    public function constructRoutes(Application $app)
    {
        $this->constructPublicRoutes($app);
    }

    /**
     * Builds the routes that can be accesses all the time without being logged in.
     *
     * @param Application $app            
     */
    private function constructPublicRoutes(Application $app)
    {
        // Home
        $app->get('/v1/', 'WineTasting\\Controller\\HomeController::info');
        
        // User
        $app->post('/v1/user/register', 'WineTasting\\Controller\\UserController::register');
        $app->get('/v1/user/{id}/votes', 'WineTasting\\Controller\\UserController::getVotes');
        
        // Wine
        $app->get('/v1/wine', 'WineTasting\\Controller\\WineController::getWines');
        $app->get('/v1/wine/ranking', 'WineTasting\\Controller\\WineController::getRankedWines');
        $app->get('/v1/wine/{id}', 'WineTasting\\Controller\\WineController::getWine');
        $app->post('/v1/wine', 'WineTasting\\Controller\\WineController::create');
        $app->post('/v1/wine/vote1', 'WineTasting\\Controller\\WineController::vote1');
        $app->post('/v1/wine/vote2', 'WineTasting\\Controller\\WineController::vote2');
        $app->post('/v1/wine/vote3', 'WineTasting\\Controller\\WineController::vote3');
    }

}
