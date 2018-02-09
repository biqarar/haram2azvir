<?php
namespace content\transfer\model;


trait student
{
	public function student()
	{

		$query =
		"
			SELECT
				person.*,
				(SELECT bridge.value from bridge where bridge.users_id = person.users_id AND bridge.title  = 'mobile' LIMIT 1) AS `mobile`,
				(SELECT bridge.value from bridge where bridge.users_id = person.users_id AND bridge.title  = 'phone' LIMIT 1) AS `phone`,
				(SELECT bridge.value from bridge where bridge.users_id = person.users_id AND bridge.title  = 'email' LIMIT 1) AS `email`,
				(SELECT users.username from users where users.id = person.users_id ) AS `username`
			FROM
				person



		";

		$result = \lib\db::get($query, null, false, 'quran_hadith');

		$azvir = new \lib\utility\ermile\azvir(azvir_api_key, 'haram', 1);
		foreach ($result as $key => $value)
		{

			$insert_member =
			[
				'force_add'       => true,
				'type'            => 'student',
				'mobile'          => $value['mobile'],
				'email'           => $value['email'],
				'shfrom'          => $value['province_name'],
				'foreign'         => intval($value['nationality']) === 97 ? false : true ,

				'firstname'       => $value['name'],
				'lastname'        => $value['family'],
				'father'          => $value['father'],
				'birthdate'       => $value['birthday'],
				'pasportdate'     => $value['pasport_date'],
				'gender'          => $value['gender'],
				'marital'         => $value['marriage'],
				'shcode'          => $value['code'],
				'code'            => $value['username'],

				'birthcity'       => null,
				'zipcode'         => null,
				'religion'        => null,
				'avatar'          => null,

				'education'       => $value['education_name'],
				'education2'      => $value['education_name2'],

				'educationcourse' => null,
				'city'            => $value['city_name'],
				'province'        => $value['province_name'],
				'country'         => $value['country_name'],
				'address'         => null,
				'phone'           => $value['phone'],
				'mobile2'         => null,
				'fathermobile'    => null,
				'mothermobile'    => null,
				'status'          => 'awaiting',
				'desc'            => null,
			];


			if(\lib\utility\nationalcode::check($value['nationalcode']))
			{
				$insert_member['nationalcode']    = $value['nationalcode'];
			}
			else
			{
				$insert_member['pasportcode']    = $value['nationalcode'];
			}

			$xazvir = $azvir->member('post', $insert_member);

			$member_id = self::fix($xazvir);
			if(isset($member_id['member_id']))
			{
				\lib\db::query("UPDATE person set azvir_member_id = '$member_id[member_id]' WHERE person.id = $value[id] LIMIT 1 ", 'quran_hadith');
			}
			else
			{
				\lib\debug::error(T_("نمیتونم کاربر رو اضافه کنم"));

			}

		}
	}
}
?>