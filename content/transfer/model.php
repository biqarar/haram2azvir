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

		foreach ($query as $key => $value)
		{
			\lib\db::query($value, 'quran_hadith');
		}

	}

	public function post_transfer()
	{
		$this->database_field_upgrade();

		switch (\lib\utility::post('level'))
		{
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

			default:
				\lib\debug::warn("نکن!");
				return false;
				break;
		}
	}

}
?>