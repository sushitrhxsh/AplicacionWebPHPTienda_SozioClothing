<?php
	session_start();

	// Destroying All Sessions
	if(session_destroy()){
		header('Location:index.php');
		die;
		//header("Location: /mtapps/login/"); // Redirecting To Home Page
	}
?>