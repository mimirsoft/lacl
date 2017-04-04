<?php
/*
 *
This file is part of Mimirsoft Lightweight ACL System
Copyright (C) 2011, Kevin Milhoan, Mimir Software Corporation

Mimirsoft Lightweight ACL System is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as
published by the Free Software Foundation, either version 3 of the
License, or (at your option) any later version.

Mimirsoft Lightweight ACL System is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with Mimirsoft Lightweight ACL System.  If not, see <http://www.gnu.org/licenses/>.

Contact MimirSoft at mimirsoft@gmail.com or www.mimirsoft.com

*
*/
class PasswordException extends Exception
{
	public $backtrace;
	public $message;

	public function __construct($message=false) {
		if(!$message) {
			$this->message = "No message provided";
		}
		else{
			$this->message = $message;
		}
		$this->backtrace = debug_backtrace();
	}
}

class Password
{

	function generatePassword($length = 9, $add_dashes = false, $available_sets = 'luds') 
	{
			$sets = array();
			if(strpos($available_sets, 'l') !== false)
			$sets[] = 'abcdefghjkmnpqrstuvwxyz';
			if(strpos($available_sets, 'u') !== false)
			$sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
			if(strpos($available_sets, 'd') !== false)
			$sets[] = '23456789';
			if(strpos($available_sets, 's') !== false)
			$sets[] = '!@#$%&*?';
			$all = '';
			$password = '';
			foreach($sets as $set)
			{
			$password .= $set[array_rand(str_split($set))];
			$all .= $set;
			}
			$all = str_split($all);
			for($i = 0; $i < $length - count($sets); $i++)
			$password .= $all[array_rand($all)];
			$password = str_shuffle($password);
			if(!$add_dashes)
			return $password;
			$dash_len = floor(sqrt($length));
			$dash_str = '';
			while(strlen($password) > $dash_len)
			{
			$dash_str .= substr($password, 0, $dash_len) . '-';
			$password = substr($password, $dash_len);
			}
			$dash_str .= $password;
			return $dash_str;

	}
	
	// get a new salt - 21 hexadecimal characters long
	// current PHP installations should not exceed 21 characters
	// on dechex( mt_rand() )
	// but we future proof it anyway with substr()
	function getPasswordSalt()
	{
		return mcrypt_create_iv(22, MCRYPT_DEV_URANDOM);
	}
	
	//retrieve hash from database
	function createPasswordHash($password)
	{
		$options = [
			'cost' => 11,
			'salt' => Password::getPasswordSalt()
		];
		echo "SALT<BR/>";
		echo $options['salt'];
		echo "<BR/>";
		$hash = password_hash($password, PASSWORD_BCRYPT, $options);
		return $hash;
	}
	
	//retrieve hash from database
	function getPasswordHash($user_id)
	{
		//retrieve hash from database
		return $hash;
	}
	
	// compare a password to a hash
	function comparePassword( $password, $hash )
	{
		return password_verify($password, $hash);
	}
	
	// get a new hash for a password
	//$hash = getPasswordHash( getPasswordSalt(), $password );
	
}
?>
