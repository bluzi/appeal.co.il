<?php
	require('../functions/system.php');
	require('../functions/business-logic.php');
	
	if (array_key_exists('action', $_GET) == false) {
		echo ajax_error();
		exit();
	}
	
	switch ($_GET['action']) {
		case 'cities':
			$cities = get_city_names();
			echo ajax_response($cities);
			break;
		
		case 'offenses': 
			$offenses = get_offenses_names($_GET['city']);
			if ($offenses) {
				echo ajax_response($offenses);
			}
			else {
				echo ajax_error("העיר שהזנת לא נמצאת במערכת.");
			}
			break;
			
		case 'html-form': 
			$html_form = fields_to_html_form($_GET['city'], $_GET['offense']);
			echo $html_form;
			break;
	}