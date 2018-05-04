<?php
namespace content\transfer\model;


trait times
{
	public static function times()
	{

		$query =
		"
			SELECT
				classes.*,
				(select azvir_classroom_id from place where place.id = classes.place_id LIMIT 1) as `classroom_id`
			FROM
				classes


		";
		$result = \dash\db::get($query, null, false , 'quran_hadith');

		$azvir = new \dash\utility\ermile\azvir(azvir_api_key, azvir_api_school, 1);

		$azvir_semester = [];

		foreach ($result as $key => $value)
		{
			$week_days = $value['week_days'];
			$week_days = explode(',', $week_days);

			foreach ($week_days as $nothing => $day)
			{
				$args = [];
				$args['lesson_id']    = $value['azvir_lesson_id'];
				$args['classroom_id'] = $value['classroom_id'];
				$args['start']        = $value['start_time'];
				$args['end']          = $value['end_time'];
				$args['date']         = null;
				$args['type']         = 'class';
				$args['weekday']      = $day;
				$args['period']       = 'weekly';
				$args['desc']         = null;
				$xx = self::fix($azvir->time('post', $args), null, $args);
				// var_dump($args);
				// var_dump($xx);exit();
				# code...
			}

		}

	}


}
?>