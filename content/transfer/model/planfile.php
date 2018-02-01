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

		$price  = array_column($rows, 'price');
		$price  = array_filter($price);
		$price  = array_unique($price);
		$price  = array_values($price);


		var_dump($group, $grade, $course, $price);
		// var_dump($first_rows, $rows);
		// var_dump($old_plan);
		exit();
	}

}
?>