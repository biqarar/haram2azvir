<?php
namespace content;

class view
{
	public static function config()
	{
		// define default value for global

		\dash\data::site_title(T_("سامانه انتقال اطلاعات"));
		\dash\data::site_desc(T_("سامانه انتقال اطلاعات پورتال آموزشی مرکز قرآن و حدیث کریمه اهل بیت علیها السلام "));
		\dash\data::site_slogan(T_("سریع باش!"));

		\dash\data::include_css(false);

		// if you need to set a class for body element in html add in this value
		\dash\data::bodyclass(null);
	}
}
?>