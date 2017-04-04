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


if(isset($_POST['apikey']) && $_POST['apikey']=='cde3xsw2' && isset($_POST['application']) )
{
	include("../../globalconstants.php");
	include("../../Database_Mysql.class.php");
	
	include("../../User2.class.php");
	//is this requestor allowed to ask for auths?
	//$dbh = new DB_Mysql();
	/*$stmt = $dbh->prepare("INSERT INTO roles_has_actions
      						SET role_id = :1:,
      						 action_id = :2:");
    //$stmt->execute($this->id, $action_id);
    */
    
	//is this person authenticated?
	
	
	$array = User2::GetAllUsers();
	echo json_encode($array);
    
	
}

?>