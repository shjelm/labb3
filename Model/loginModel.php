<?php

namespace model;

class loginModel{
	
	private static $mySession = "mySession";
	
	private static $username ="Admin";
	
	private static $password = "Password";	
	
	private static $checkBrowser = "checkBrowser";
	
	private static $browser = "browser";
	
	public function checkMessageNr($username, $password)
	{
		if ($username == self::$username && $password == self::$password) {
			
			$_SESSION[self::$mySession] = true;					
			
			return 1;
		} 
		else if (empty($username)) {
			return 2;
		} 
		else if (empty($password)) {
			return 3;
		} 
		else {
			 return 4;
		}
	}
	
	public function checkLogin($username, $password)
	{
		if ($username == self::$username && $password == self::$password){
			return true;
		}
	}
	
	public function checkLogout($logout)
	{
		if($logout)
		{
			self::destroySession();			
			return true;
		}
		else{
			return false;
		}
	}
	public function setLogout($logout)
	{
		if($logout)
		{		
			return 5;
		}
	}
	
	public function checkSession()
	{
		if(isset($_SESSION[self::$mySession])){
			$session = $_SESSION[self::$mySession];
		}
		if(isset($session)){
			
			return true;
		}
		else {
			return false;
		}
	}
	
	public function destroySession()
	{
			unset($_SESSION);
	}
	
	public function getBrowser()
	{
		if (!isset($_SESSION[self::$checkBrowser])){
				$_SESSION[self::$checkBrowser] = array();
				$_SESSION[self::$checkBrowser][self::$browser] = self::getUserAgent();
			}		
	}
	public function checkBrowser()
	{
		if($_SESSION[self::$checkBrowser][self::$browser] = self::getUserAgent()){
			return true;			
		}
		
	}
	
	public function checkCookies($cookie)
	{
		if($cookie){
			return 6;
		}
	}
	
	public static function getUserAgent()
	{
	    static $agent = null;
	
	    if ( empty($agent) ) {
	        $agent = $_SERVER['HTTP_USER_AGENT'];
	
	        if ( stripos($agent, 'Firefox') !== false ) {
	            $agent = 'firefox';
	        } elseif ( stripos($agent, 'MSIE') !== false ) {
	            $agent = 'ie';
	        } elseif ( stripos($agent, 'iPad') !== false ) {
	            $agent = 'ipad';
	        } elseif ( stripos($agent, 'Android') !== false ) {
	            $agent = 'android';
	        } elseif ( stripos($agent, 'Chrome') !== false ) {
	            $agent = 'chrome';
	        } elseif ( stripos($agent, 'Safari') !== false ) {
	            $agent = 'safari';
	        } elseif ( stripos($agent, 'AIR') !== false ) {
	            $agent = 'air';
	        } elseif ( stripos($agent, 'Fluid') !== false ) {
	            $agent = 'fluid';
	        }	
	    }	
	    return $agent;
	}
}
