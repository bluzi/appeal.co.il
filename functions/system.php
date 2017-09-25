<?php
	function sections() {
		$sections = scandir('storyboard/');
		foreach ($sections as $filename) {
			if (strpos($filename, '.php')) {
				$path = 'storyboard/' . $filename;
				require_once($path);
			}
		}
	}
	
	/**
		Ajax repsonses
	*/
	function ajax_response($data = null) {
		return json_encode(array(
			'data' => $data,
			'error' => false,
			'message' => ''	
		), JSON_UNESCAPED_UNICODE);
	}
	
	function ajax_error($message = '') {
		return json_encode(array(
			'data' => null,
			'error' => true,
			'message' => $message	
		), JSON_UNESCAPED_UNICODE);
	}