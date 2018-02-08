<?php
namespace content\transfer\model;


trait personfix
{
	public function personfix()
	{
		$query = [];
		$query[] =
		"
			UPDATE
				person
			SET
				province_name =
				(
					SELECT
						CONCAT(province.name, ' - ', city.name)
					FROM
						city
					INNER JOIN province ON city.province_id = province.id
					WHERE city.id = person.from
				)
		";

		$query[] =
		"
			UPDATE
				person
			SET
				country_name =
				(
					SELECT
						country.name
					FROM
						country
					WHERE country.id = person.nationality
				)
		";

		$query[] =
		"
			UPDATE
				person
			SET
				education_name =
				CONCAT
				(
					(
						SELECT
							education.section
						FROM
							education
						WHERE education.id = person.education_id
					)
				,
					' '
				,
					(

						SELECT
							education.section
						FROM
							education
						WHERE education.id = person.education_howzah_id

					)
				)
		";

		// $query[] = "ALTER TABLE `person` ADD `education_name` varchar(100) NULL DEFAULT NULL";

		// $query[] = "ALTER TABLE `person` ADD `province_name` varchar(100) NULL DEFAULT NULL";
		// $query[] = "ALTER TABLE `person` ADD `country_name` varchar(100) NULL DEFAULT NULL";

		foreach ($query as $key => $value)
		{
			\lib\db::query($value, 'quran_hadith');
		}
		\lib\debug::true("اینم از این");
	}
}
?>