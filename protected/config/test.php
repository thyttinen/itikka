<?php

return CMap::mergeArray(
	require(dirname(__FILE__).'/main.php'),
	array(
		'components'=>array(
			'fixture'=>array(
				'class'=>'system.test.CDbFixtureManager',
			),
                    
			'db'=>array(
				'connectionString'=>'pgsql:host=localhost;port=5432;dbname=itikka_test',
                                'username' => 'postgres',
                                'password' => 'pass',
                                'charset' => 'utf8',
			),
			
		),
	)
);
