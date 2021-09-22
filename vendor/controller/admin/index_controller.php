<?php  
	require_once '../vendor/autoload.php';

	$route->defSqlAdminIndex($_GET['del'], $_GET['id']);

	if($_GET['account'] and $_GET['account'] == 'exit') unset($_SESSION['admin']);

	$id = $_SESSION['id'];

	$userClass = new User($mysql, TABLE_USER, TABLE_CDK, TABLE_LOG_BAN);	
	$userClass->loginUser();
	$userClass->issetAdminToId($id);
	$userClass->loginSuperUser();

	$categoryClass = new Category($mysql, TABLE_CATEGORY);
	$sectionClass = new Section($mysql, TABLE_SECTION);
	$themeClass = new Theme($mysql, TABLE_THEMES);
	$questClass = new Quest($mysql, TABLE_QUEST);

	$sectionList = $sectionClass->ShowAll();
	$categoryList = $categoryClass->ShowAll();

	$page = Page::PageList('Главная страница форума', '../index.php', 'Личный кабинет на форуме', '../user.php?id='.$id);

	try {

		$sectionList = $sectionClass->ShowAll();
		$categoryList = $categoryClass->ShowAll();

		$countUser = $userClass->countUser();
		$countModer = $userClass->countModer();
		$countUserBan = $userClass->countUserBan();
		$countTheme = $themeClass->countTheme();
		$hotSection = $sectionClass->countMaxSection();
		$hotTheme = $themeClass->countMaxTheme();

		if($hotTheme == '') $hotTheme['title'] = 'Ни одной темы еще не создано!';

		$user = $userClass->ShowAll();
		$banUser = $userClass->showUserToBanned();
		
		if($_GET['del'] and $_GET['id']){
			$type = $_GET['del'];
			$id = $_GET['id'];

			if ($type == 'section') {
				$getSection = $sectionClass->delToId($id);//Удаляем раздел, в ответ он получает геттэг
				 $categoryClass->delToGetSection($getSection);//Удаляем категори по геттегу разделов
				$quest_id = $themeClass->getIdToGetSection($getSection);//Получаем все id ответов, которые были оставленны в темах связанных в темой с геттегом раздела
				$themeClass->delToGetSection($getSection);//Удаляем все темы связанные с геттегом
				$questClass->delQuestToThemeId($quest_id);//Удаляем все ответы 
				$message = 'Раздел и все что его касается удаленно!';
			}

			if ($type == 'category') {
				$getSection = $categoryClass->delToId($id);
				$quest_id = $themeClass->getIdToGetSection($getSection);
				$themeClass->delToGetSection($getSection);
				$questClass->delQuestToThemeId($quest_id);
				$message = 'Категория удалена!';
			}
		}

		if(isset($_POST['submit-search-user-ban'])){
			$banUser = $userClass->searchUserBan($_POST['search-user-ban']);
		}

		if(isset($_POST['submit-search-user'])){
			$user = $userClass->searchUser($_POST['search-user']);
		}

	} catch (Exception $e) {
		if($e->getCode() == '1') {
			$message = $e->getMessage();
		} else {
			exit('Критическая ошибка: '. $e->getMessage());
		}
	}
?>