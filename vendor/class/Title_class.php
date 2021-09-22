<?php  
	
	// Класс, который строит название страниц
	class Title 
	{
		private static $mode;

		public static function constructAdminAdd($mode, $type)
		{
			if ($mode == 'add' and $type == 'section'){ 
				self::$mode = 'addsection';
				return 'Добавить Раздел';
			};
			if ($mode == 'add' and $type == 'category'){
				self::$mode = 'addcategory';
				return 'Добавить Категорию';
			};
			if ($mode == 'edit' and $type == 'section'){
				self::$mode = 'editsection';
				return 'Изменить Раздел';
			};
			if ($mode == 'edit' and $type == 'category'){
				self::$mode = 'editcategory';
				return 'Изменить Категорию';
			};
		}

		public static function getModeAdminADd()
		{
			return self::$mode;
		}
	}
	
?>