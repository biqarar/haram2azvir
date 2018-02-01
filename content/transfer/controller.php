<?php
namespace content\transfer;

class controller extends \content\main\controller
{

	// for routing check
	function ready()
	{

		$this->post('transfer')->ALL();
	}
}
?>