<?php
namespace controller;

require_once("view/loginView.php");
require_once ("controller/loginController.php");


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