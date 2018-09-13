<?php
namespace content\transfer\model;


trait times
{
	public static function times()
	{
		$x = '[{"lesson_id":"55","classroom_id":"3D","start":"10:45:00","end":"12:00:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"5W","classroom_id":"3D","start":"10:45:00","end":"12:00:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"5X","classroom_id":"3B","start":"10:00:00","end":"12:00:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"5Y","classroom_id":"3D","start":"08:00:00","end":"10:30:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"5B","classroom_id":"3N","start":"08:00:00","end":"09:15:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"5C","classroom_id":"3N","start":"09:30:00","end":"10:45:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"5D","classroom_id":"3x","start":"08:30:00","end":"09:45:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"5k","classroom_id":"3x","start":"08:30:00","end":"09:45:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"56","classroom_id":"3D","start":"09:15:00","end":"10:30:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"5p","classroom_id":"3M","start":"09:15:00","end":"10:30:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"5m","classroom_id":"3x","start":"10:00:00","end":"11:15:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"5w","classroom_id":"3N","start":"08:00:00","end":"09:15:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"5x","classroom_id":"3N","start":"09:30:00","end":"10:45:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"62","classroom_id":"3y","start":"10:00:00","end":"11:30:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"65","classroom_id":"3B","start":"07:45:00","end":"09:00:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"66","classroom_id":"3M","start":"09:15:00","end":"10:30:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"5q","classroom_id":"3x","start":"13:00:00","end":"14:15:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"5r","classroom_id":"3G","start":"13:00:00","end":"14:15:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"5L","classroom_id":"3B","start":"13:00:00","end":"14:30:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"5f","classroom_id":"3B","start":"13:00:00","end":"14:30:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"5g","classroom_id":"3B","start":"14:30:00","end":"15:45:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"5J","classroom_id":"3D","start":"14:45:00","end":"16:00:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"5h","classroom_id":"3y","start":"13:00:00","end":"14:15:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"5j","classroom_id":"3y","start":"14:30:00","end":"15:45:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"57","classroom_id":"3B","start":"14:30:00","end":"15:45:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"58","classroom_id":"3N","start":"13:00:00","end":"14:30:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"59","classroom_id":"3N","start":"14:30:00","end":"15:45:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"5s","classroom_id":"3R","start":"13:45:00","end":"14:45:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"5t","classroom_id":"3x","start":"14:30:00","end":"15:45:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"5b","classroom_id":"3x","start":"18:00:00","end":"20:00:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"63","classroom_id":"3D","start":"13:00:00","end":"14:30:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"5P","classroom_id":"3v","start":"14:00:00","end":"16:00:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"4X","classroom_id":"3d","start":"15:45:00","end":"18:00:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"54","classroom_id":"3d","start":"16:00:00","end":"17:30:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"67","classroom_id":"3v","start":"14:00:00","end":"16:00:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"5Q","classroom_id":"3L","start":"13:00:00","end":"15:00:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"68","classroom_id":"3N","start":"09:30:00","end":"10:45:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"69","classroom_id":"3N","start":"11:00:00","end":"12:15:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"6m","classroom_id":"3d","start":"07:45:00","end":"09:00:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"6n","classroom_id":"3d","start":"09:15:00","end":"10:30:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"6f","classroom_id":"3M","start":"10:45:00","end":"12:00:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"6r","classroom_id":"3B","start":"09:15:00","end":"10:30:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"6s","classroom_id":"3B","start":"10:30:00","end":"12:00:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"5v","classroom_id":"3G","start":"14:30:00","end":"15:45:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"5F","classroom_id":"3x","start":"09:15:00","end":"10:30:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"5T","classroom_id":"3R","start":"13:30:00","end":"14:45:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"5Z","classroom_id":"3w","start":"08:00:00","end":"09:30:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"6b","classroom_id":"3v","start":"14:30:00","end":"16:00:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"4N","classroom_id":"3x","start":"13:45:00","end":"15:00:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"5R","classroom_id":"3H","start":"15:00:00","end":"16:00:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"6c","classroom_id":"3v","start":"10:00:00","end":"12:00:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"6g","classroom_id":"3x","start":"08:00:00","end":"10:00:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"6h","classroom_id":"3d","start":"10:00:00","end":"12:00:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"6p","classroom_id":"3d","start":"08:00:00","end":"10:00:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"6m","classroom_id":"3P","start":"10:00:00","end":"12:00:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"6v","classroom_id":"3R","start":"13:30:00","end":"14:45:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"64","classroom_id":"3M","start":"07:30:00","end":"09:00:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"6j","classroom_id":"3P","start":"08:00:00","end":"09:45:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"6k","classroom_id":"3P","start":"10:00:00","end":"11:45:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"5c","classroom_id":"3D","start":"15:00:00","end":"16:00:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"5M","classroom_id":"3B","start":"19:00:00","end":"20:00:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"5d","classroom_id":"3G","start":"13:00:00","end":"14:30:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"6z","classroom_id":"3D","start":"08:00:00","end":"09:30:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"6x","classroom_id":"3M","start":"10:45:00","end":"12:00:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"6t","classroom_id":"3N","start":"08:00:00","end":"09:15:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"6C","classroom_id":"3H","start":"15:30:00","end":"16:30:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"6M","classroom_id":"3d","start":"00:00:00","end":"00:00:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"43","classroom_id":"3d","start":"16:00:00","end":"17:00:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"43","classroom_id":"3d","start":"16:00:00","end":"17:00:00","date":null,"type":"class","weekday":"monday","period":"weekly","desc":null},
{"lesson_id":"43","classroom_id":"3d","start":"16:00:00","end":"17:00:00","date":null,"type":"class","weekday":"wednesday","period":"weekly","desc":null},
{"lesson_id":"44","classroom_id":"3d","start":"16:00:00","end":"17:00:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"44","classroom_id":"3d","start":"16:00:00","end":"17:00:00","date":null,"type":"class","weekday":"sunday","period":"weekly","desc":null},
{"lesson_id":"44","classroom_id":"3d","start":"16:00:00","end":"17:00:00","date":null,"type":"class","weekday":"monday","period":"weekly","desc":null},
{"lesson_id":"44","classroom_id":"3d","start":"16:00:00","end":"17:00:00","date":null,"type":"class","weekday":"tuesday","period":"weekly","desc":null},
{"lesson_id":"44","classroom_id":"3d","start":"16:00:00","end":"17:00:00","date":null,"type":"class","weekday":"wednesday","period":"weekly","desc":null},
{"lesson_id":"6T","classroom_id":"3w","start":"18:30:00","end":"19:30:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"3b","classroom_id":"3d","start":"00:00:00","end":"00:00:00","date":null,"type":"class","weekday":"","period":"weekly","desc":null},
{"lesson_id":"7N","classroom_id":"3D","start":"17:00:00","end":"18:00:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"7Y","classroom_id":"3C","start":"17:30:00","end":"18:30:00","date":null,"type":"class","weekday":"tuesday","period":"weekly","desc":null},
{"lesson_id":"8y","classroom_id":"3x","start":"16:30:00","end":"17:30:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"8n","classroom_id":"3F","start":"19:45:00","end":"20:30:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"8n","classroom_id":"3F","start":"19:45:00","end":"20:30:00","date":null,"type":"class","weekday":"wednesday","period":"weekly","desc":null},
{"lesson_id":"3d","classroom_id":"3d","start":"00:00:00","end":"00:00:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"3d","classroom_id":"3d","start":"00:00:00","end":"00:00:00","date":null,"type":"class","weekday":"monday","period":"weekly","desc":null},
{"lesson_id":"3d","classroom_id":"3d","start":"00:00:00","end":"00:00:00","date":null,"type":"class","weekday":"wednesday","period":"weekly","desc":null},
{"lesson_id":"5K","classroom_id":"3D","start":"18:30:00","end":"19:30:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"6w","classroom_id":"3d","start":"14:00:00","end":"15:00:00","date":null,"type":"class","weekday":"sunday","period":"weekly","desc":null},
{"lesson_id":"6w","classroom_id":"3d","start":"14:00:00","end":"15:00:00","date":null,"type":"class","weekday":"tuesday","period":"weekly","desc":null},
{"lesson_id":"S","classroom_id":"3B","start":"13:15:00","end":"14:30:00","date":null,"type":"class","weekday":"","psaturdayeriod":"weekly","desc":null},
{"lesson_id":"9d","classroom_id":"3v","start":"19:00:00","end":"21:00:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"T","classroom_id":"3d","start":"17:00:00","end":"18:00:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"T","classroom_id":"3d","start":"17:00:00","end":"18:00:00","date":null,"type":"class","weekday":"monday","period":"weekly","desc":null},
{"lesson_id":"T","classroom_id":"3d","start":"17:00:00","end":"18:00:00","date":null,"type":"class","weekday":"wednesday","period":"weekly","desc":null},
{"lesson_id":"95","classroom_id":"3d","start":"18:00:00","end":"19:00:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"95","classroom_id":"3d","start":"18:00:00","end":"19:00:00","date":null,"type":"class","weekday":"monday","period":"weekly","desc":null},
{"lesson_id":"95","classroom_id":"3d","start":"18:00:00","end":"19:00:00","date":null,"type":"class","weekday":"wednesday","period":"weekly","desc":null},
{"lesson_id":"V","classroom_id":"3d","start":"17:00:00","end":"18:00:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"V","classroom_id":"3d","start":"17:00:00","end":"18:00:00","date":null,"type":"class","weekday":"monday","period":"weekly","desc":null},
{"lesson_id":"V","classroom_id":"3d","start":"17:00:00","end":"18:00:00","date":null,"type":"class","weekday":"wednesday","period":"weekly","desc":null},
{"lesson_id":"4Y","classroom_id":"3d","start":"20:30:00","end":"20:30:00","date":null,"type":"class","weekday":"sunday","period":"weekly","desc":null},
{"lesson_id":"4Y","classroom_id":"3d","start":"20:30:00","end":"20:30:00","date":null,"type":"class","weekday":"tuesday","period":"weekly","desc":null},
{"lesson_id":"4Z","classroom_id":"3d","start":"00:00:00","end":"00:00:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"4Z","classroom_id":"3d","start":"00:00:00","end":"00:00:00","date":null,"type":"class","weekday":"monday","period":"weekly","desc":null},
{"lesson_id":"4Z","classroom_id":"3d","start":"00:00:00","end":"00:00:00","date":null,"type":"class","weekday":"wednesday","period":"weekly","desc":null},
{"lesson_id":"3Q","classroom_id":"3t","start":"10:00:00","end":"12:00:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"4B","classroom_id":"3d","start":"13:30:00","end":"14:30:00","date":null,"type":"class","weekday":"thursday","period":"weekly","desc":null},
{"lesson_id":"b7","classroom_id":"3x","start":"20:30:00","end":"21:30:00","date":null,"type":"class","weekday":"sunday","period":"weekly","desc":null},
{"lesson_id":"b7","classroom_id":"3x","start":"20:30:00","end":"21:30:00","date":null,"type":"class","weekday":"tuesday","period":"weekly","desc":null},
{"lesson_id":"b7","classroom_id":"3x","start":"20:30:00","end":"21:30:00","date":null,"type":"class","weekday":"thursday","period":"weekly","desc":null},
{"lesson_id":"bH","classroom_id":"3d","start":"18:00:00","end":"19:30:00","date":null,"type":"class","weekday":"sunday","period":"weekly","desc":null},
{"lesson_id":"bH","classroom_id":"3d","start":"18:00:00","end":"19:30:00","date":null,"type":"class","weekday":"tuesday","period":"weekly","desc":null},
{"lesson_id":"bH","classroom_id":"3d","start":"18:00:00","end":"19:30:00","date":null,"type":"class","weekday":"thursday","period":"weekly","desc":null},
{"lesson_id":"fQ","classroom_id":"3d","start":"00:00:00","end":"00:00:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"fR","classroom_id":"3d","start":"00:00:00","end":"00:00:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"g8","classroom_id":"3d","start":"13:00:00","end":"14:15:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"g8","classroom_id":"3d","start":"13:00:00","end":"14:15:00","date":null,"type":"class","weekday":"wednesday","period":"weekly","desc":null},
{"lesson_id":"wQ","classroom_id":"3x","start":"16:00:00","end":"17:00:00","date":null,"type":"class","weekday":"sunday","period":"weekly","desc":null},
{"lesson_id":"wQ","classroom_id":"3x","start":"16:00:00","end":"17:00:00","date":null,"type":"class","weekday":"tuesday","period":"weekly","desc":null},
{"lesson_id":"xS","classroom_id":"3d","start":"10:45:00","end":"12:00:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"vY","classroom_id":"3d","start":"18:00:00","end":"20:00:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"vY","classroom_id":"3d","start":"18:00:00","end":"20:00:00","date":null,"type":"class","weekday":"monday","period":"weekly","desc":null},
{"lesson_id":"vY","classroom_id":"3d","start":"18:00:00","end":"20:00:00","date":null,"type":"class","weekday":"wednesday","period":"weekly","desc":null},
{"lesson_id":"JJ","classroom_id":"3d","start":"00:00:00","end":"00:00:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"JK","classroom_id":"3d","start":"00:00:00","end":"00:00:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"JV","classroom_id":"3b","start":"00:00:00","end":"00:00:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"Mr","classroom_id":"3w","start":"09:45:00","end":"10:00:00","date":null,"type":"class","weekday":"tuesday","period":"weekly","desc":null},
{"lesson_id":"Tb","classroom_id":"3R","start":"16:00:00","end":"17:00:00","date":null,"type":"class","weekday":"saturday","period":"weekly","desc":null},
{"lesson_id":"Tb","classroom_id":"3R","start":"16:00:00","end":"17:00:00","date":null,"type":"class","weekday":"monday","period":"weekly","desc":null},
{"lesson_id":"Tb","classroom_id":"3R","start":"16:00:00","end":"17:00:00","date":null,"type":"class","weekday":"wednesday","period":"weekly","desc":null}]
';

		$x = json_decode($x, true);
		$azvir = new \dash\utility\ermile\azvir(azvir_api_key, azvir_api_school, 1);
		foreach ($x as $key => $value)
		{
			$xx = self::fix($azvir->time('post', $value), null, $value);
		}

		var_dump(1);exit();

		return;

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