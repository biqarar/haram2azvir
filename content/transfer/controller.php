<?php
namespace content\transfer;

class controller
{

	// for routing check
	public static function routing()
	{
		ini_set('memory_limit', '-1');
		ini_set('max_execution_time', '999999999999999999');

		if(!defined('azvir_api_key'))
		{
			define('azvir_api_key', '$2y$07$jYmLs6CX1Fjcwz4NVpd8wuV1g92KSRyrRX.nj0nKL01GY2VvaBKcq');
		}

		if(!defined('azvir_api_school'))
		{
			define('azvir_api_school', 'edu');
		}

		// $azvir = new \dash\utility\ermile\azvir(azvir_api_key, azvir_api_school, 1);
		// $me = '{"grade_id":4,"group_id":5,"course_id":6,"pricetype_id":8,"title":"دماء ثلاثه","totaltime":"12","passscore":"14","certification":false,"learnunit":1}';
		// $me = json_decode($me, true);
		// $x = $azvir->topic('post', $me);
		// var_dump($x);exit();
		// $add_group = $azvir->pricetype('post', ['title' => 'حفظ', 'price' => 2000, 'unittype' => 'real']);

		// var_dump($add_group);exit();

	}


}
?>