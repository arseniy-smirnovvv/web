<?php  

	class Build
	{
	
		public static function BuildDataDb($set)
		{

			$data = [];

			while (($row = $set->fetch_assoc()) != false) {
				$data[] = $row;
			}

			return $data;
		}

		public static function BuildOneDataDb($set)
		{
			$data = '';

			while (($row = $set->fetch_assoc()) != false) {
				$data = $row;
			}

			return $data;
		}
	}


?>