<?php


/**
* Класс User - модель для работы с пользователями
 */
class User
{
	
	
	/**
	* Регистрация пользователя
	     * @param string $name <p>Имя</p>
	     * @param string $email <p>E-mail</p>
	     * @param string $password <p>Пароль</p>
	     * @return boolean <p>Результат выполнения метода</p>
	     */
	    public static function register($name, $email, $password)
	    {
		
		Tasks::setCurrentPage(null);
		// 		Соединение с БД
		        $db = DB::getConnection();
		
		// 		Текст запроса к БД
		        $sql = 'INSERT INTO users (name, email, password) '
		                . 'VALUES (:name, :email, :password)';
		
		// 		Получение и возврат результатов. Используется подготовленный запрос
		        $result = $db->prepare($sql);
		$result->bindParam(':name', $name, PDO::PARAM_STR);
		$result->bindParam(':email', $email, PDO::PARAM_STR);
		$result->bindParam(':password', $password, PDO::PARAM_STR);
		return $result->execute();
	}
	
	
	/**
	* Редактирование данных пользователя
	     * @param integer $id <p>id пользователя</p>
	     * @param string $name <p>Имя</p>
	     * @param string $password <p>Пароль</p>
	     * @return boolean <p>Результат выполнения метода</p>
	     */
	    public static function edit($id, $name, $password)
	    {
		// 		Соединение с БД
		        $db = DB::getConnection();
		
		// 		Текст запроса к БД
		        $sql = "UPDATE users
            SET name = :name, password = :password
            WHERE id = :id";
		
		// 		Получение и возврат результатов. Используется подготовленный запрос
		        $result = $db->prepare($sql);
		$result->bindParam(':id', $id, PDO::PARAM_INT);
		$result->bindParam(':name', $name, PDO::PARAM_STR);
		$result->bindParam(':password', $password, PDO::PARAM_STR);
		return $result->execute();
	}
	
	
	/**
	* Проверяем существует ли пользователь с заданными $email и $password
	     * @param string $email <p>E-mail</p>
	     * @param string $password <p>Пароль</p>
	     * @return mixed : integer user id or false
	     */
	    public static function checkUserData($email, $password)
	    {
		// 		Соединение с БД
		        $db = DB::getConnection();
		
		// 		Текст запроса к БД
		        $sql = 'SELECT * FROM users WHERE email = :email AND password = :password';
		
		// 		Получение результатов. Используется подготовленный запрос
		        $result = $db->prepare($sql);
		$result->bindParam(':email', $email, PDO::PARAM_INT);
		$result->bindParam(':password', $password, PDO::PARAM_INT);
		$result->execute();
		
		// 		Обращаемся к записи
		        $user = $result->fetch();
		
		if ($user) {
			// 			Если запись существует, возвращаем id пользователя
			            $paramUser = array();
			$paramUser['id'] = $user['id'];
			$paramUser['roleAdmin'] = $user['access'];
			$paramUser['userName'] = $user['name'];
			return $paramUser;
			//r			eturn $user['id'];
		}
		return false;
	}
	
	
	/**
	* Запоминаем пользователя
	     * @param integer $userId <p>id пользователя</p>
	     */
	    public static function auth($userId,$roleAdmin,$userName)
	    {
		// 		Записываем идентификатор пользователя в сессию
		        $_SESSION['user'] = $userId;
		$_SESSION['roleAdmin'] = $roleAdmin;
		$_SESSION['userName'] = $userName;
		
		Tasks::setCurrentPage(null);
	}
	
	
	/**
	* Возвращает идентификатор пользователя, если он авторизирован.<br/>
	     * Иначе перенаправляет на страницу входа
	     * @return string <p>Идентификатор пользователя</p>
	     */
	    public static function checkLogged()
	    {
		// 		Если сессия есть, вернем идентификатор пользователя
		        if (isset($_SESSION['user'])) {
			return $_SESSION['user'];
		}
		
		header("Location: /user/login");
	}
	
	
	/**
	* Проверяет является ли пользователь гостем
	     * @return boolean <p>Результат выполнения метода</p>
	     */
	    public static function isGuest()
	    {
		if (isset($_SESSION['user'])) {
			return false;
		}
		return true;
	}
	
	public static function isAdmin()
	    {
		
		if (!isset($_SESSION['roleAdmin'])) {
			echo ' no $_SESSION roleAdmin';
			return false;
		}
		else if ($_SESSION['roleAdmin'] !="1") {
			return false;
		}
		return true;
	}
	
	public static function userId()
	    {
		
		if (isset($_SESSION['user'])) {
			return $_SESSION['user'];
		}
		
		return "";
	}
	
	public static function userName()
	    {
		
		if (isset($_SESSION['userName'])) {
			return $_SESSION['userName'];
		}
		
		return "";
	}
	
	
	
	
	/**
	* Проверяет имя: не меньше, чем 2 символа
	     * @param string $name <p>Имя</p>
	     * @return boolean <p>Результат выполнения метода</p>
	     */
	    public static function checkName($name)
	    {
		if (strlen($name) >= 2) {
			return true;
		}
		return false;
	}
	
	
	/**
	* Проверяет телефон: не меньше, чем 10 символов
	     * @param string $phone <p>Телефон</p>
	     * @return boolean <p>Результат выполнения метода</p>
	     */
	    public static function checkPhone($phone)
	    {
		if (strlen($phone) >= 10) {
			return true;
		}
		return false;
	}
	
	
	/**
	* Проверяет имя: не меньше, чем 6 символов
	     * @param string $password <p>Пароль</p>
	     * @return boolean <p>Результат выполнения метода</p>
	     */
	    public static function checkPassword($password)
	    {
		//i		f (strlen($password) >= 6) {
			return true;
			//}			//r			eturn false;
		}
		
		
		/**
		* Проверяет email
		     * @param string $email <p>E-mail</p>
		     * @return boolean <p>Результат выполнения метода</p>
		     */
		    public static function checkEmail($email)
		    {
			
			//i			f (filter_var($email, FILTER_VALIDATE_EMAIL)) {
				return true;
				//}				
				//r				eturn false;
			}
			
			
			/**
			* Проверяет не занят ли email другим пользователем
			     * @param type $email <p>E-mail</p>
			     * @return boolean <p>Результат выполнения метода</p>
			     */
			    public static function checkEmailExists($email)
			    {
				// 				Соединение с БД
				        $db = DB::getConnection();
				
				// 				Текст запроса к БД
				        $sql = 'SELECT COUNT(*) FROM users WHERE email = :email';
				
				// 				Получение результатов. Используется подготовленный запрос
				        $result = $db->prepare($sql);
				$result->bindParam(':email', $email, PDO::PARAM_STR);
				$result->execute();
				
				if ($result->fetchColumn())
				            return true;
				return false;
			}
			
			
			/**
			* Возвращает пользователя с указанным id
			     * @param integer $id <p>id пользователя</p>
			     * @return array <p>Массив с информацией о пользователе</p>
			     */
			    public static function getUserById($id)
			    {
				// 				Соединение с БД
				        $db = DB::getConnection();
				
				// 				Текст запроса к БД
				        $sql = 'SELECT * FROM users WHERE id = :id';
				
				// 				Получение и возврат результатов. Используется подготовленный запрос
				        $result = $db->prepare($sql);
				$result->bindParam(':id', $id, PDO::PARAM_INT);
				
				// 				Указываем, что хотим получить данные в виде массива
				        $result->setFetchMode(PDO::FETCH_ASSOC);
				$result->execute();
				
				return $result->fetch();
			}
			
		}
		