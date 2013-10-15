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
	
	/**
	 * @var bool
	 */
	private $post;
	
	/**
	 * @var bool
	 */
	private $autoLogin;
	
	/**
	 * @var string
	 */
	private $cryptedPassword; 
	
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
		
		$this->post = $this->loginView->checkPost();
		
		self::logOut();
		
		self::loginCookies();	
		if(self::logOut() == false){
			var_dump($this->session);			
				if(self::validCookie() == false && $this->session)
				{
					$this->messageNr = 123123123;
				}	
			if(self::validCookie() == false && $this->session == false)
			{
				$this->messageNr = $this->loginModel->validCookieMsg();
			}
			if ($this->cookies && self::validCookie() && $this->session == false){
				$this->messageNr = $this->loginModel->setMsgCookies($this->cookies);							
			}			
			if ($this->cookies == false && $this->session == false && $this->post){
				
				$this->messageNr = $this->loginModel->checkMessageNr($this->username, $this->password);
			}
		}
		var_dump($this->messageNr);
		$this->message = $this->loginView->setMessage($this->messageNr);
		
		$this->browser = $this->loginModel->checkBrowser();
		
		self::showPage();
	}
	
	public function showPage()
	{	
		if(self::logOut())
		{
			$this->HTMLPage->getLogOutPage($this->message);				
			
		}
		if($this->loginView->cookiesSet() && $this->session != true && self::validCookie())
		{
			$this->HTMLPage->getLoggedInPage($this->message);
			
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
			$this->loginView->unsetCookies();
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
		if($autoLogin && self::logIn()){
			
			$this->loginModel->saveEndTime();
			$endTime = $this->loginModel->getEndTime();
		
			$this->loginView->autoLogin($this->username, $this->password, $endTime);
			
			$pass = $this->loginView->getCryptedPassword();		
		}
	}
	
	/**
	 * @return bool
	 */	
	public function logIn()
	{
		return $this->loginModel->checkLogin($this->username, $this->password);
	}
	
	/**
	 * @return bool
	 */	
	public function validCookie()
	{
		$endTime = $this->loginModel->getEndTime();
		if($this->loginView->validCookies($this->loginModel->getUser(), $endTime)){
			return true;
		}
		else {
			return false;
		}
	}
}
