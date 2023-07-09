<?php

return array(
	'name'=>'test_task',
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'modules'=>array(
		// uncomment the following to enable the Gii tool
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'h>yjcr"',
		 	// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('*'),
			'newFileMode'=>0666,
		        'newDirMode'=>0777,
		),
		
	),
	'components'=>array(

		'db'=>array(
		'connectionString' => 'mysql:host=localhost;dbname=test_task',
		'emulatePrepare' => true,
		'username' => 'test_user',
		'password' => 'test_user',
		'charset' => 'utf8',
		'enableProfiling'=>true,
		'enableParamLogging' => true,
		),
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>false,
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
	),
);