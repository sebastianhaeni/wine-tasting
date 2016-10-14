<?php
namespace WineTasting\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Base controller class.
 * Every controller that handles routes has to inherit from this class.
 *
 * @author Sebastian Häni <haeni.sebastian@gmail.com>
 */
class BaseController
{

    /**
     * @param $data
     * @return JsonResponse
     */
    protected function json($data)
    {
        $response = new JsonResponse($data);

        if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
            $response->setEncodingOptions($response->getEncodingOptions() | JSON_PRETTY_PRINT);
        }

        return $response;
    }
}
