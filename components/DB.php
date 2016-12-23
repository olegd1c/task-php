<?php

class DB
{
	public static function getConnection(){
		$paramsPath = ROOT.'/config/db_config.php';
		$params = include ($paramsPath);
		
		try
				{
			$dbhost = $params['host'];
			//"			localhost"; // Хост базы данных
			$dbuser = $params['user'];//"devuser"; // Логин пользователя базы данных
			$dbpassword = $params['password'];//"admin"; // Пароль пользователя базы данных
			$dbname = $params['dbname'];//"forumsp"; // Имя базы данных
			$dbprefix = $params['dbname']."_";//"forumsp_"; // Префикс таблиц базы данных
			//$dsn = "mysql:host=$dbhost;
			port=8888;
			dbname=$dbname;
			charset=utf8;
			prefix = $dbprefix";
			$dsn = "mysql:host=$dbhost;
			dbname=$dbname;
			charset=utf8";
				/*$dsn = 'mysql' => [
					'driver'    => 'mysql',
					'host'      => env('DB_HOST', 'ХОСТ'),
					'database'  => env('DB_DATABASE', 'БАЗА'),
					'username'  => env('DB_USERNAME', 'ЛОГИН'),
					'password'  => env('DB_PASSWORD', 'ПАРОЛЬ'),
					'charset'   => 'utf8',
					'collation' => 'utf8_unicode_ci',
					'prefix'    => '',
					'strict'    => false,
				],
				*/
			//echo $dbuser.'<br>'.$dbpassword;
			$db = new PDO ($dsn,$dbuser,$dbpassword);
		}
		catch (PDOException $e)
		{
			echo "Failed to get DB handle: " . $e->getMessage() . "\n";
			exit;
		}
		/*$dsn = "mysql:host = {
				$params['host']
			}
			;
			dbname = {
				$params['dbname']
			}
			";
		var_dump($params);
        try {
            $db = new PDO($dsn, $params['user'], $params['password']);
        }catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
		*/
        return $db;
    }
}