<?php
Class dbObj{
	/* Database connection start */
	var $servername = "localhost";
	var $username = "root";
	var $password = "mysql";
	var $dbname = "gc";
	var $conn;
	function getConnstring() {
		$con = mysqli_connect($this->servername, $this->username, $this->password, $this->dbname) or die("Connection failed: " . mysqli_connect_error());

		/* check connection */
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		} else {
			$this->conn = $con;
			mysqli_query($this->conn, "SET NAMES 'utf8'");
			mysqli_query($this->conn, 'SET character_set_connection=utf8');
			mysqli_query($this->conn, 'SET character_set_client=utf8');
			mysqli_query($this->conn, 'SET character_set_results=utf8');
		}
		return $this->conn;
	}
}

?>
