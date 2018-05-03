<?php
namespace content\transfer\model;


trait classroom
{
	public static function classroom()
	{
		$azvir = new \dash\utility\ermile\azvir(azvir_api_key, azvir_api_school, 1);

		$result = \dash\utility\import::csv(__DIR__.'/madras.csv');

		$master_key = $active_class = array_column($result, 'fixed');
		$active_class = array_unique($active_class);
		$active_class = array_filter($active_class);

		$azvir_classroom = [];

		foreach ($active_class as $key => $value)
		{
			$mutli = array_search($value, $master_key);
			$multiclass = false;
			if(isset($result[$mutli]['multiclass']))
			{
				if($result[$mutli]['multiclass'] == 'yes')
				{
					$multiclass = true;
				}
			}

			$insert_classroom =
			[
				'title'      => $value,
				'desc'       => null,
				'status'     => 'enable',
				'multiclass' => $multiclass,
				'maxperson'  => null,
			];

			$classroom_id = self::fix($azvir->classroom('post', $insert_classroom));
			$new_id = null;
			if(isset($classroom_id['id']))
			{
				$azvir_classroom[$classroom_id['id']] = $value;
				$new_id = $classroom_id['id'];
			}
			else
			{
				$classroom_id = self::fix($azvir->classroom_search('get', ['search' => $value['name']]));
				if(isset($classroom_id[0]['id']))
				{
					$new_id = $classroom_id[0]['id'];
					$azvir_classroom[$classroom_id[0]['id']] = $value;
				}
				else
				{
					\dash\notif::warn("Can not add classroom $value[name]");
				}
			}
			if($new_id)
			{
				$ids = array_column($result, 'fixed', 'id');
				$all_ids = [];
				foreach ($ids as $place_id => $place_name)
				{
					if($value == $place_name)
					{
						$all_ids[] = $place_id;
					}
				}
				$all_ids = implode(',', $all_ids);
				\dash\db::query("UPDATE place set azvir_classroom_id = '$new_id' WHERE place.id IN ($all_ids) ", 'quran_hadith');
			}
		}
		\dash\notif::ok("تمام");
	}
}
?>