<?php

namespace model;

class loginModel{
	
	const CORRECTUSERCREDENTIALS = 1;
	const EMPTYUSERNAME = 2;
	const EMPTYPASSWORD = 3;
	const INCORRECTUSERCREDENTIALS = 4;
	const USERLOGOUT = 5;
	const SAVECREDENTIALS = 6;
	const VALIDSAVEDCREDENTIALS = 7;
	const DEFAULTMSG = 999;
	
	/**
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
	 * @return string
	 */
	public function getUser()
	{
		return self::$username;		
	}
	
	/**
	 * @return string
	 */
	public function getPass()
	{
		return self::$password;
	}
	
	/**
	 * @param string $username
	 * @param string $password
	 * @return int
	 */	
	public function checkMessageNr($username, $password)
	{
		if ($username == self::$username && $password == self::$password) {
			
			$_SESSION[self::$mySession] = true;	
			return 'correctUserCredentials';
		} 
		else if (empty($username)) {
			return 'emptyUsername';
		} 
		else if (empty($password)) {
			return 'emptyPassword';
		} 
		else {
			 return 'incorrectUserCredentials';
		}
	}
	
	/**
	 * @param string $username
	 * @param string $password
	 * @return bool
	 */	
	public function checkLogin($username, $password)
	{
		if ($username == self::$username && $password == self::$password){
			
			return true;
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * @param bool $logout
	 * @return bool
	 */	
	public function checkLogout($logout)
	{
		if($logout)
		{
			$this->destroySession();			
			return true;
		}
		else{
			return false;
		}
	}
	
	/**
	 * @return int
	 */
	public function validSavedCredentialsMsg()
	{
			return 'validSavedCredentials';
	}
	
	/**
	 * @return string
	 */
	public function noMsg()
	{
		return 'defaultMsg';
	}
	
	/**
	 * @param boll $logout
	 * @return int
	 */
	public function setLogout($logout)
	{
		if($logout)
		{		
			return 'userLogOut';
		}
	}
	
	/**
	 * @return bool
	 */	
	public function checkLoggedIn()
	{
		if(isset($_SESSION[self::$mySession])){
			
			return true;
		}
		else {
			return false;
		}
	}
	
	/**
	 * @return bool
	 */	
	public function checkBrowserUsed()
	{
		if(isset($_SESSION[self::$checkBrowser])){
			
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
		if(isset($_SESSION[self::$checkBrowser])){
			unset($_SESSION[self::$checkBrowser]);
		}
	}
	
	public function getBrowser()
	{
		if (!isset($_SESSION[self::$checkBrowser])){
				$_SESSION[self::$checkBrowser] = array();
				$_SESSION[self::$checkBrowser][self::$browser] = self::getUserAgent();
			}		
	}
	
	/**
	 * @return bool
	 */	
	public function checkBrowser()
	{
		
		if(isset($_SESSION[self::$checkBrowser][self::$browser])){
			if($_SESSION[self::$checkBrowser][self::$browser] == self::getUserAgent()){
				return true;			
			}		
			else {
				return false;
			}
		}
	}
	
	/**
	 * @param bool
	 * @return int
	 */	
	public function setMsgSaveCredentials($canSaveCredentials)
	{
		if($canSaveCredentials){
			return 'saveCredentials';
		}
	}
	
	public function saveEndTime()
	{
		$endtime = time() + 3600;
		file_put_contents("endtime.txt", $endtime);		
	}

	public function getEndTime()
	{
		$end = file_get_contents("endtime.txt");
		return $end;
	}
	
	/*Magical fix from Emil*/
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
