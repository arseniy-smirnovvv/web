<?php  

  if(preg_match("#admin#", $_SERVER["SCRIPT_NAME"])){
    require_once '../vendor/autoload.php';
  } else {
    require_once './vendor/autoload.php';
  }

	/**
	 * Класс, для работы с юзером
	 */
	class User
	{
    private $recovery_table;
    private $log_table;
		private $cdk_table;
		private $table;
		private $db;

		private $id;
		private $login;
		private $email;
		private $status;
		private $moder;
		private $admin;
		private $data_reg;

		function __construct($db, $table, $cdk_table, $log_table = false, $log_recovery = false)
		{
			$this->table = $table;
			$this->db = $db;
			$this->cdk_table = $cdk_table;
      $this->log_table = $log_table;
      $this->recovery_table = $log_recovery;
		}
		
		# Контроллер регистрации нового пользователя
		public function register($login, $email, $password, $surpassword)
		{
			try {
				$login = $this->validData($login, 'Поле с логином пустое!', 'Поле с логином слишком длинное!');
				$this->loginFree($login);
				$email = $this->validEmail($email, 'Поле с почтовым ящиком пустое!', 'Поле с почтовым ящиком слишком длинное!');
				$this->emailFree($email);
				$password = $this->validPassword($password, 'Поле с паролем пустое!', 'Поле с паролем слишком длинное!');
				$surpassword = $this->validPassword($surpassword, 'Поле с подтверждением пароля пустое!', 'Поле с подтверждением пароля слишком длинное!');
				$this->samePassword($password, $surpassword);
				$ip_reg = $_SERVER['REMOTE_ADDR'];
				$this->registerToDb($login, $email, $password, $ip_reg);
			} catch (Exception $e) {
				$this->saveData($login, $email);
				throw new Exception($e->getMessage(), 1);
			}
		}

		# Контроллер входа в аккаунт
		public function login($login, $password)
		{
			try {
				$login = $this->validData($login, 'Поле с логином пустое!', 'Поле с логином слишком длинное!');
				$password = $this->validPassword($password, 'Поле с паролем пустое!', 'Поле с паролем слишком длинное!');
				$this->issetLogin($login, $password);
				$this->checkPassword($login, $password);
				$this->createCDK($this->getIdToLogin($login));
			} catch (Exception $e) {
				throw new Exception($e->getMessage(), 1);
			}
		}

    # Контроллера входа в админ панель
    public function loginAdmin($id, $adminPassword)
    {
      try {
         $adminPassword = $this->validPassword($adminPassword, "Поле с админ паролем пустое!", "Поле с админ паролем слишком длинное!");
         $this->checkAdminPassword($id, $adminPassword);
      } catch (Exception $e) {
        throw new Exception($e->getMessage(), 1);
      }
    }

    # Контроллер изменение аккаунта 
    public function edit($id, $login, $email, $status, $nPassword, $surPassword, $photo)
    {
      try {
        $login = $this->validData($login, "Поле с паролем пустое!", "Поле с паролем слишком длинное!");
        $email = $this->validEmail($email);
        $status = $this->validData($status, "Поле с подписью пусто!", "Поле с подписью слишком длинное!");
        $nPassword = $this->validPasswordEdit($nPassword, "Поле с новым паролем слишком длинное!");
        $surPassword = $this->validPasswordEdit($surPassword, "Поле с новым паролем слишком длинное!");
        $this->samePassword($nPassword, $surPassword);
        $photoName = $this->savePhoto($photo);
        $this->editToDb($id, $login, $email, $status, $nPassword, $photoName);
      } catch (Exception $e) {
        throw new Exception($e->getMessage(), 2);
      }
    }

		# Метод, проверяет вхожденные данные
		private function validData($data, $error1, $error2)
		{
			$len = mb_strlen($data);

			if ($len <= 0) throw new Exception($error1);
			if ($len >= 255) throw new Exception($error2);

			return htmlspecialchars($data);
 		}

 		private function validEmail($email)
 		{
 			$len = mb_strlen($email);

 			if ($len <= 0) throw new Exception("Поле с почтовым ящиком пустое!");
 			if ($len > 255) throw new Exception("Поле с почтовым ящиком слишком длинное!");
 			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) throw new Exception("Неверный тип почтового ящика!");
 			
 			return htmlspecialchars($email);
 		}

 		# Метод, который проверяет пароли, и возрващает их в защиврованном ввиде стандартом md5
 		private function validPassword($password, $error1, $error2)
 		{
 			$len = mb_strlen($password);

 			if ($len <= 0) throw new Exception($error1);
			if ($len >= 255) throw new Exception($error2);

			return md5(htmlspecialchars($password));
 		}

    # Метод, который проверяет пароль для редактирования 
    public function validPasswordEdit($password, $error)
    {
      if(!isset($password) || $password == '') return false;
      if (mb_strlen($password) >= 255) throw new Exception($error);
      
      return md5(htmlspecialchars($password));
    }

 		# Метод, сравнивающий пароли 
 		private function samePassword($password, $surpassword)
 		{
 			if (!($password === $surpassword)) throw new Exception("Папрои не совпадают!");

 			return true;
 		}

 		# Метод, который проверяет свободен ли логин
 		private function loginFree($login)
 		{
 			$query = "SELECT COUNT(*) FROM `$this->table` WHERE `login` = '$login'";

 			if (!($set_count = $this->db->query($query))) throw new Exception("Не удалось проверить, занят ли Логин!");

 			$count = Build::BuildOneDataDb($set_count);

 			if($count['COUNT(*)'] != 0) throw new Exception("Такой аккаунт уже существует. Пожалуйста придумайте другой логин!");
 			return true;
  	}

		# Метод, который проверяет свободен ли почтовый ящик
		private function emailFree($email)
		{
  			$query = "SELECT COUNT(*) FROM `$this->table` WHERE `email` = '$email'";

 			if (!($set_count = $this->db->query($query))) throw new Exception("Не удалось проверить, занят ли Логин!");

 			$count = Build::BuildOneDataDb($set_count);

 			if($count['COUNT(*)'] != 0) throw new Exception("Такой аккаунт уже существует. Пожалуйста введите другой почтовый ящик!");
 			return true;
	  }

		# Метод, который создает сессии с данными, если при регистрации произошла ошибка, все данные, которые пользователь ввел сохранятся
		private function saveData($login, $email)
		{
  			$_SESSION['login'] = $login;
  			$_SESSION['email'] = $email;
  	}

  		# Метод, который удаляет сохраненные сессии
  		public function delSaveData()
  		{
  			unset($_SESSION['login']);
  			unset($_SESSION['email']);
  		}

  		# Метод, который после сохранение данных возрващает эти данные, если они есть
  		public function getSaveData($data)
  		{
  			if (isset($_SESSION[$data])) return $_SESSION[$data];
+

  			return false; 
  		}

  		# Метод, который получает и возрвращает Id по Логину
  		private function getIdToLogin($login)
  		{
  			$query = "SELECT `id` FROM `$this->table` WHERE `login` = '$login'";

  			if(!($set_id = $this->db->query($query))) throw new Exception("Не удалось получить id для полной авторизации!");
  			
  			$id = Build::BuildOneDataDb($set_id);

  			return $id['id'];
  		}

      public function getLoginToId($id)
      {
        try {
          $user = $this->getUserToId($id);  
          return $user['login']; 
        } catch (Exception $e) {
          return 'Недоступно';
        }
      }

  		# Метод, добавляющий пользователя в базу данных
  		private function registerToDb($login, $email, $password, $ip_reg)
  		{
  			$query = "INSERT INTO `$this->table` (`login`, `email`, `password`, `ip_reg`, `date`) VALUES ('$login', '$email', '$password', '$ip_reg', UNIX_TIMESTAMP())";

  			if (!$this->db->query($query)) throw new Exception("Не удалось рарегистрировать аккаунт!");
  		}


      # Метод, обновляющий данные пользователя в базе данных по его id
      private function editToDb($id, $login, $email, $status, $nPassword, $photoName)
      {
        $query = "UPDATE `$this->table` SET";

        if($login) $query .= "`login` = '$login',";
        if($email) $query .= "`email` = '$email',";
        if($status) $query .= "`status` = '$status',";
        if($nPassword) $query .= "`password` = '$nPassword',";
        if($photoName) $query .="`img` = '$photoName',";

        $len = mb_strlen($query);
        $query = mb_substr($query, 0, ($len - 1));

        $query .= "WHERE `$this->table`.`id` = '$id'";

        if(!$this->db->query($query)) throw new Exception("Не удалось обновить профиль!");
      }

  		# Метод, который проверяет, существует ли аккаунт
  		public function issetLogin($login)
  		{
  			$query = "SELECT COUNT(*) FROM `$this->table` WHERE `login` = '$login'";

   			if (!($set_count = $this->db->query($query))) throw new Exception("Не удалось проверить, занят ли Логин!");

   			$count = Build::BuildOneDataDb($set_count);

   			if($count['COUNT(*)'] == 0) throw new Exception("Аккаунта с таким логином не существует!");

   			return true;
  		}

      # Метод, который проверяет существует ли аккаунт с вхожденной почтой
      public function issetEmail($email)
      {
        $query = "SELECT COUNT(*) FROM `$this->table` WHERE `email` = '$email'";

        if (!($set_count = $this->db->query($query))) throw new Exception("Не удалось проверить, существует ли аккаунт с таким почтовым ящиком!");
        
        $count = Build::BuildOneDataDb($set_count);

        if($count['COUNT(*)'] == 0) throw new Exception("Аккаунта с таким почтовым ящиком не существует!");

        return true;
      }

      # Метод, который проверяет, существует ли аккаунт по id
      public function issetUserToId($id)
      {
        $query = "SELECT COUNT(*) FROM `$this->table` WHERE `id` = '$id'";

        if (!($set_count = $this->db->query($query))) throw new Exception("Не удалось проверить, занят ли Логин!");

        $count = Build::BuildOneDataDb($set_count);

        if($count['COUNT(*)'] == 0) throw new Exception("Аккаунта с таким id не существует!");

        return true;
      }

  		private function checkPassword($login, $password)
  		{
  			$query = "SELECT `password` FROM `$this->table` WHERE `login` = '$login'";

  			if(!($set_password = $this->db->query($query))) throw new Exception("Не удалось получить данные об аккаунте!");
  			
  			$password_db = Build::BuildOneDataDb($set_password);

  			if ($password === $password_db) throw new Exception("Неверный пароль!");
  			 
  			return true;
  		}

  		# Создает ключ Cdk, по которому дальше будет проходить проверка, авторизовался ли пользователь, или нет
  		private function createCDK($user_id)
  		{
  			$key = uniqid();
  			$user_ip = $_SERVER['REMOTE_ADDR'];

  			$query = "INSERT INTO `$this->cdk_table` (`cdk`, `ip`, `user_id`, `date_login`) VALUES ('$key', '$user_ip', '$user_id', UNIX_TIMESTAMP())";

  			if (!$this->db->query($query)) throw new Exception("Не удалось авторизоваться!");

  			$_SESSION['cdk'] = $key;

  			return true;
  		}

  		# Метод, который получает данные по CDK ключу
  		private function getCDK($cdk)
  		{
  			$query = "SELECT * FROM `$this->cdk_table` WHERE `cdk` = '$cdk'";

  			if (!($set_cdk = $this->db->query($query))) return header("Location: http://www.prosto-forum.loc/login.php");

  			$cdk = Build::BuildOneDataDb($set_cdk);

  			return $cdk;
  		}

  		# Метод, который получает данные и  юзере по его id
  		public function getUserToId($id)
  		 {

        try {
          $this->issetUserToId($id);

          $query = "SELECT * FROM `$this->table` WHERE `id` = '$id'";

          if (!($set_data = $this->db->query($query))) throw new Exception("Пользователь по указаному id не найден!", 1);
          ;

          $data = Build::BuildOneDataDb($set_data);

          return $data;  
        } catch (Exception $e) {
          throw new Exception($e->getMessage(), 1);
        }
  		 } 

      # Метод, который получает данные о юзере по его почтовому ящику
      public function getUserToEmail($email)
      {
          try {
            $this->issetEmail($email);
            $query = "SELECT * FROM `$this->table` WHERE `email` = '$email'";
            $set_user = $this->db->query($query); 
            return Build::BuildOneDataDb($set_user); 
          } catch (Exception $e) {
            throw new Exception($e->getMessage(), 1);
          }
      }

  		# Метод, который проверяет авторизовался ли пользователь
  		public function loginUser()
  		{
  			$redirect = "Location: http://www.prosto-forum.loc/login.php";

  			if (!isset($_SESSION['cdk'])) return header($redirect);

  			$user_ip = $_SERVER['REMOTE_ADDR'];
  			$key = $_SESSION['cdk'];

  			$cdk = $this->getCDK($key);

  			if ($cdk['ip'] !== $user_ip) return header($redirect);
			  
  			$user = $this->getUserToId($cdk['user_id']);

        $_SESSION['id'] = $user["id"];

        return true;
  		}

      # Метод, который проверяет загружаемый файл
      public function savePhoto($img)
      {
        $photoName = $img['name'];
        $photoType = $img['type'];
        $type = substr($photoType, 6);
        $photoTmp = $img['tmp_name'];
        $photoSize = $img['size'];
        $error = $img['error'];
        $newName = uniqid() . '.' . $type;
        $dirUpload = "C:\OSPanel\domains\www.prosto-forum.loc\img\profil-photo";

        if($photoName == '') return false;

        if (!($photoType == 'image/jpeg' || $photoType == 'image/jpg' || $photoType == 'image/png')) throw new Exception("Данный тип изоброжения не подходит!");

        if ($photoSize > (5 * 1024 * 1024)) throw new Exception("Изоброжение не должно превышать 5мб!");

        if(!move_uploaded_file($photoTmp, $dirUpload. '\\'. $newName)) throw new Exception("Не удалось загрузить изоброжение на хостинг!");
        ;

        return $newName;
      }

      # Проверяет, модератор ли пользователь
      public function issetModerToId($id)
      {
        try {
          $moder = $this->getUserToId($id);
          if($moder['moder'] != 0 || $moder['admin'] != 0) return true;
          throw new Exception(1);
        } catch (Exception $e) {
          return false;
        }
      }

    # Метод, который проверяет по id пользователя, админ он или нет 
    public function issetAdminToId($id)
    {
      $query = "SELECT COUNT(*) FROM `$this->table` WHERE `id` = '$id' AND `admin` = '1'";

      if(!($set_count = $this->db->query($query))) throw new Exception("Не удалось подключится к базе данных!");
      
      $count = Build::BuildOneDataDb($set_count);

      if($count['COUNT(*)'] == 0) {
        header('Location: ../index.php', false);
        throw new Exception("Вы не являетесь администратором!");
      }
    }

    # Метод, который проверяет на валидность админ пароля пользователя по его id
    public function checkAdminPassword($id, $password)
    {
      $user = $this->getUserToId($id);

      if($user['admin_pass'] !== $password) throw new Exception("Не правильный админ пароль!");
      
      $_SESSION['admin'] = $id;

      return true;
    }

    # Метод, который проверяет, прошел ли администратор аутенфикацию
    public function loginSuperUser()
    {
      if(!isset($_SESSION['admin']) || $_SESSION['admin'] == '') header("Location: login.php");
    }

    # Метод, который возвращает кол-во всех пользователей которые зарегистрированны
    public function countUser()
    { 
      $query = "SELECT COUNT(*) FROM `$this->table`"; 

        if(!($set_count = $this->db->query($query))) throw new Exception("Не удалось получить кол-во зарегистрированных пользователей", 1);

        $count = Build::BuildOneDataDb($set_count);
        return $count['COUNT(*)'];
    }

    # Метод, который возвращает кол-во всех пользователей которые являются модераторами
    public function countModer()
    {
      $query = "SELECT COUNT(*) FROM `$this->table` WHERE `moder` = '1'";

       if(!($set_count = $this->db->query($query))) throw new Exception("Не удалось получить кол-во модераторов!");
       
       $count = Build::BuildOneDataDb($set_count);

       return $count['COUNT(*)'];
    }

    # Метод, которые возвраащает кол-во всех забаненный пользователей
    public function countUserBan()
    {
      $query = "SELECT COUNT(*) FROM `$this->table` WHERE `banned` = '1'";

      if(!($set_count = $this->db->query($query))) throw new Exception("Не удалось получить кол-во забаненных пользователей!");
      
      $count = Build::BuildOneDataDb($set_count);

      return $count['COUNT(*)'];
    }

    # Возвращает ассоциативный массив со всеми пользователями
    public function showAll()
    {
      $query = "SELECT * FROM `$this->table`";

      if(!($set_user = $this->db->query($query))) throw new Exception("Не удалось список получить пользователей!", 1);
      
      $user = Build::BuildDataDb($set_user);

      return $user;
    }


    # Метод, который возвращает ассоциативный массив забаненных пользователей
    public function showUserToBanned()
    {
      $query = "SELECT * FROM `$this->table` WHERE `banned` = '1'";

      if(!($set_user = $this->db->query($query))) throw new Exception("Не удалось получить список забаненных пользователей!");
      
      $user = Build::BuildDataDb($set_user);
      return $user;
    }


    # Контроллер бана пользователя
    public function ban($user_id, $id)
    {
      try {
        if($user_id == $id) throw new Exception("Вы не можете себя забанить!");
        $user = $this->getUserToId($user_id);
        if($user['admin'] == '1') throw new Exception("Вы не можете забанить админа!");
        $this->userBan($user_id);
        $this->logBan($user_id, $id);
      } catch (Exception $e) {
        throw new Exception($e->getMessage(), 1);
      }

    }


    # метод бана пользователя по его id
    private function userBan($user_id)
    {
      $query = "UPDATE `$this->table` SET `banned` = '1', `moder` = '0', `admin` = '0' WHERE `$this->table`.`id` = '$user_id'";

      if(!$this->db->query($query)) throw new Exception("Не удалось забанить пользователя!"); 

      return true;
    }

    # Метод, который записывает в лог данные о бане
    private function logBan($user_id, $id)
    {
      $query = "INSERT INTO `$this->log_table` (`user-id-banned`, `user-id`, `date`) VALUES ('$user_id', '$id', UNIX_TIMESTAMP())";
      if(!$this->db->query($query)) throw new Exception("Не удалось записать данные в лог-бан!");

      return true;      
    }


    # Метод, который разбанивает пользователя
    public function unban($user_id)
    {
      $query = "UPDATE `$this->table` SET `banned` = '0' WHERE `$this->table`.`id` = '$user_id'";

      if(!$this->db->query($query)) throw new Exception("Не удалось разбанить аккаунт!", 1);
      
      return true;
    }


    # Метод, который превращает обычно пользователя в модераторам
    public function makeModer($user_id, $id)
    {
      if($user_id == $id) throw new Exception("Нельзя сделать себя модератором!");
    
        $query = "UPDATE `$this->table` SET `moder` = '1' WHERE `$this->table` . `id` = '$user_id'";

        if(!$this->db->query($query)) throw new Exception("Не удалось сделать пользователя модератором!");
        
        return true;
    }

    # Контроллер снятия модераторам
    public function removeModer($user_id, $id)
    {
      try {
        if($user_id == $id) throw new Exception("Вы не можете разжаловать самого себя!"); 
        $user = $this->getUserToId($user_id);
        if($user['admin'] == '1') throw new Exception("Вы не можете разжаловать админа!");
        $this->removeModerToDb($user_id);    
      } catch (Exception $e) {
        throw new Exception($e->getMessage(), 1);
      }
    }

    # Метод, который снимает модерку с обычно пользователя
    private function removeModerToDb($user_id)
    {
      $query = "UPDATE `$this->table` SET `moder` = '0' WHERE `$this->table` . `id` = '$user_id'";

      if(!$this->db->query($query)) throw new Exception("Не удалось сделать модераторам обычным пользователем!");

      return true;
    }

    # Метод, который удаляет аккаунт
    public function del($id)
    {
      $query = "DELETE FROM `$this->table` WHERE `$this->table`.`id` = '$id'";

      if(!$this->db->query($query)) throw new Exception("Не получилось удалить аккаунт!");
    }

    # Метод, который ищет забаненных пользователей по совпадениям
    public function searchUserBan($reg)
    {
      $query = "SELECT `id`, `login` FROM `$this->table` WHERE  `login` LIKE '%$reg%' AND `banned` = '1'";

      if(!($set_user = $this->db->query($query))) throw new Exception("Не удалось получить список всех забаненных пользователей!", 1);
      
      $user = Build::BuildDataDb($set_user);

      return $user;
    }

    # Метод, который ищет пользователей
    public function searchUser($reg)
    {
      $query = "SELECT * FROM `$this->table` WHERE `login` LIKE '%$reg%'";

      if(!($set_user = $this->db->query($query))) throw new Exception("Не удалось получить список пользователей, которые вы ищите!", 1);

      $user = Build::BuildDataDb($set_user);
      return $user;
    }

    # Метод, который получает последнию о бане
    public function getInfoBan($id)
    {
      $query = "SELECT * FROM `$this->log_table` WHERE `user-id-banned` = '$id' ORDER BY `id` DESC LIMIT 1";

      $retInfo = [
        'user-id-banned' => 'Недоступно',
        'date' => '0'        
      ];

      if(!($set_info = $this->db->query($query))) return $retInfo;

      $info = Build::BuildOneDataDb($set_info);

      if($info['id'] == '' || $info['user-id-banned'] == '') return $info;

      return $info;
    }


    # Метод, который получает информацию входов юзера по её id
    public function getCDKInfoToId($id)
    {
      $query = "SELECT * FROM `$this->cdk_table` WHERE `user_id` = '$id' ORDER BY `id` DESC LIMIT 10";

      if(!($set_cdk = $this->db->query($query))) throw new Exception("Не удалось получить информацию о входе!", 1);

      $cdk = Build::BuildDataDb($set_cdk);

      return $cdk;
    }

    # Метод, который получает информацию о банах юзера по его id
    public function getInfoBanToId($id)
    {
      $query = "SELECT * FROM `$this->log_table` WHERE `user-id-banned` = '$id' ORDER BY `id` DESC";

      if(!($set_log = $this->db->query($query))) throw new Exception("Не удалось получить информацию о бане!", 1);
      
      $log = Build::BuildDataDb($set_log);

      return $log;
    }

    # Контроллер сброса пароля
    public function recoveryCode($email)
    {
      try {
        $email = $this->validEmail($email);
        $this->issetEmail($email);
        $_SESSION['code'] = md5($_SERVER['REMOTE_ADDR'] . uniqid());
        $_SESSION['mail'] = $email;
        $code = $_SESSION['code'];
        $user = $this->getUserToEmail($email);
        $this->sendCode($user['login'], $email, $code);
        $this->logRecovery($user['id'], $email, $code);
      } catch (Exception $e) {
        throw new Exception($e->getMessage(), 1);
      }
    }

    # Метод, который отправляет на почту код подтверждения
    public function sendCode($login, $email, $code)
    {
      $mail = mail($email, "Восстановление пароля на форуме prosto-forum", "Привет $login. Ты подавал заявку на восстановление пароля? Если нет, то просто забей на этой письмо :) А если да, то держки код $code");

      if(!$mail) throw new Exception("Код не отправлен!");
      
      return true;
    }

    # Метод, который отправляет на почту новый пароль
    public function sendPassword($email, $password)
    {
      $mail = mail($email, 'Сброс пароля', "Ваш новый пароль: $password");

      if(!$mail) throw new Exception("Код не отправлен!");
      
      return true;
    }

    # Метод, который проверяет код, если верен, то срасывается пароль
    public function recoveryPassword($code)
    {
      try {
        if(!isset($_SESSION['code'])) throw new Exception("Ошибка в доступе. Вы еще оставили заявку для сброса пароля!");
        if($_SESSION['code'] !== $code) throw new Exception("Неправильный код!");
        $log_info = $this->getLogRecovery($_SESSION['mail']);
        if($log_info['code'] !== $code) throw new Exception("Неправильный код!");
        $newPassword = $this->resetPasswordToEmail($_SESSION['mail']);
        $this->sendPassword($_SESSION['mail'], $newPassword);
      } catch (Exception $e) {
        throw new Exception($e->getMessage(), 1);
      }
    }

    # Метод, который записывает лог о восстановления пароля
    public function logRecovery($id, $email, $code)
    {
      $query = "INSERT INTO `$this->recovery_table` (`user-id`, `email`, `code`, `date`) VALUES ('$id', '$email', '$code', UNIX_TIMESTAMP())";

      if(!$this->db->query($query)) throw new Exception("Не удалось записать лог восстановления пароля!");
    }

    # Метод, который получает информацию о логе восстановление 
    public function getLogRecovery($email)
    {
      $query = "SELECT * FROM `$this->recovery_table` WHERE `email` = '$email'";

      if(!($set_info = $this->db->query($query))) throw new Exception("Не удалось получить лог-записи о восстановление!");
      return Build::BuildOneDataDb($set_info);
    }

    # Метод, который сбрасывает пароль у аккаунта по его почтовому ящику, и возвращает новый пароль
    public function resetPasswordToEmail($email)
    {
      $latter = ['a', 'b', 'c', 'd','e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];

      $newPassword = '';
      for ($i=0; $i < 8; $i++) { 
          $newPassword .= $latter[rand(0, (count($latter) - 1))]; 
      }

      $mNewPassword = md5($newPassword);

      $query = "UPDATE `$this->table` SET `password` = '$mNewPassword' WHERE `email` = '$email'";

      if(!$this->db->query($query)) throw new Exception("Не удалось изменить пароль!");
        
      return $newPassword; 
    }

    public function exitToAccount()
    {
      if(isset($_SESSION['cdk'])) unset($_SESSION['cdk']);
      if(isset($_SESSION['id'])) unset($_SESSION['id']);
      if(isset($_SESSION['admin'])) unset($_SESSION['admin']);
    }

	}  
?>