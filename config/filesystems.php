<?php

return [
	'default' => 'data',

	'disks' => [
		'cwd' => [
			'driver' => 'local',
			'root' => getcwd(),
		],

		'data' => [
			'driver' => 'local',
			'root' => config('app.storage'),
		],
	],
];
