<?php
namespace content\transfer\model;


trait price
{
	public static function price_calc()
	{
		// $q = "ALTER TABLE `price` ADD `plus` INT(10) NOT NULL AFTER `visible`;";
		// \dash\db::query($q, 'quran_hadith');

		// $q = "ALTER TABLE `price` ADD `minus` INT(10) NOT NULL AFTER `visible`;";
		// \dash\db::query($q, 'quran_hadith');

		$x = "SELECT id from price_change WHERE `type` = 'price_add'";
		$ids_plus = \dash\db::get($x, 'id', null , 'quran_hadith');
		$ids_plus = implode(',', $ids_plus);


		$x = "SELECT id from price_change WHERE `type` = 'price_low'";
		$ids_low = \dash\db::get($x, 'id', null , 'quran_hadith');
		$ids_low = implode(',', $ids_low);


		$q = "UPDATE price SET price.plus = price.value WHERE price.title IN ($ids_plus) ";
		\dash\db::query($q, 'quran_hadith');

		$q = "UPDATE price SET price.minus = price.value WHERE price.title IN ($ids_low) ";
		\dash\db::query($q, 'quran_hadith');

		$query =
		"
			SELECT
			((SUM(price.plus) - SUM(price.minus)) / 10) AS `total`,
			price.users_id,
			users.username,
			person.name,
			person.family,
			person.gender

			FROM price
			inner join users on users.id = price.users_id
			inner join person on person.users_id = price.users_id
			GROUP BY price.users_id , users.username, person.name, person.family, person.gender
			ORDER BY total DESC LIMIT 100
		";
		$result = \dash\db::get($query, null, false, 'quran_hadith');
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
		exit();
		var_dump($result);exit();




	}
}