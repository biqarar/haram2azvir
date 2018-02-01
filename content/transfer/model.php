<?php
namespace content\transfer;


class model extends \mvc\model
{
	use \content\transfer\model\planfile;


	public function post_transfer()
	{
		switch (\lib\utility::post('level'))
		{
			case 'planfile':
				$this->plan_file();
				return;
				# code...
				break;

			default:
				\lib\debug::warn("نکن!");
				return false;
				break;
		}
	}

}
?>