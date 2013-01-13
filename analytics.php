<?php
/*
 * PHP lib for segment.io
 * 
 * Code implements the REST API here
 * https://github.com/segmentio/analytics-rest
 *
 * @author Opeyemi Obembe (@kehers) / digitalcraftstudios.com
 * @version 0.1
 */

class analytics {

	private $headers = array(
		'Content-Type: application/json',
		'Accept: application/json'
	);
	
	private $secret;
	private $session_id;
	private $user_id;
	
	protected $identify_endpoint = "https://api.segment.io/v1/identify";
	protected $track_endpoint = "https://api.segment.io/v1/track";
	
	public function __construct($secret, $user_id, $session_id = null) {
		$this->secret = $secret;
		$this->user_id = $user_id;
		$this->session_id = $session_id;
	}
	
    /**
     * Identify a User
     *
     * @param traits array identity keys
	 * @param time string ISO 8601 date. Leave blank for segment.io server time
     */
	public function identify($traits = array(), $time = null) {
		$body = array(
			'secret' => $this->secret,
			'sessionId' => $this->session_id,
			'userId' => $this->user_id,
			'traits' => $traits
		);
		
		if ($time)
			$body['timestamp'] = $time;
			
		$this->get($this->identify_endpoint, $body);
	}
	
    /**
     * Track an Action
     *
     * @param event string describes what this user just did
     * @param properties array items that describe the event in more detail
	 * @param time string ISO 8601 date. Leave blank for segment.io server time
     */
	public function track($event, $properties = null, $time = null) {
		$body = array(
			'secret' => $this->secret,
			'sessionId' => $this->session_id,
			'userId' => $this->user_id,
			'event' => $event,
			'properties' => $properties,
		);
		
		if ($time)
			$body['timestamp'] = $time;
		
		$this->get($this->track_endpoint, $body);
	}
	
	private function get($endpoint, $body) {
		$body = json_encode($body);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, TRUE);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
		curl_setopt($ch, CURLOPT_URL, $endpoint);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
		// Set to 1 to verify SSL
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

		$response = curl_exec($ch);
		$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		
		if ($status_code != 200) {
			// Handle errors here
			// For now, Im letting things degrade gracefully
			// If you want to throw exceptions, uncomment below
			
			$json = json_decode($response);
			$code = $json->error->code;
			$message = $json->error->message;
			$error = "HTTP $status_code error.";
			if ($code)
				$error .= " ($code): $message";
			
			throw new Exception($error);			
		}
	}
}
?>