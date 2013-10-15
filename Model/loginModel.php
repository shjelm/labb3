<?php

namespace model;

class loginModel{
	
	/**
	 * @TODO: dokumentera koden
	 * @var string
	 */
	private static $mySession = "mySession";
	
	/**
	 * @var string
	 */
	private static $username ="Admin";
	
	/**
	 * @var string
	 */
	private static $password = "Password";	
	
	/**
	 * @var bool
	 */
	private static $checkBrowser = "checkBrowser";
	
	/**
	 * @var string
	 */
	private static $browser = "browser";
	
	/**
	 * @param string
	 */
	
	public function getUser()
	{
		return self::$username;		
	}
	public function getPass()
	{
		return self::$password;
	}
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
	public function validCookieMsg()
	{
			return 7;
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
		if(isset($_SESSION[self::$mySession])){
			unset($_SESSION[self::$mySession]);
		}
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
	
	public function setMsgCookies($cookie)
	{
		if($cookie){
			return 6;
		}
	}
	
	public function saveEndTime()
	{
		$endtime = time() + 30;
		file_put_contents("endtime.txt", $endtime);		
	}

	public function getEndTime()
	{
		$end = file_get_contents("endtime.txt");
		return $end;
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
