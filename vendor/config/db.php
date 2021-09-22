<?php  
	
	define('DB_HOST', 'localhost');
	define('DB_USER', 'root');
	define('DB_PASSWORD', '');
	define('DB_NAME', 'forum');

	define('TABLE_SECTION', 'section');
	define('TABLE_CATEGORY', 'category');
	define('TABLE_USER', 'user');
	define('TABLE_CDK', 'cdk_key');
	define('TABLE_THEMES', 'theme');
	define('TABLE_QUEST', 'quest');
	define('TABLE_LOG_BAN', 'log-ban');
	define('TABLE_LOG_VISIT', 'visit-log');
	define('TABLE_LOG_RECOVERY', 'recovery-log');
		
	$mysql = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	if($mysql->connect_errno) exit('Ошибка при подключение к базе данных. Извините за неудобства :)');
?>