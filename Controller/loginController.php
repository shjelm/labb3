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
	 * @var string
	 */
	private $cryptedPassword; 
	
	/**
	 * 
	 * @param view\LoginView     $loginView    
	 * @param view\HTMLPage      $HTMLPage            
	 * @param model\LoginModel   $loginModel  
	 */
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
			
			$this->messageNr = $this->loginModel->checkMessageNr($this->username, $this->password);
			
			if(self::loginCookies() && $this->session!=true){
				$this->messageNr = $this->loginModel->setMsgCookies($this->cookies);
			}
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
		var_dump(self::loginWithCookies());
		if(self::loginWithCookies() && $this->session != true)
		{	//@TODO: Fixa meddelande till inloggning med cookies
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
			$this->loginView->autoLogin($this->username, $this->password);
		}
	}
	 //@TODO: tänker jag rätt här? anv.namn och lösen ska kollas, stämmer det så retunera true
	public function logIn()
	{
		return $this->loginModel->checkLogin($this->username, $this->password);
	}
	
	//@TODO: hämta ut lösen från fil, anropa och jämföra, retunera true om cookien är valid
	public function loginWithCookies()		
	{
		$this->cryptedPassword = $this->loginView->getCryptedPassword();
		var_dump($this->cryptedPassword);
		return $this->loginView->validCookies($this->username, $this->cryptedPassword);
		
	}
}
