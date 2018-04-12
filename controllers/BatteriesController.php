<?php

namespace Grocy\Controllers;

use \Grocy\Services\BatteriesService;

class BatteriesController extends BaseController
{
	public function __construct(\Slim\Container $container)
	{
		parent::__construct($container);
		$this->BatteriesService = new BatteriesService();
	}

	protected $BatteriesService;

	public function Overview(\Slim\Http\Request $request, \Slim\Http\Response $response, array $args)
	{
		$nextChargeTimes = array();
		foreach($this->Database->batteries() as $battery)
		{
			$nextChargeTimes[$battery->id] = $this->BatteriesService->GetNextChargeTime($battery->id);
		}

		return $this->AppContainer->view->render($response, 'batteriesoverview', [
			'batteries' => $this->Database->batteries(),
			'current' => $this->BatteriesService->GetCurrent(),
			'nextChargeTimes' => $nextChargeTimes
		]);
	}

	public function TrackChargeCycle(\Slim\Http\Request $request, \Slim\Http\Response $response, array $args)
	{
		return $this->AppContainer->view->render($response, 'batterytracking', [
			'batteries' =>  $this->Database->batteries()
		]);
	}

	public function BatteriesList(\Slim\Http\Request $request, \Slim\Http\Response $response, array $args)
	{
		return $this->AppContainer->view->render($response, 'batteries', [
			'batteries' => $this->Database->batteries()
		]);
	}

	public function BatteryEditForm(\Slim\Http\Request $request, \Slim\Http\Response $response, array $args)
	{
		if ($args['batteryId'] == 'new')
		{
			return $this->AppContainer->view->render($response, 'batteryform', [
				'mode' => 'create'
			]);
		}
		else
		{
			return $this->AppContainer->view->render($response, 'batteryform', [
				'battery' =>  $this->Database->batteries($args['batteryId']),
				'mode' => 'edit'
			]);
		}
	}
}
