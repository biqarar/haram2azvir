<?php
namespace content\transfer\model;


trait removefakemobile
{

	public function removefakemobile($_fix = false)
	{
		$all_mobile = "SELECT bridge.id AS `id`, bridge.value AS `mobile` from bridge WHERE bridge.title = 'mobile' ";
		$all_mobile = \lib\db::get($all_mobile, ['id', 'mobile'], false, 'quran_hadith');
		$count_all = count($all_mobile);

		$fix_mobile = [];
		$must_remove = [];

		foreach ($all_mobile as $key => $value)
		{
			$temp = \lib\utility\filter::mobile($value);
			if($temp)
			{
				$fix_mobile[$key] = $temp;
			}
			else
			{
				$must_remove[$key] = $value;
			}
		}

		if($_fix)
		{
			$query_update = [];
			foreach ($fix_mobile as $key => $value)
			{
				$query_update[] = "UPDATE bridge SET bridge.value = '$value' WHERE bridge.id = '$key' LIMIT 1";
			}

			$count = count($query_update);
			$chunk = array_chunk($query_update, 500);

			if($query_update)
			{
				foreach ($chunk as $key => $value)
				{
					$run_update_query = implode(' ; ', array_values($value));

					\lib\db::query($run_update_query, 'quran_hadith', ['multi_query' => true]);
				}
				\lib\debug::true("درستش کردم تعداد موبایل هایی که درست کردم  = " . (string) $count);

			}
			else
			{
				\lib\debug::true("همه موبایل ها قبلا درست شده بودند");
			}

		}
		else
		{
			$ids = array_keys($must_remove);

			if($ids)
			{
				$ids = implode(',', $ids);
				\lib\db::query("DELETE FROM bridge WHERE id IN ($ids) ", 'quran_hadith');
			}

			\lib\debug::warn("موبایل های خراب =  ". (string) (count($all_mobile) - count($fix_mobile)));

		}

	}


	public function fixmobile()
	{
		$this->removefakemobile(true);
	}

}
?>