<?php
require "analytics.php";

try {
	// Init with API secret
	$analytics = new analytics('p8bi6cpx36en7b5pceip', '1');
	
	// Identify user
	$traits = array(
		'name' => 'Opeyemi Obembe',
		'twitter' => '@kehers',
	);
	$analytics->identify($traits);
	
	// Track his action
	$properties = array(
		'ip' => '127.0.0.1',
		'laptop' => 'HP G62'
	);
	$analytics->track('Tested analytics.php', $properties);
	
} catch(Exception $e) {
	echo $e;
}
?>