<?php
	/**
		Variables
	*/
	$cities = array();
	
	/**
		Functions
	*/
	function get_cities() {
		global $cities;
		
		if ($cities == null) {
			$cities = array();
			
			foreach (scandir("../cities/") as $filename) {
				if (strpos($filename, '.xml')) {
					$city = parse_city_file("../cities/" . $filename);
					$cities[] = $city;
				}
			}
		}
		
		return $cities;
	}
	
	function parse_city_file($path) {
		$xmlString = file_get_contents($path);
		$xmlObj = simplexml_load_string($xmlString) or die("Error: Cannot create object");
		
		$xmlObj = json_encode($xmlObj);
		$xmlObj = json_decode($xmlObj,TRUE);
		
		return $xmlObj;
	}
	
	function get_city_names() {
		$result = array();
		
		$cities = get_cities();
		foreach ($cities as $city) {
			$result[] = $city['@attributes']['description'];
		}
		
		return $result;
	}
	
	function get_city_by_description($cityDescription) {
		foreach (get_cities() as $city) {
			if ($city['@attributes']['description'] == $cityDescription) {
				return $city;
			}
		}
		
		return null;
	}
	
	function get_offenses_names($cityDescription) {
		$result = array();
		$city = get_city_by_description($cityDescription);
		
		if ($city == null) {
			return false; // No such city
		}
		
		$offenses = $city['offenses']['offense'];
		
		foreach ($offenses as $offense) {
			$result[$offense['@attributes']['name']] = 	$offense['@attributes']['description'];
		}
		
		return $result;
	}
	
	function fields_to_html_form($cityDescription, $offenseName) {
		$result = "";
		$city = get_city_by_description($cityDescription);
		
		$offense = get_offense_by_name($city, $offenseName);
		
		if ($offense != null) {
			foreach ($offense['fields']['row'] as $row) {
				$result .= '<div class="row">';
				$result .= "\n";
				$result .= '<div class="two columns">&nbsp;</div>';
				$result .= "\n";
				
				
				if (array_key_exists('field', $row)) {
					$fields = $row['field'];
				}
				else {
					$fields = $row;
				}
				
				foreach ($fields as $field) {
					if (array_key_exists('@attributes', $field)) {
						$field = $field['@attributes'];
					}
					
					$result .= field_to_html($field);	
					$result .= "\n";
				}
				
				$result .= '<div class="two columns">&nbsp;</div>';
				$result .= "\n";
				$result .= '</div>';
				$result .= "\n\n";
			}
		}
		
		return $result;
	}
	
	function field_to_html($field) {
		$remembered_fields = array();
		
		if (array_key_exists('remembered-fields', $_COOKIE)) {
			$remembered_fields = json_decode($_COOKIE['remembered-fields'], true);
		}
		
		$result = '<div class="' . num_to_size_class($field['size']) . ' columns">';
			$result .= '<label for="' . $field['name'] . '">' . $field['label'] . '</label>';
			
			$result .= '<input ';
			
			if (array_key_exists('tabindex', $field)) {
				$result .= 'tabindex="' . $field['tabindex'] . '"';
			}
			
			if (array_key_exists('direction', $field)) {
				if ($field['direction'] == 'ltr') {
					$textalign = 'left';
				}
				else {
					$textalign = 'right';
				}
				$result .= 'style="direction: ' . $field['direction'] . '; text-align: ' . $textalign . ';"';	
			}
			
			if (array_key_exists('remember', $field)) {
				$result .= 'data-remember="true"';
			}
			
			if (array_key_exists('mask', $field)) {
				$result .= 'data-mask="' . $field['mask'] . '"';
			}
			
			if (array_key_exists($field['name'], $remembered_fields)) {
				$result .= 'value="' . $remembered_fields[$field['name']] . '"';
			}
			
			if (array_key_exists('type', $field)) {
				$result .= 'type="' . $field['type'] . '"';
			}
			else {
				$result .= 'type="text"';
			}
			
			if (array_key_exists('placeholder', $field)) {
				$result .= 'placeholder="' . $field['placeholder'] . '"';
			}
			
			if (array_key_exists('pattern', $field)) {
				$result .= 'pattern="' . $field['pattern'] . '"';
			}
			
			$result .= ' class="u-full-width field" id="' . $field['name'] . '" />';
			
		$result .= '</div>';
		return $result;
	}
	
	function get_offense_by_name($city, $offenseName) {
		$offenses = $city['offenses']['offense'];
		
		foreach ($offenses as $offense) {
			if ($offense['@attributes']['name'] == $offenseName) {
				return $offense;
			}
		}
		
		return null;
	}
	
	function num_to_size_class($num) {
		$sizes = array(1 => 'one', 2 => 'two', 3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six', 7 => 'seven', 8 => 'eight');
		return $sizes[intval($num)];
	}
	
	function fill_template($template, $fields) {
		$letter = $template;
		foreach ($fields as $key => $value) {
			$letter = str_replace('{' . $key . '}', $value, $letter);
		}
		
		return $letter;
	}
	
	function send_appeal($cityDescription, $offenseName, $fields) {
		$city = get_city_by_description($cityDescription);
		$offense = get_offense_by_name($city, $offenseName);
		
		if ($city['@attributes']['method'] == 'email') {
			return send_appeal_by_email($city, $offense, $fields);
		}
		
		else if ($city['@attributes']['method'] == 'form') {
			return send_appeal_by_form($city, $offense, $fields);
		}
		
		return false;
	}
	
	function send_appeal_by_email($city, $offense, $fields) {
		require_once("../external/phpmailer/PHPMailerAutoload.php");
		
		$email_address = $city['@attributes']['email-address'];
		
		$message_template = trim(preg_replace('/\t+/', '',$offense['message-template']));
		$filled_message_template = fill_template($message_template, $fields);
		
		$subject_template = trim($offense['subject-template']);
		$filled_subject_template = fill_template($subject_template, $fields);
		
		$mail = new PHPMailer;
		$mail->isSMTP();
		$mail->isHTML(true); 
		
		$mail->addAddress($email_address);
		$mail->AddBCC("eliran013@gmail.com");
		
		$mail->setFrom("admin@cookiesession.com", $fields['fullname']);
		
		$mail->Host = 'smtp.mailgun.org';                     // Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = 'postmaster@sandbox0042669c8a704ad5879cb73cc20d0644.mailgun.org';   // SMTP username
		$mail->Password = 'ee54539d0c3641a909ca3be89d4fcab6';                           // SMTP password
		$mail->SMTPSecure = 'tls';   
		
		$mail->isHTML(true);   
		$mail->CharSet = 'UTF-8';
		
		$mail->Subject = $filled_subject_template;
		
		$message = '<html lang="he"><body style="text-align:right; direction:rtl;">';
		$message .= $filled_message_template;
		$message .= "</body></html>";
		
		$mail->Body = nl2br($message, true);
		
		$result = $mail->send();
		
		if ($result) {
			return true;
		}
		else {
			return $mail->ErrorInfo;
		}
	}
	
	function send_appeal_by_form($city, $offense, $fields) {
		return false;
	}