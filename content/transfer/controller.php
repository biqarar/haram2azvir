<?php
namespace content\transfer;

class controller extends \content\main\controller
{

	// for routing check
	function ready()
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

		$azvir = new \lib\utility\ermile\azvir('$2y$07$ppqEBLfiUT7zlqFVxp/Wa.ILdtNVkL/i1o2eAZ4Dm41mV9VlSiU86', 'haram', 1);
		foreach ($result as $key => $value)
		{
			// var_dump($value);
			$insert_member =
			[
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

			// var_dump($insert_member);exit();
			if(\lib\utility\nationalcode::check($value['nationalcode']))
			{
				$insert_member['nationalcode']    = $value['nationalcode'];
			}
			else
			{
				$insert_member['pasportcode']    = $value['nationalcode'];
			}


			$member_id = self::fix($azvir->member('post', $insert_member));
			if(isset($member_id['id']))
			{
				\lib\db::query("UPDATE person set azvir_member_id = '$member_id[id]' WHERE person.id = $value[id] LIMIT 1 ", null, false, 'quran_hadith');
			}
			else
			{
				\lib\debug::error(T_("نمیتونم کاربر رو اضافه کنم"));

			}
		}
		// $azvir = new \lib\utility\ermile\azvir('$2y$07$ppqEBLfiUT7zlqFVxp/Wa.ILdtNVkL/i1o2eAZ4Dm41mV9VlSiU86', 'haram', 1);
		// $add_group = $azvir->pricetype('post', ['title' => 'حفظ', 'price' => 2000, 'unittype' => 'real']);
		// var_dump($add_group);exit();

		$this->post('transfer')->ALL();
	}

	public static function fix($_responce, $_var_dump = false, $_data = [])
	{

		$text = null;

		$text .= json_encode($_responce, JSON_UNESCAPED_UNICODE). "\n";
		$text .= json_encode($_data, JSON_UNESCAPED_UNICODE). "\n\n";

		file_put_contents(__DIR__. '/log',$text , FILE_APPEND);

		if(isset($_responce['result']) && $_responce['result'])
		{
			return $_responce['result'];
		}
		return null;
	}
}
?>