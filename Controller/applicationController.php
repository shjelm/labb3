<?php

namespace controller;

require_once realpath(dirname(__DIR__))."/View/loginView.php";
require_once realpath(dirname(__DIR__))."/Controller/loginController.php";


class ApplicationController{


	private $LoginView;
	private $LoginController;
	
	public function __construct(){
		$this->LoginView = new \view\loginView();
		$this->LoginController = new \controller\loginController();
		
	}
	public function runApplication()
	{
		try{
			
			$this->LoginController->userWantsToLogin();
		}
		catch(Exception $ex)
		{
			echo "Something went wrong.";
		}
	}
}