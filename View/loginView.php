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
	public function setMessage($message)
	{
		if($_GET){

			switch ($message) {
				
				case 'correctUserCredentials':
					$this->messageString = '<p>Inloggningen lyckades</p>';	
					
					if(self::checkAutoLogin())
					{
						$this->messageString = '<p>Inloggningen lyckades och vi kommer ihåg dig nästa gång</p>';
					}			
					break;
					
				case 'emptyUsername': 
					$this->messageString = '<p>Användarnamn saknas</p>';
					break;
	
				case 'emptyPassword': 
					$this->messageString = '<p>Lösenord saknas</p>';
					break;		
					
				case 'incorrectUserCredentials':
					$this->messageString = '<p>Felaktigt användarnamn och/eller lösenord</p>';	
					break;
					
				case 'userLogOut':
					$this->messageString = '<p>Du har nu loggat ut</p>';	
					break;
				
				case 'saveCredentials':
					$this->messageString = '<p>Inloggning lyckad med cookies</p>';	
					break;
				case 'validSavedCredentials':
					$this->messageString = '<p>Felaktig information i cookie</p>';	
					break;
				
				default:
					$this->messageString = '';
			}
			return $this->messageString;
		}
	}
	
	/**
	 * @return bool
	 */	
	public function checkFormSent()
	{
		if($_POST){
			return true;
		}
		else{
			return false;
		}
	}
	
	/**
	 * @return bool
	 */
	public function checkLogout(){
		if($_POST){
			if (isset($_GET[self::$logOut])){
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
		if($_GET){
			if(isset($_POST[self::$autoLogin])){
				return true;
			}
			else {
				return false;
			}
		}
	}
	
	public function destroyCredentials()
	{
		setcookie(self::$username, "",time()-3600);
		setcookie(self::$password, "",time()-3600);
	}
	
	/**
	 * @return bool
	 */
	public function canSaveCredentials()
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
	 *@param string $username 
	 *@param int $end
	 * @return bool
	 */	
	public function correctSavedCredentials($username, $end)
	{
		if(self::canSaveCredentials()){
			if($_COOKIE[self::$username] == $username  &&  $_COOKIE[self::$password] == md5(self::$password."crypt") 
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
	
	/**
	 * @param string $username
	 * @param string $password
	 * @param int $end
	 */
	public function autoLogin($username, $password, $endtime){
		
		setcookie(self::$username, $username, $endtime);
		$this->cryptedPassword = md5($password . "crypt");
		setcookie(self::$password, $this->cryptedPassword, $endtime);	
	}
	
	/**
	 * @return string
	 */
	public function getCryptedPassword()
	{
		if(isset($_COOKIE[self::$password])){	
			return $this->cryptedPassword; 
		}
	}
}
