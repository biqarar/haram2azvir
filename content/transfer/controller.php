<?php
namespace content\transfer;

class controller
{

	// for routing check
	public static function routing()
	{
		ini_set('memory_limit', '-1');

		if(!defined('azvir_api_key'))
		{
			define('azvir_api_key', '$2y$07$K6Jc0V2TqrZJ650sxiB9w.mbyEQ3yS4XJUE0lUcJQwqfKx7p5o6yi');
		}

		if(!defined('azvir_api_school'))
		{
			define('azvir_api_school', 'haram');
		}

		// $azvir = new \dash\utility\ermile\azvir('$2y$07$ppqEBLfiUT7zlqFVxp/Wa.ILdtNVkL/i1o2eAZ4Dm41mV9VlSiU86', 'haram', 1);
		// $add_group = $azvir->pricetype('post', ['title' => 'حفظ', 'price' => 2000, 'unittype' => 'real']);
		// var_dump($add_group);exit();

	}


}
?>