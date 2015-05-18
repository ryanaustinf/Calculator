<?php
	/***
	 * controller.php
	 * Author: raf
	 * @20150518
	 * This file is for AJAX DB access functions.
	 */
	require_once "../test.php";
	
	/**
	 * Checks if username exists in the database
	 * @param unknown $uname
	 * @return "true" if username exists and false otherwise
	 */
	function checkUsername($uname) {
		global $conn;
		
		//check if username is in the database
		$query = "SELECT username FROM users WHERE username = ?";
		$stmt = $conn->prepare($query);
		$stmt->bind_param("s",$uname);
		$stmt->execute();
		
		//return if exists
		$ret = ( $stmt->fetch() ? "true" : "false" );
		$stmt->close(); //close statement
		return $ret;
	}
	
	switch( $_POST['request'] ) {
		case 'username':
			echo checkUsername($_POST['uname']);
			break;
		default:
	}
	
	$conn->close();
?>