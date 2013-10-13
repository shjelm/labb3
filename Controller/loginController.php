<?php

namespace controller;

require_once 'view/loginView.php';
require_once 'view/HTMLPage.php';
require_once 'model/loginModel.php';

class loginController{
	
	private $loginView;
	
	private $HTMLPage;
	
	private $loginModel;
	
	private $username;
	
	private $password;
	
	private $messageNr;
	
	private $message;
	
	private $session;
	
	private $browser; 
	
	private $cookies;
	
	private $cookiesOk;
	
	public function __construct()
	{
		$this->loginView = new \view\LoginView();
		$this->loginModel = new \model\LoginModel();
		$this->HTMLPage = new \view\HTMLPage();
	}
	
	public function userWantsToLogin()
	{
		$this->username = $this->loginView->getUsername();
		$this->password =  $this->loginView->getPassword();
		
		$this->session = self::stayLoggedin();
		$this->messageNr = self::logOut();
		
		$this->messageNr = $this->loginModel->checkCookies($this->cookies);
		
		$this->messageNr = $this->loginModel->checkLogin($this->username, $this->password);
		
		$this->message = $this->loginView->setMessage($this->messageNr);
		
		$this->browser = $this->loginModel->checkBrowser();
		
		$this->cookies = $this->loginView->cookiesSet();
			
		self::loginCookies();	
		self::showPage();
	}
	
	public function showPage()
	{				
		if($this->messageNr == 5)
		{
			$this->HTMLPage->getLogOutPage($this->message);
		}
		
		if($this->cookies == true && $this->session != true)
		{	//@TODO: Fixa meddelande till inloggning med cookies
			$this->HTMLPage->getLoggedInPage($this->message);
		}		
		else if($this->browser != true)
		{
			$this->HTMLPage->getPage($this->message);
		}		
		else if($this->messageNr == 1)
		{
			$this->HTMLPage->getLoggedInPage($this->message);
		}						
		else if($this->session == true)
		{
			$this->HTMLPage->getLoggedInPage('');
		}		
		else 
		{	
			$this->HTMLPage->getPage($this->message);
		}	
	}
	
	public function logOut()
	{
		$checkToLogout = $this->loginView->checkLogout();
		return ($this->loginModel->checkLogout($checkToLogout));
	}
	
	public function stayLoggedin()
	{
		return $this->loginModel->checkSession();
	}
	
	public function loginCookies()
	{
		$autoLogin = $this->loginView->checkAutoLogin();
		
		if($autoLogin == true){
		$this->loginView->autoLogin($this->username, $this->password);
		}
	}
}
