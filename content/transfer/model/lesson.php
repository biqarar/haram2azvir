<?php
namespace content\transfer\model;


trait lesson
{
	public static function lesson()
	{

		$query =
		"
			SELECT
				*
			FROM
				classes
			ORDER BY classes.start_date ASC

		";
		$result = \dash\db::get($query, null, false , 'quran_hadith');

		$semester_name =
		[
			1 => "تابستان",
			2 => "پاییز",
			3 => "زمستان",
		];

		$month =
		[
			1 => 3,
			2 => 3,

			3 => 1,
			4 => 1,
			5 => 1,
			6 => 1,

			7 => 2,
			8 => 2,
			9 => 2,

			10 => 3,
			11 => 3,
			12 => 3,
		];

		$semester_start =
		[
			1 => "0301",
			2 => "0701",
			3 => "1001",
		];

		$semester_end =
		[
			1 => "0631",
			2 => "0930",
			3 => "0231",
		];

		$azvir = new \dash\utility\ermile\azvir(azvir_api_key, azvir_api_school, 1);

		$azvir_semester = [];

		foreach ($result as $key => $value)
		{
			$start_date = $value['start_date'];
			$start_date_month = strtotime($start_date);
			if(!$start_date_month)
			{
				\dash\notif::error(T_("Can not set the time"));
				continue;
			}

			$start_date_month = date("m", strtotime($start_date));
			$start_date_month = intval($start_date_month);

			$semester_name_temp = $semester_name[$month[$start_date_month]];

			$endyear = $year = date("Y", strtotime($start_date));
			if($month[$start_date_month] == 3)
			{
				$endyear = intval($year) + 1;
			}
			$semester_name_temp .= ' '. \dash\utility\convert::to_fa_number($year);

			$new_id = null;

			if(array_search($semester_name_temp, $azvir_semester) === false)
			{
				$insert_semester =
				[
					'title' => $semester_name_temp,
					'start' => (string) $year . (string) $semester_start[$month[$start_date_month]],
					'end'   => (string) $endyear . (string) $semester_end[$month[$start_date_month]],
				];
				$insert_semester['status'] = 'expire';

				if(intval($year) >= 1396)
				{
					$insert_semester['status'] = 'active';
				}


				$semester_id = self::fix($azvir->semester('post', $insert_semester), null, $insert_semester);
				if(isset($semester_id['id']))
				{
					$new_id = $semester_id['id'];

					$azvir_semester[$semester_id['id']] = $semester_name_temp;
				}
				else
				{
					$semester_id = self::fix($azvir->semester_search('get', ['search' => $semester_name_temp]));
					if(isset($semester_id[0]['id']))
					{
						$new_id = $semester_id[0]['id'];
						$azvir_semester[$semester_id[0]['id']] = $semester_name_temp;
					}
					else
					{
						\dash\notif::warn("Can not add semester $semester_name_temp");
					}
				}

			}
			else
			{
				$new_id = array_search($semester_name_temp, $azvir_semester);
			}

			if($new_id)
			{
				\dash\db::query("UPDATE classes set azvir_semester_id = '$new_id' WHERE classes.id = $value[id] ", 'quran_hadith');
			}
		}

		\dash\db::query("UPDATE classes set azvir_teacher_id = (SELECT azvir_teacher_id FROM person WHERE person.users_id = classes.teacher)", 'quran_hadith');
		\dash\db::query("UPDATE classes set azvir_topic_id = (SELECT azvir_topic_id FROM plan WHERE plan.id = classes.plan_id)", 'quran_hadith');

		self::add_lesson();
	}


	private static function add_lesson()
	{
		$query =
		"
			SELECT
				*
			FROM
				classes
			ORDER BY classes.start_date ASC

		";
		$result = \dash\db::get($query, null, false , 'quran_hadith');

		$azvir = new \dash\utility\ermile\azvir(azvir_api_key, azvir_api_school, 1);

		$azvir_lesson = [];


		foreach ($result as $key => $value)
		{
			$status = ['draft','cancel','awaiting','full','enable','disable','expire'];

			if($value['status'] === 'ready')
			{
				$status = 'awaiting';
			}
			elseif($value['status'] === 'done')
			{
				$status = 'expire';
			}
			elseif ($value['status'] === 'running')
			{
				$status = 'enable';
			}
			else
			{
				$status = 'draft';
			}
			$topicteacher = ['topic_id' => $value['azvir_topic_id'], 'teacher_id' => $value['azvir_teacher_id']];
			$xx = self::fix($azvir->topicteacher('post', $topicteacher), null, $topicteacher);


			$insert_lesson                   = [];
			$insert_lesson['status']         = $status;
			$insert_lesson['force_semester'] = true;
			$insert_lesson['semester_id']    = $value['azvir_semester_id'];
			$insert_lesson['topic_id']       = $value['azvir_topic_id'];
			$insert_lesson['teacher']        = $value['azvir_teacher_id'];
			$insert_lesson['gender']         = $value['gender'] == 'male' ? 'male' : $value['gender'] == 'female' ? 'female' : 'all';
			$insert_lesson['maxperson']      = $value['azvir_maxperson'];
			$insert_lesson['examdate']       = $value['end_date'];

			$lesson_id = self::fix($azvir->lesson('post', $insert_lesson), false, $insert_lesson);

			if(isset($lesson_id['id']))
				{
					$new_id = $lesson_id['id'];

					$azvir_lesson[$lesson_id['id']] = $lesson_id;
				}
				else
				{
					$new_id = null;
					// $lesson_id = self::fix($azvir->semester_search('get', ['search' => $semester_name_temp]));
					// if(isset($lesson_id[0]['id']))
					// {
					// 	$new_id = $lesson_id[0]['id'];
					// 	$azvir_lesson[$lesson_id[0]['id']] = $lesson_id[0];
					// }
					// else
					// {
					// 	\dash\notif::warn("Can not add lesson $semester_name_temp");
					// }
				}

				if($new_id)
				{
					\dash\db::query("UPDATE classes set azvir_lesson_id = '$new_id' WHERE classes.id = $value[id] ", 'quran_hadith');
				}

			# code...
		}


	}
}
?>