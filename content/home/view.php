<?php
namespace content\home;

class view extends \mvc\view
{
	function config()
	{
		$this->data->bodyclass = 'unselectable siftal';
		// $this->include->js     = false;

		$this->data->page['title']   = $this->data->site['title'] . ' | '. $this->data->site['slogan'];
		$this->data->page['special'] = true;
	}
}
?>