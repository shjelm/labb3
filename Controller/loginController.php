<?php

namespace controller;

require_once realpath(dirname(__DIR__)).'/View/loginView.php';
require_once realpath(dirname(__DIR__)).'/View/HTMLPage.php';
require_once realpath(dirname(__DIR__)).'/Model/loginModel.php';

class loginController{
	/**
	 * @var \view\loginView
	 */
	private $loginView;
	
	/**
	 * @var \view\HTMLPage
	 */
	private $HTMLPage;
	
	/**
	 * @var \model\loginModel
	 */
	private $loginModel;
	
	/**
	 * @var string
	 */
	private $username;
	
	/**
	 * @var string
	 */
	private $password;
	
	/**
	 * @var int
	 */
	private $messageNr;
	
	/**
	 * @var string
	 */
	private $message;
	
	/**
	 * @var bool
	 */
	private $session;
	
	/**
	 * @var string
	 */
	private $browser; 
	
	/**
	 * @var bool
	 */
	private $cookies;
	
	/**
	 * @var bool
	 */
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
		
		$this->cookies = $this->loginView->cookiesSet();
		
		self::logOut();
		if(self::logOut() == false){
			$this->messageNr = $this->loginModel->checkCookies($this->cookies);
			
			$this->messageNr = $this->loginModel->checkMessageNr($this->username, $this->password);
		}
		
		$this->message = $this->loginView->setMessage($this->messageNr);
		
		$this->browser = $this->loginModel->checkBrowser();
		
		self::loginCookies();	
		self::showPage();
	}
	
	public function showPage()
	{			
		if(self::logOut())
		{
			$this->HTMLPage->getLogOutPage($this->message);
		}
		
		if($this->loginView->cookiesSet() && $this->session != true)
		{	//@TODO: Fixa meddelande till inloggning med cookies
			$this->HTMLPage->getLoggedInPage("cookie");
		}		
		else if($this->browser != true)
		{
			$this->HTMLPage->getPage($this->message);
		}		
		else if(self::logIn())
		{
			$this->HTMLPage->getLoggedInPage($this->message);
		}						
		else if(self::stayLoggedin())
		{
			$this->HTMLPage->getLoggedInPage($this->message);
		}		
		else 
		{	
			$this->HTMLPage->getPage($this->message);
		}	
	}
	
	/**
	 * @return bool
	 */
	public function logOut()
	{
		$checkToLogout = $this->loginView->checkLogout();
		
		$this->messageNr = $this->loginModel->setLogout($checkToLogout);
		
		return ($this->loginModel->checkLogout($checkToLogout));
	}
	
	/**
	 * @return bool
	 */
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
	
	public function logIn()
	{
		return $this->loginModel->checkLogin($this->username, $this->password);
	}
}
