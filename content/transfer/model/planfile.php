<?php
namespace content\transfer\model;


trait planfile
{

	private static function xTrim($_array)
	{
		return array_map(function($_a){return trim(trim($_a), '"');}, $_array);
	}

	public function plan_file()
	{
		$plan_file = \lib\utility::files('plan');
		if(isset($plan_file['tmp_name']))
		{
			$plan_file = @\lib\utility\file::read($plan_file['tmp_name']);
		}
		else
		{
			\lib\debug::error(T_("داداش یه فایل بفرست! چیزی نفرستادی که!!"));
			return false;
		}

		$old_plan   = explode("\n", $plan_file);
		$first_rows = [];
		$rows       = [];

		foreach ($old_plan as $key => $value)
		{
			$value = explode(',', $value);
			$value = self::xTrim($value);

			if($key === 0)
			{
				$first_rows = $value;
			}
			else
			{
				if(count($value) !== count($first_rows))
				{
					\lib\debug::error(T_("اینتر آخر فایل رو پاک کن!"));
					return false;
				}

				// $rows[] = $value;
				$rows[] = array_combine($first_rows, $value);
			}
		}

		$group  = array_column($rows, 'group');
		$group  = array_filter($group);
		$group  = array_unique($group);
		$group  = array_values($group);

		$grade  = array_column($rows, 'grade');
		$grade  = array_filter($grade);
		$grade  = array_unique($grade);
		$grade  = array_values($grade);

		$course = array_column($rows, 'course');
		$course = array_filter($course);
		$course = array_unique($course);
		$course = array_values($course);

		$pricetype   = array_column($rows, 'price');
		$pricetype   = array_filter($pricetype);
		$pricetype   = array_unique($pricetype);
		$pricetype   = array_values($pricetype);
		$pricetype[] = '0';

		$azvir = new \lib\utility\ermile\azvir('$2y$07$ppqEBLfiUT7zlqFVxp/Wa.ILdtNVkL/i1o2eAZ4Dm41mV9VlSiU86', 'haram', 1);
		$azvir_group = [];
		foreach ($group as $key => $value)
		{
			$group_id = self::fix($azvir->group('post', ['title' => $value]));
			if(isset($group_id['id']))
			{
				$azvir_group[$group_id['id']] = $value;
			}
			else
			{
				$group_id = self::fix($azvir->group_search('get', ['search' => $value]));
				if(isset($group_id[0]['id']))
				{
					$azvir_group[$group_id[0]['id']] = $value;
				}
				else
				{
					\lib\debug::warn("Can not add group $value");
				}
			}
		}

		$azvir_grade = [];
		foreach ($grade as $key => $value)
		{
			$grade_id = self::fix($azvir->grade('post', ['title' => $value]));
			if(isset($grade_id['id']))
			{
				$azvir_grade[$grade_id['id']] = $value;
			}
			else
			{
				$grade_id = self::fix($azvir->grade_search('get', ['search' => $value]));
				if(isset($grade_id[0]['id']))
				{
					$azvir_grade[$grade_id[0]['id']] = $value;
				}
				else
				{
					\lib\debug::warn("Can not add grade $value");
				}
			}
		}

		$azvir_course = [];
		foreach ($course as $key => $value)
		{
			$course_id = self::fix($azvir->course('post', ['title' => $value]));
			if(isset($course_id['id']))
			{
				$azvir_course[$course_id['id']] = $value;
			}
			else
			{
				$course_id = self::fix($azvir->course_search('get', ['search' => $value]));
				if(isset($course_id[0]['id']))
				{
					$azvir_course[$course_id[0]['id']] = $value;
				}
				else
				{
					\lib\debug::warn("Can not add course $value");
				}
			}
		}

		$azvir_pricetype = [];
		foreach ($pricetype as $key => $value)
		{
			$pricetype_id = self::fix($azvir->pricetype('post', ['title' => $value, 'price' => $value, 'unittype' => 'real']));
			if(isset($pricetype_id['id']))
			{
				$azvir_pricetype[$pricetype_id['id']] = $value;
			}
			else
			{
				$pricetype_id = self::fix($azvir->pricetype_search('get', ['price' => $value]));

				if(isset($pricetype_id[0]['id']))
				{
					$azvir_pricetype[$pricetype_id[0]['id']] = $value;

				}
				else
				{
					\lib\debug::warn("Can not add pricetype $value");
				}
			}
		}

		$azvir_topic = [];
		foreach ($rows as $key => $value)
		{
			$insert_topic =
			[

				'grade_id'      => array_search($value['grade'], $azvir_grade),
				'group_id'      => array_search($value['group'], $azvir_group),
				'course_id'     => array_search($value['course'], $azvir_course),
				'pricetype_id'  => array_search($value['price'], $azvir_pricetype),
				'title'         => $value['title'],
				'totaltime'     => $value['otime'] == 'NULL' ? null : $value['otime'],
				'passscore'     => $value['omark'],
				'certification' => $value['osertification'] === 'no' ? false : true,
				'learnunit'     => 1,
			];

			$topic_id = self::fix($azvir->topic('post', $insert_topic), true, [$insert_topic, $value]);
			if(isset($topic_id['id']))
			{
				$azvir_topic[$topic_id['id']] = $value;
			}
			else
			{
				$search_topic =
				[
					'search'    => $value['title'],
					'grade_id'  => array_search($value['grade'], $azvir_grade),
					'group_id'  => array_search($value['group'], $azvir_group),
					'course_id' => array_search($value['course'], $azvir_course),
				];

				$topic_id = self::fix($azvir->topic_search('get', $search_topic));
				if(isset($topic_id[0]['id']))
				{
					$azvir_topic[$topic_id[0]['id']] = $value;
				}
				else
				{
					\lib\debug::warn('Can not add topic');
				}
			}
		}
		\lib\debug::true("حله. بریم بعدی");

		// var_dump($group, $grade, $course, $price);
		// var_dump($first_rows, $rows);
		// var_dump($old_plan);
		// exit();
	}


	public static function fix($_responce, $_var_dump = false, $_data = [])
	{

		$text = null;

		$text .= json_encode($_responce, JSON_UNESCAPED_UNICODE). "\n";
		$text .= json_encode($_data, JSON_UNESCAPED_UNICODE). "\n\n";

		file_put_contents(__DIR__. '/log',$text , FILE_APPEND);

		if(isset($_responce['result']) && $_responce['result'])
		{
			return $_responce['result'];
		}
		return null;
	}

}
?>