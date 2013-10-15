<?php

namespace view;

class loginView{
	/**
	 * @var string
	 */
	private static $username = "UserName";
	
	/**
	 * @var string
	 */
	private static $password = "Password";
	
	/**
	 * @var string
	 */
	private static $logOut = "logout";
	
	/**
	 * @var string
	 */
	private static $autoLogin = "AutoLogin";
	
	/**
	 * @var string
	 */
	private $cryptedPassword = "";
	
	/**
	 * @var int
	 */
	private static $endtme;
	
	
	
	/**
	 * @return string
	 */
	public function getUsername(){
		if($_POST || $_GET){
			if(isset($_POST[self::$username])){
				$username = $_POST[self::$username];
				
				return $username;
			}
		}
	}
	
	/**
	 * @return string
	 */
	public function getPassword()
	{
		if($_POST || $_GET){
			if(isset($_POST[self::$password])){	
				$password = $_POST[self::$password];
			
				return $password;
			}
		}
	}
	
	/**
	 * @return string
	 */
	public function setMessage($messageNr)
	{
		if($_GET){
			switch ($messageNr) {
				case 1:
					$this->messageString = '<p>Inloggningen lyckades</p>';				
					break;
					
				case 2: 
					$this->messageString = '<p>Användarnamn saknas</p>';
					break;
	
				case 3: 
					$this->messageString = '<p>Lösenord saknas</p>';
					break;		
					
				case 4:
					$this->messageString = '<p>Felaktigt användarnamn och/eller lösenord</p>';	
					break;
					
				case 5:
					$this->messageString = '<p>Du har nu loggat ut</p>';	
					break;
				
				case 6:
					$this->messageString = '<p>Inloggning med cookies</p>';	
					break;
					
				case 7:
					$this->messageString = '<p>Inloggningen lyckades och vi kommer ihåg dig nästa gång</p>';				
					break;
				
				default:
					$this->messageString = '';
			}
			return $this->messageString;
		}
	}
	
	public function checkPost()
	{
		if($_POST){
			return true;
		}
	}
	
	/**
	 * @return bool
	 */
	public function checkLogout(){
		if($_POST){
			if (isset($_GET[self::$logOut])){
				self::unsetCookies();
				return true;
			}
			else 
			{
				return false;
			}
		}
	}
	
	/**
	 * @return bool
	 */
	public function checkAutologin()
	{
		if($_POST){
			if(isset($_POST[self::$autoLogin])){
				return true;
			}
			else {
				return false;
			}
		}
	}
	
	public function unsetCookies()
	{
		setcookie(self::$username, "",time()-3600);
		setcookie(self::$password, "",time()-3600);
	}
	
	/**
	 * @return bool
	 * @TODO: om cookien är satt, retunera true för att sedan kolla så att den är valid
	 */
	public function cookiesSet()
	{
		if (isset($_COOKIE[self::$username]) && isset($_COOKIE[self::$password]))
		{
			return true;
		}
		else 
		{
			return false;	
		}
	}
	
	/**
	 *  @TODO: kolla om cookien är valid (namn, lösen och tid), isf retunera true
	 */
	public function validCookies($username, $pass, $end, $cryptedPassword)
	{
		if(self::cookiesSet()){
			if($_COOKIE[self::$username] == $username  && self::getCryptedPassword() == $cryptedPassword 
				&& $end > time())
			{
				return true;
			}
			else 
			{
				return false;
			}
		}
	}
	
	public function getUserCookie()
	{
		if(isset($_COOKIE[self::$username]))
		return $_COOKIE[self::$username];
	}
	
	public function getPasswordCookie()
	{
		if(isset($_COOKIE[self::$password]))
		return $_COOKIE[self::$password];
	}
	
	public function autoLogin($username, $password, $endtime){
		
		/*$this->endtime = time() + 3600;
		file_put_contents("endtime.txt", $this->endtime);
		 * 
		 */
		setcookie(self::$username, $username, $endtime);
		$this->cryptedPassword = crypt($password);
		setcookie(self::$password, $this->cryptedPassword, $endtime);	
		
		//file_put_contents("password.txt", $this->cryptedPassword);
	}
	public function getCryptedPassword()
	{
		return $this->cryptedPassword; 
	}
}
