<?php
namespace content\main;


class controller extends \mvc\controller
{
	public function repository()
	{
		if(!\lib\user::id())
		{
			\lib\error::bad();
		}
	}

}
?>