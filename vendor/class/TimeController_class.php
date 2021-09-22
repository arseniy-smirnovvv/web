<?php  

	/**
	 * Класс, для работы со временем
	 */
	class TimeController
	{

		private $nowTime;

		function __construct()
		{
			$this->nowTime = time();
		}

		public function countDays($date)
		{

			$date1 = date_create(date("Y-m-d H:i:s", $this->nowTime));
			$date2 = date_create(date("Y-m-d H:i:s", $date));

			$interval = date_diff($date1, $date2);
			$day = (string)$interval->days;

			if($day == 0) $day = "1";
			$last_num = $day[strlen($day) - 1];

			$str = '';

			if($last_num == 1) $str = ' день';
			elseif ($last_num == 2 || $last_num == 3 || $last_num == 4) $str = ' дня';
			elseif ($last_num == 5 || $last_num == 6 || $last_num == 7 || $last_num == 8 || $last_num == 9 || ($last_num % 5) == 0) $str = ' дней';

			return $day . $str; 
		}
	}
	
?>