<?php
namespace content\transfer\model;


trait takenunit
{
	public static function takenunit()
	{

		$query = " SELECT * FROM classification	";

		$result = \dash\db::get($query, null, false , 'quran_hadith');

		# code... error_in_insert

		$azvir = new \dash\utility\ermile\azvir(azvir_api_key, azvir_api_school, 1);

		$azvir_takenunit = [];

		foreach ($result as $key => $value)
		{
			if($value['because'] == 'error_in_insert')
			{
				continue;
			}

			$status = 'taken';
			if($value['because'] == 'cansel' || $value['because'] == 'move')
			{
				$status = 'cancel';
			}
			elseif($value['because'] == 'absence')
			{
				$status = 'remove';

			}

		// $status = \dash\app::request('status');
		// if(!in_array($status, ['taken','remove','add','removeforce','removeadmin','cancel']))
		// {
		// 	\dash\notif::error(T_("Invalid status of :takenunit"), 'status');
		// 	return false;
		// }

			$insert_takenunit                         = [];
			$insert_takenunit['addedby']              = 'expert';
			// $insert_takenunit['firstscore']        = $firstscore;
			// $insert_takenunit['firstscoretime']    = $firstscoretime;
			// $insert_takenunit['teacherscore']      = $teacherscore;
			// $insert_takenunit['teacherscoretime']  = $teacherscoretime;
			// $insert_takenunit['reviewtype']        = $reviewtype;
			// $insert_takenunit['studentreviewtext'] = $studentreviewtext;
			// $insert_takenunit['teacheranswertext'] = $teacheranswertext;
			$insert_takenunit['score']                = $value['mark'];
			// $insert_takenunit['price']             = $price;
			// $insert_takenunit['pricepaytype']      = $pricepaytype;
			// $insert_takenunit['discountpercent']   = $discountpercent;
			// $insert_takenunit['discount']          = $discount;
			$insert_takenunit['lesson_id']            = $value['azvir_lesson_id'];
			$insert_takenunit['student_id']           = $value['azvir_member_id'];
			$insert_takenunit['status']               = $status;

			$takenunit_id = self::fix($azvir->takenunit('post', $insert_takenunit), false, $insert_takenunit);

			if(isset($takenunit_id['id']))
			{
				$new_id = $takenunit_id['id'];

				$azvir_lesson[$takenunit_id['id']] = $takenunit_id;
			}
			else
			{
				$new_id = null;
				// $takenunit_id = self::fix($azvir->semester_search('get', ['search' => $semester_name_temp]));
				// if(isset($takenunit_id[0]['id']))
				// {
				// 	$new_id = $takenunit_id[0]['id'];
				// 	$azvir_lesson[$takenunit_id[0]['id']] = $takenunit_id[0];
				// }
				// else
				// {
				// 	\dash\notif::warn("Can not add lesson $semester_name_temp");
				// }
			}

			if($new_id)
			{
				\dash\db::query("UPDATE classification set azvir_takenunit_id = '$new_id' WHERE id = $value[id] ", 'quran_hadith');
			}

			# code...
		}
	}

}
?>