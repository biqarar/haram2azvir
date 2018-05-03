<?php
namespace content\transfer;


class model
{
	use \content\transfer\model\planfile;
	use \content\transfer\model\removefakemobile;
	use \content\transfer\model\nationalcode;
	use \content\transfer\model\personfix;
	use \content\transfer\model\student;
	use \content\transfer\model\lesson;
	use \content\transfer\model\classroom;
	use \content\transfer\model\price;


	public static function fix($_responce, $_var_dump = false, $_data = [])
	{

		$text = null;

		$text .= json_encode($_responce, JSON_UNESCAPED_UNICODE). "\n";
		$text .= json_encode($_data, JSON_UNESCAPED_UNICODE). "\n\n";

		if(array_key_exists('ok', $_responce))
		{
			if(!$_responce['ok'])
			{
				file_put_contents(__DIR__. '/model/log',$text , FILE_APPEND);
			}
		}

		if(isset($_responce['result']) && $_responce['result'])
		{
			return $_responce['result'];
		}
		return $_responce;
	}

	public static function database_field_upgrade()
	{
		$query = [];

		$query[] = "ALTER TABLE `plan` ADD `azvir_topic_id` varchar(100) NULL DEFAULT NULL";
		$query[] = "ALTER TABLE `person` ADD `city_name` varchar(100) NULL DEFAULT NULL";
		$query[] = "ALTER TABLE `person` ADD `province_name` varchar(100) NULL DEFAULT NULL";
		$query[] = "ALTER TABLE `person` ADD `country_name` varchar(100) NULL DEFAULT NULL";
		$query[] = "ALTER TABLE `person` ADD `education_name` varchar(200) NULL DEFAULT NULL";
		$query[] = "ALTER TABLE `person` ADD `education_name2` varchar(200) NULL DEFAULT NULL";
		$query[] = "ALTER TABLE `person` ADD `azvir_member_id` varchar(200) NULL DEFAULT NULL";
		$query[] = "ALTER TABLE `person` ADD `azvir_teacher_id` varchar(200) NULL DEFAULT NULL";
		$query[] = "ALTER TABLE `person` ADD `azvir_expert_id` varchar(200) NULL DEFAULT NULL";
		$query[] = "ALTER TABLE `classes` ADD `azvir_semester_id` varchar(200) NULL DEFAULT NULL";
		$query[] = "ALTER TABLE `classes` ADD `azvir_lesson_id` varchar(200) NULL DEFAULT NULL";
		$query[] = "ALTER TABLE `place` ADD `azvir_classroom_id` varchar(200) NULL DEFAULT NULL";

		foreach ($query as $key => $value)
		{
			\dash\db::query($value, 'quran_hadith');
		}
		\dash\notif::ok("حله!");
	}

	public static function post()
	{
		if(\dash\request::get('level') != \dash\request::post('level'))
		{
			\dash\redirect::to(\dash\url::base(). '/transfer?level='. \dash\request::post('level'));
			return;
		}

		switch (\dash\request::post('level'))
		{
			case 'sql':
				self::database_field_upgrade();
				break;

			case 'planfile':
				self::plan_file();
				break;

			case 'removefakemobile':
				self::removefakemobile();
				break;

			case 'fixmobile':
				self::fixmobile();
				break;

			case 'nationalcodeduplicate':
				self::nationalcodeduplicate();
				break;

			case 'removeduplicatemobile':
				self::removeduplicatemobile();
				break;

			case 'personfix':
				self::personfix();
				break;

			case 'student':
				self::student();
				break;

			case 'classroom':
				self::classroom();
				break;


			case 'lesson':
				self::lesson();
				break;

			case 'teacher':
				self::student('teacher');
				break;

			case 'operator':
				self::student('operator');
				break;

			case 'price':
				self::price_calc();
				break;

			default:
				\dash\notif::warn("نکن!");
				return false;
				break;
		}
	}

}
?>