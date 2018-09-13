<?php
namespace content\transfer\model;


trait certification
{
	public static function certification()
	{
		$query = [];

		// $query[] = "ALTER TABLE `classification` ADD `azvir_topic_id` varchar(100) NULL DEFAULT NULL";
		// $query[] = "UPDATE `classification` SET `azvir_topic_id` = (SELECT azvir_topic_id from classes where classes.id = classification.classes_id LIMIT 1)";

		// $query[] = "ALTER TABLE `classification` ADD `plan_id` varchar(100) NULL DEFAULT NULL";
		// $query[] = "UPDATE `classification` SET `plan_id` = (SELECT plan_id from classes where classes.id = classification.classes_id LIMIT 1)";

		// $query[] = "ALTER TABLE `certification` ADD `plan_id` varchar(100) NULL DEFAULT NULL";
		// $query[] = "UPDATE `certification` SET `plan_id` = (SELECT plan_id from classification where classification.id = certification.classification_id LIMIT 1)";


		// $query[] = "ALTER TABLE `certification` ADD `azvir_member_id` varchar(100) NULL DEFAULT NULL";
		// $query[] = "UPDATE `certification` SET `azvir_member_id` = (SELECT azvir_member_id from classification where classification.id = certification.classification_id LIMIT 1)";

		// $query[] = "ALTER TABLE `certification` ADD `plan_name` varchar(200) NULL DEFAULT NULL";
		// $query[] = "UPDATE `certification` SET `plan_name` = (SELECT plan.name from plan where plan.id = certification.plan_id LIMIT 1)";



		// foreach ($query as $key => $value)
		// {
		// 	\dash\db::query($value, 'quran_hadith');
		// }

		$azvir = new \dash\utility\ermile\azvir(azvir_api_key, azvir_api_school, 1);

		$query = "SELECT azvir_member_id, GROUP_CONCAT(plan_name) AS `X` FROM certification group by azvir_member_id ";
		$result = \dash\db::get($query, null, false, 'quran_hadith');
		foreach ($result as $key => $value)
		{
			$patch =
			[
				'id'  => $value['azvir_member_id'],
				'desc' => "گواهی‌نامه‌های ثبت شده در پورتال قدیم: ". $value['X'],
			];

			$xpatch = $azvir->member('patch', $patch);
		}

		// // case 'certification': add new certification
		// $args['name']   = $name;
		// $args['status'] = 'enable';

		// case 'certification/detail': add certification topic id
		// 		if(isset($input['topic_id']) && isset($input['certification_id']))
		// 		{
		// 			$result = \lib\app\certificationdetail::add($input['certification_id'], $input['topic_id']);
		// 		}
		// 		break;

		\dash\notif::ok("حله!");
		var_dump(122);exit();
	}
}
?>