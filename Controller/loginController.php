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
	private $mySession;
	
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
	
	/**
	 * @var bool
	 */
	private $browserSession;
	
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
		
		$this->mySession = self::stayLoggedin();
		$this->browserSession = $this->loginModel->checkBrowserSession();
		
		$this->cookies = $this->loginView->cookiesSet();
		
		$this->post = $this->loginView->checkPost();
		
		$this->loginModel->getBrowser();
		var_dump($this->loginModel->getBrowser());
		$this->browser = $this->loginModel->checkBrowser();
		var_dump($this->browser);
		
		self::logOut();
		
		self::loginCookies();	
		if(self::logOut() == false){			
			
			if(self::validCookie() == false && $this->mySession == false && $this->browserSession == false)
			{
				$this->messageNr = $this->loginModel->validCookieMsg();
			}
			else
			{
				$this->messageNr = $this->loginModel->noMsg();
			}	
			if ($this->cookies && self::validCookie() && $this->mySession == false){
				$this->messageNr = $this->loginModel->setMsgCookies($this->cookies);							
			}			
			if ($this->cookies == false && $this->mySession == false && $this->post){
				
				$this->messageNr = $this->loginModel->checkMessageNr($this->username, $this->password);
			}
		}
		var_dump($this->messageNr);
		
		$this->message = $this->loginView->setMessage($this->messageNr);
		
		self::showPage();
	}
	
	public function showPage()
	{	
		if(self::logOut())
		{
			$this->HTMLPage->getLogOutPage($this->message);				
			
		}
		if($this->loginView->cookiesSet() && $this->mySession != true && self::validCookie())
		{
			$this->HTMLPage->getLoggedInPage($this->message);
			
		}		
		/**else if($this->browser != true)
		{
			$this->HTMLPage->getPage($this->message);
		}*/		
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
