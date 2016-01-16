<?php

include('class.password.php');

class User extends Password{

    private $db;
	
	function __construct($db){
		parent::__construct();
	
		$this->_db = $db;
	}

	public function is_logged_in(){
		if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
			return true;
		}		
	}

	private function get_user_hash($Username){	

		try {

			$stmt = $this->_db->prepare('SELECT Password FROM Users WHERE Username = :Username');
			$stmt->execute(array('Username' => $Username));
			
			$row = $stmt->fetch();
			return $row['Password'];

		} catch(PDOException $e) {
		    echo '<p class="error">'.$e->getMessage().'</p>';
		}
	}

	
	public function login($Username,$Password){	

		$hashed = $this->get_user_hash($Username);
		
		if($this->password_verify($Password,$hashed) == 1){
		    
		    $_SESSION['loggedin'] = true;
		    return true;
		}		
	}
	
		
	public function logout(){
		session_destroy();
	}
	
}


?>