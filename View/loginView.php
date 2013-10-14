<?php

namespace view;

class loginView{
	
	private static $username = "UserName";
	
	private static $password = "Password";
	
	private static $logOut = "logout";
	
	private static $autoLogin = "AutoLogin";
	
	public function getUsername(){
		if($_POST){
			if(isset($_POST)){
				$username = $_POST[self::$username];
				
				return $username;
			}
		}
	}
	
	public function getPassword()
	{
		if($_POST){
			if(isset($_POST)){	
				$password = $_POST[self::$password];
			
				return $password;
			}
		}
	}
	
	public function setMessage($messageNr)
	{
		if($_POST){
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
					$this->messageString = '<p>Du har loggats ut</p>';	
					break;
				
				case 6:
					$this->messageString = '<p>Inloggning med cookies</p>';	
					break;
				
				default:
					$this->messageString = '<p>Något har gått fel</p>';
			}
			return $this->messageString;
		}
	}
	
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
	
	public function cookiesSet()
	{
		if (isset($_COOKIE[self::$username]) && isset($_COOKIE[self::$password]))
		{
			return true;
		}
	}
	public function autoLogin($username, $password){
		$this->endtime = time() + 3600;
		file_put_contents("endtime.txt", $this->endtime);
		setcookie(self::$username, $username, $this->endtime);
		$this->cryptedPassword = crypt($password);
		setcookie(self::$password, $this->cryptedPassword, $this->endtime);	
		
		file_put_contents("password.txt", $this->cryptedPassword);
	}
}
