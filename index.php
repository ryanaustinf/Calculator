<?php
	/***
	 * index.php
	 * Author: raf
	 * @20150518
	 * This file is the entry point for the Calculator app.
	 */
	session_start();
	$_SESSION['faillogin'] = false;
	if( $_SERVER['REQUEST_METHOD'] == 'GET' ) {
		if( isset($_SESSION['user']) ) {
			require_once "calc.html";
		} else {
			require_once "login.php";
		}
	} else {
		require_once "../test.php";
		$query = "SELECT password FROM users WHERE username = ?";
		$stmt = $conn->prepare($query);
		$stmt->bind_param("s",$_POST['uname']);
		$stmt->bind_result($pass);
		$stmt->execute();
		$stmt->fetch();
		if( $pass === $_POST['pass'] ) {
			$_SESSION['faillogin'] = false;
			$_SESSION['user'] = $_POST['uname'];
			$stmt->close();
			
			setcookie("avalonuser",$_POST['uname'], time() + 86400 * 14,
					"/Avalon" );
						
			require_once "calc.html";
		} else {
			$_SESSION['faillogin'] = true;
			require_once "login.php";
		}
	}
?>