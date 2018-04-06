<?php
namespace content\main;


class controller extends \mvc\controller
{
	public function repository()
	{
		if(!\dash\user::id())
		{
			\dash\header::status(400, );
		}
	}

}
?>