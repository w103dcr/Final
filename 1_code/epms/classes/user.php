<?php

	class User{
		private $username;
		private $group;
		
		public function login($username, $password, $dbHandler){
			$row = $dbHandler->run('SELECT * 
									FROM `users` 
									WHERE `username` = :username 
									LIMIT 1',
								array(
									'username' => $username
									))->fetch(PDO::FETCH_ASSOC);
			
			// If there are results
			if($row){
				$hashedPassword = $row['password'];
				
				if(password_verify($password, $hashedPassword)){
					$this->username = $row['username'];
					$this->group = $row['access_group'];
					
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}
		
		public function getGroup(){
			return htmlspecialchars($this->group);
		}
		
		public function getUsername(){
			return htmlspecialchars($this->username);
		}
		
		public function logout(){
			session_destroy();
			unset($_SESSION['user_session']);		
		}
	}

?>