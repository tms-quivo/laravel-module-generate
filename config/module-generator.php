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
		'action' => 'Actions',
		'policy' => 'Policies',
		'observer' => 'Observers',
		'scope' => 'Scopes',
		'event' => 'Events',
		'listener' => 'Listeners',
		'mail' => 'Mail',
		'notification' => 'Notifications',
		'job' => 'Jobs',
		'repository' => 'Repositories',
		'provider' => 'Providers',
	],

	'c_scaffold_folders' => [
		'Actions',
        'Models',
        'Repositories',
        'Observers',
        'Events',
        'Listeners',
        'Jobs',
	],

	'm_scaffold_folders' => [
		'Http\Controllers',
		'Http\Requests',
		'Providers',
		'resources\assets',
		'resources\lang',
		'resources\views',
		'routes'
	],

	'stubs' => [
		'gitkeep' => true,
	],

	'livewire' => [
		'namespace' => 'Livewire',

		'view' => 'resources/views/livewire',
	]
];
