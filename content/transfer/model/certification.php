<?php
namespace content\transfer\model;


trait certification
{
	public static function certification()
	{
		$query = [];

		$query[] = "ALTER TABLE `classification` ADD `azvir_topic_id` varchar(100) NULL DEFAULT NULL";
		$query[] = "UPDATE `classification` SET `azvir_topic_id` = (SELECT azvir_topic_id from classes where classes.id = classification.classes_id LIMIT 1)";


		$query[] = "ALTER TABLE `certification` ADD `azvir_topic_id` varchar(100) NULL DEFAULT NULL";
		$query[] = "UPDATE `certification` SET `azvir_topic_id` = (SELECT azvir_topic_id from classification where classification.id = certification.classification_id LIMIT 1)";



		foreach ($query as $key => $value)
		{
			\dash\db::query($value, 'quran_hadith');
		}
		// case 'certification': add new certification
		$args['name']   = $name;
		$args['status'] = 'enable';

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