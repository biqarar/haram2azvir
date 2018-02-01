<?php
namespace content\transfer;


class model extends \mvc\model
{
	private static function xTrim($_array)
	{
		return array_map(function($_a){return trim(trim($_a), '"');}, $_array);
	}

	public function post_transfer()
	{
		$plan_file = \lib\utility::files('plan');
		if(isset($plan_file['tmp_name']))
		{
			$plan_file = @\lib\utility\file::read($plan_file['tmp_name']);
		}
		else
		{
			\lib\debug::error(T_("فایلی وارد نشده است"));
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
				$rows[] = $value;
			}
		}
		var_dump($first_rows, $rows);
		var_dump($old_plan);exit();
	}

}
?>