<?php
	/***
	 * logout.php
	 * Author: raf
	 * @20150518
	 * This file facilitates logout.
	 */

	//start session
	session_start();
	
	//destroys session
	session_unset();
	session_destroy();
	
	//goes to homepage
	echo "<script>location = '/Calculator'</script>";
?>