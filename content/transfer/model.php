<?php
namespace content\transfer;


class model extends \mvc\model
{
	use \content\transfer\model\planfile;
	use \content\transfer\model\removefakemobile;


	public function post_transfer()
	{
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