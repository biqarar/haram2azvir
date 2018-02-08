<?php
namespace content\transfer;


class model extends \mvc\model
{
	use \content\transfer\model\planfile;
	use \content\transfer\model\removefakemobile;
	use \content\transfer\model\nationalcode;

	public function database_field_upgrade()
	{
		$query = [];

		$query[] = "ALTER TABLE `plan` ADD `azvir_topic_id` varchar(100) NULL DEFAULT NULL";
		$query[] = "ALTER TABLE `person` ADD `province_name` varchar(100) NULL DEFAULT NULL";
		$query[] = "ALTER TABLE `person` ADD `country_name` varchar(100) NULL DEFAULT NULL";

		foreach ($query as $key => $value)
		{
			\lib\db::query($value, 'quran_hadith');
		}
		\lib\debug::true("حله!");
	}

	public function post_transfer()
	{

		switch (\lib\utility::post('level'))
		{
			case 'sql':
				$this->database_field_upgrade();
				break;

			case 'planfile':
				$this->plan_file();
				break;

			case 'removefakemobile':
				$this->removefakemobile();
				break;

			case 'fixmobile':
				$this->fixmobile();
				break;

			case 'nationalcodeduplicate':
				$this->nationalcodeduplicate();
				break;

			case 'removeduplicatemobile':
				$this->removeduplicatemobile();
				break;

			default:
				\lib\debug::warn("نکن!");
				return false;
				break;
		}
	}

}
?>