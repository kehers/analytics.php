analytics.php
=============

A PHP wrapper for [segment.io] (https://segment.io/). Implements the REST API at [segmentio/analytics-rest](https://github.com/segmentio/analytics-rest)

Usage
=====

The usage is as simple as this

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

License
=======

Software is available under the MIT license.