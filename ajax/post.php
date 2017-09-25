<?php
	require('../functions/system.php');
	require('../functions/business-logic.php');
	
	if (array_key_exists('action', $_POST) == false) {
		echo ajax_error();
		exit();
	}
	
	switch ($_POST['action']) {			
		case 'send-appeal':
			if (($error = send_appeal($_POST['city'], $_POST['offense'], $_POST['fields'])) === true) {
				echo ajax_response();
			}
			else {
				echo ajax_error($error);
			}
			break;
	}