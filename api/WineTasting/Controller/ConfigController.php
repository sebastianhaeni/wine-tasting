<?php

namespace WineTasting\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use WineTasting\Model\User;
use WineTasting\Model\UserQuery;
use Symfony\Component\HttpFoundation\Response;
use WineTasting\Model\ConfigQuery;
use WineTasting\Model\Config;
use WineTasting\Util\SuccessResponse;

class ConfigController extends BaseController {
	public function getValue($name, Application $app) {
		$config = ConfigQuery::create ()->findOneByName ( $name );
		
		if ($config === null) {
			$config = new Config ();
			$config->setName ( $name );
			$config->save ();
		}
		
		return $this->json ( $config->getValue () );
	}
	public function setValue(Application $app, Request $request, $name) {
		$config = ConfigQuery::create ()->findOneByName ( $name );
		
		if ($config === null) {
			$config = new Config ();
			$config->setName ( $name );
		}
		
		$config->setValue ( $request->get ( 'value' ) );
		
		$config->save ();
		
		return new SuccessResponse();
	}
}
