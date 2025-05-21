<?php

return [
    'module_path' => base_path('modules'),

	'module_namespace' => 'Modules',

	'container_path' => base_path('app/Containers'),

	'container_namespace' => 'App\Containers',

	'paths' => [
		'controller' => 'Http\Controllers',
		'request' => 'Http\Requests',
		'resource' => 'Transformers',
		'resource_collection' => 'Transformers',
		'model' => 'Models',
	]
];
