<?php
namespace content\transfer\model;


trait student
{
	public static function student($_type = null)
	{
		if($_type === 'operator')
		{
			$query =
			"
				SELECT
					person.*
				FROM
					person
				INNER JOIN users_branch ON users_branch.users_id = person.users_id
				WHERE
					users_branch.type = 'operator'
			";
		}
		elseif($_type === 'teacher')
		{
			$query =
			"
				SELECT
					person.*
				FROM
					person
				INNER JOIN users_branch ON users_branch.users_id = person.users_id
				WHERE
					users_branch.type = 'teacher'
			";
		}
		else
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
				WHERE
					person.azvir_member_id IS NULL

			";

		}



		$result = \dash\db::get($query, null, false, 'quran_hadith');

		$type = 'student';
		if($_type === 'teacher')
		{
			$type = 'teacher';
		}
		elseif($_type === 'operator')
		{
			$type = 'expert';
		}

		if(!$result)
		{
			\dash\notif::ok("همه رفتند");
			return false;
		}

		$azvir = new \dash\utility\ermile\azvir(azvir_api_key, azvir_api_school, 1);

		foreach ($result as $key => $value)
		{
			$patch =
			[
				'id'  => $value['azvir_member_id'],
				$type => 1,
			];

			$xpatch = $azvir->member('patch', $patch);
		}

		\dash\notif::ok("همه رفتند");
		return false;

		/// need less to this code after send all user

		foreach ($result as $key => $value)
		{

			$city_name     = $value['city_name'] ? self::find_place($value['city_name'], 'city') : null ;
			$province_name = $value['province_name'] ? self::find_place($value['province_name'], 'province') : null ;
			$country_name  = $value['country_name'] ? self::find_place($value['country_name'], 'country') : null ;

			if($country_name)
			{
				$country_name = str_replace(' ', '-', $country_name);
			}

			$birthdate = \dash\date::db($value['birthday']);
			if($birthdate === false)
			{
				$birthdate = null;
			}


			$passportdate = \dash\date::db($value['pasport_date']);
			if($passportdate === false)
			{
				$passportdate = null;
			}

			// $city_name     = null;
			// $province_name = null;
			// $country_name  = null;

			$insert_member =
			[
				'force_add'       => true,
				$type             => 1,
				'mobile'          => $value['mobile'],
				'email'           => $value['email'],
				'shfrom'          => $province_name,
				'foreign'         => intval($value['nationality']) === 97 ? false : true ,

				'firstname'       => $value['name'],
				'lastname'        => $value['family'],
				'father'          => $value['father'],
				'birthdate'       => $birthdate,
				'pasportdate'     => $passportdate,
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
				'city'            => $city_name,
				'province'        => $province_name,
				'country'         => $country_name,
				'nationality'     => $country_name,
				'address'         => null,
				'phone'           => $value['phone'],
				'mobile2'         => null,
				'fathermobile'    => null,
				'mothermobile'    => null,
				'status'          => 'awaiting',
				'desc'            => null,
			];

			$insert_member['code'] = $value['username'];


			if(\dash\utility\filter::nationalcode($value['nationalcode'])  && intval($value['nationality']) === 97 )
			{
				$insert_member['nationalcode'] = $value['nationalcode'];
				$insert_member['foreign']      = false;
			}
			else
			{
				$insert_member['pasportcode'] = $value['nationalcode'];
				$insert_member['foreign']     = true;
			}

			$xazvir = $azvir->member('post', $insert_member);
			$member_id = self::fix($xazvir, true ,[$value, $insert_member]);

			if(isset($member_id['member_id']))
			{
				if($type === 'expert')
				{
					$field = 'azvir_expert_id';
				}
				elseif($type === 'teacher')
				{
					$field = 'azvir_teacher_id';
				}
				else
				{
					$field = 'azvir_member_id';
				}

				\dash\db::query("UPDATE person set $field = '$member_id[member_id]' WHERE person.id = $value[id] LIMIT 1 ", 'quran_hadith');
			}
			else
			{
				$meta = ["nationalcode","pasportcode"];
				if(isset($member_id['msg'][0]['meta']) && $member_id['msg'][0]['meta'] == $meta)
				{
					$xx = $azvir->member_search('get', ['search' => $value['nationalcode']]);

					if(isset($xx['result'][0]))
					{
						$xx = $xx['result'][0];
					}

					if(array_key_exists($type, $xx))
					{

						$patch =
						[
							'id'  => $xx['id'],
							$type => 1,
						];

						$xpatch = $azvir->member('patch', $patch);
						$text = json_encode($patch, JSON_UNESCAPED_UNICODE);

						if($type === 'expert')
						{
							$field = 'azvir_expert_id';
						}
						elseif($type === 'teacher')
						{
							$field = 'azvir_teacher_id';
						}
						else
						{
							$field = 'azvir_member_id';
						}

						\dash\db::query("UPDATE person set $field = '$xx[id]' WHERE person.id = $value[id] LIMIT 1 ", 'quran_hadith');

					}

				}

				\dash\notif::error(T_("نمیتونم کاربر رو اضافه کنم"));

			}

		}
	}


	public static function find_place($_name, $_type)
	{
		$key = null;
		switch ($_type)
		{
			case 'city':
				$key = \dash\utility\location\cites::get_key($_name);
				break;

			case 'country':
				$trans =
				[
					"afghanistan"                                  => T_("afghanistan"),
					"åland"                                        => T_("åland"),
					"albania"                                      => T_("albania"),
					"algeria"                                      => T_("algeria"),
					"american samoa"                               => T_("american samoa"),
					"andorra"                                      => T_("andorra"),
					"angola"                                       => T_("angola"),
					"anguilla"                                     => T_("anguilla"),
					"antarctica"                                   => T_("antarctica"),
					"antigua and barbuda"                          => T_("antigua and barbuda"),
					"argentina"                                    => T_("argentina"),
					"armenia"                                      => T_("armenia"),
					"aruba"                                        => T_("aruba"),
					"australia"                                    => T_("australia"),
					"austria"                                      => T_("austria"),
					"azerbaijan"                                   => T_("azerbaijan"),
					"bahamas"                                      => T_("bahamas"),
					"bahrain"                                      => T_("bahrain"),
					"bangladesh"                                   => T_("bangladesh"),
					"barbados"                                     => T_("barbados"),
					"belarus"                                      => T_("belarus"),
					"belgium"                                      => T_("belgium"),
					"belize"                                       => T_("belize"),
					"benin"                                        => T_("benin"),
					"bermuda"                                      => T_("bermuda"),
					"bhutan"                                       => T_("bhutan"),
					"bolivia"                                      => T_("bolivia"),
					"bonaire"                                      => T_("bonaire"),
					"bosnia and herzegovina"                       => T_("bosnia and herzegovina"),
					"botswana"                                     => T_("botswana"),
					"bouvet island"                                => T_("bouvet island"),
					"brazil"                                       => T_("brazil"),
					"british indian ocean territory"               => T_("british indian ocean territory"),
					"british virgin islands"                       => T_("british virgin islands"),
					"brunei"                                       => T_("brunei"),
					"bulgaria"                                     => T_("bulgaria"),
					"burkina faso"                                 => T_("burkina faso"),
					"burundi"                                      => T_("burundi"),
					"cambodia"                                     => T_("cambodia"),
					"cameroon"                                     => T_("cameroon"),
					"canada"                                       => T_("canada"),
					"cape verde"                                   => T_("cape verde"),
					"cayman islands"                               => T_("cayman islands"),
					"central african republic"                     => T_("central african republic"),
					"chad"                                         => T_("chad"),
					"chile"                                        => T_("chile"),
					"china"                                        => T_("china"),
					"christmas island"                             => T_("christmas island"),
					"cocos (keeling) islands"                      => T_("cocos (keeling) islands"),
					"colombia"                                     => T_("colombia"),
					"comoros"                                      => T_("comoros"),
					"cook islands"                                 => T_("cook islands"),
					"costa rica"                                   => T_("costa rica"),
					"croatia"                                      => T_("croatia"),
					"cuba"                                         => T_("cuba"),
					"curacao"                                      => T_("curacao"),
					"cyprus"                                       => T_("cyprus"),
					"czech republic"                               => T_("czech republic"),
					"democratic republic of the congo"             => T_("democratic republic of the congo"),
					"denmark"                                      => T_("denmark"),
					"djibouti"                                     => T_("djibouti"),
					"dominica"                                     => T_("dominica"),
					"dominican republic"                           => T_("dominican republic"),
					"east timor"                                   => T_("east timor"),
					"ecuador"                                      => T_("ecuador"),
					"egypt"                                        => T_("egypt"),
					"el salvador"                                  => T_("el salvador"),
					"equatorial guinea"                            => T_("equatorial guinea"),
					"eritrea"                                      => T_("eritrea"),
					"estonia"                                      => T_("estonia"),
					"ethiopia"                                     => T_("ethiopia"),
					"falkland islands"                             => T_("falkland islands"),
					"faroe islands"                                => T_("faroe islands"),
					"fiji"                                         => T_("fiji"),
					"finland"                                      => T_("finland"),
					"france"                                       => T_("france"),
					"french guiana"                                => T_("french guiana"),
					"french polynesia"                             => T_("french polynesia"),
					"french southern territories"                  => T_("french southern territories"),
					"gabon"                                        => T_("gabon"),
					"gambia"                                       => T_("gambia"),
					"georgia"                                      => T_("georgia"),
					"germany"                                      => T_("germany"),
					"ghana"                                        => T_("ghana"),
					"gibraltar"                                    => T_("gibraltar"),
					"greece"                                       => T_("greece"),
					"greenland"                                    => T_("greenland"),
					"grenada"                                      => T_("grenada"),
					"guadeloupe"                                   => T_("guadeloupe"),
					"guam"                                         => T_("guam"),
					"guatemala"                                    => T_("guatemala"),
					"guernsey"                                     => T_("guernsey"),
					"guinea"                                       => T_("guinea"),
					"guinea-bissau"                                => T_("guinea-bissau"),
					"guyana"                                       => T_("guyana"),
					"haiti"                                        => T_("haiti"),
					"heard island and mcdonald islands"            => T_("heard island and mcdonald islands"),
					"honduras"                                     => T_("honduras"),
					"hong kong"                                    => T_("hong kong"),
					"hungary"                                      => T_("hungary"),
					"iceland"                                      => T_("iceland"),
					"india"                                        => T_("india"),
					"indonesia"                                    => T_("indonesia"),
					"iraq"                                         => T_("iraq"),
					"ireland"                                      => T_("ireland"),
					"isle of man"                                  => T_("isle of man"),
					"italy"                                        => T_("italy"),
					"ivory coast"                                  => T_("ivory coast"),
					"jamaica"                                      => T_("jamaica"),
					"japan"                                        => T_("japan"),
					"jersey"                                       => T_("jersey"),
					"jordan"                                       => T_("jordan"),
					"kazakhstan"                                   => T_("kazakhstan"),
					"kenya"                                        => T_("kenya"),
					"kiribati"                                     => T_("kiribati"),
					"kosovo"                                       => T_("kosovo"),
					"kuwait"                                       => T_("kuwait"),
					"kyrgyzstan"                                   => T_("kyrgyzstan"),
					"laos"                                         => T_("laos"),
					"latvia"                                       => T_("latvia"),
					"lebanon"                                      => T_("lebanon"),
					"lesotho"                                      => T_("lesotho"),
					"liberia"                                      => T_("liberia"),
					"libya"                                        => T_("libya"),
					"liechtenstein"                                => T_("liechtenstein"),
					"lithuania"                                    => T_("lithuania"),
					"luxembourg"                                   => T_("luxembourg"),
					"macau"                                        => T_("macau"),
					"macedonia"                                    => T_("macedonia"),
					"madagascar"                                   => T_("madagascar"),
					"malawi"                                       => T_("malawi"),
					"malaysia"                                     => T_("malaysia"),
					"maldives"                                     => T_("maldives"),
					"mali"                                         => T_("mali"),
					"malta"                                        => T_("malta"),
					"marshall islands"                             => T_("marshall islands"),
					"martinique"                                   => T_("martinique"),
					"mauritania"                                   => T_("mauritania"),
					"mauritius"                                    => T_("mauritius"),
					"mayotte"                                      => T_("mayotte"),
					"mexico"                                       => T_("mexico"),
					"micronesia"                                   => T_("micronesia"),
					"moldova"                                      => T_("moldova"),
					"monaco"                                       => T_("monaco"),
					"mongolia"                                     => T_("mongolia"),
					"montenegro"                                   => T_("montenegro"),
					"montserrat"                                   => T_("montserrat"),
					"morocco"                                      => T_("morocco"),
					"mozambique"                                   => T_("mozambique"),
					"myanmar (burma)"                              => T_("myanmar (burma)"),
					"namibia"                                      => T_("namibia"),
					"nauru"                                        => T_("nauru"),
					"nepal"                                        => T_("nepal"),
					"netherlands"                                  => T_("netherlands"),
					"new caledonia"                                => T_("new caledonia"),
					"new zealand"                                  => T_("new zealand"),
					"nicaragua"                                    => T_("nicaragua"),
					"niger"                                        => T_("niger"),
					"nigeria"                                      => T_("nigeria"),
					"niue"                                         => T_("niue"),
					"norfolk island"                               => T_("norfolk island"),
					"north korea"                                  => T_("north korea"),
					"northern mariana islands"                     => T_("northern mariana islands"),
					"norway"                                       => T_("norway"),
					"oman"                                         => T_("oman"),
					"pakistan"                                     => T_("pakistan"),
					"palau"                                        => T_("palau"),
					"palestine"                                    => T_("palestine"),
					"panama"                                       => T_("panama"),
					"papua new guinea"                             => T_("papua new guinea"),
					"paraguay"                                     => T_("paraguay"),
					"peru"                                         => T_("peru"),
					"philippines"                                  => T_("philippines"),
					"pitcairn islands"                             => T_("pitcairn islands"),
					"poland"                                       => T_("poland"),
					"portugal"                                     => T_("portugal"),
					"puerto rico"                                  => T_("puerto rico"),
					"qatar"                                        => T_("qatar"),
					"republic of the congo"                        => T_("republic of the congo"),
					"réunion"                                      => T_("réunion"),
					"romania"                                      => T_("romania"),
					"russia"                                       => T_("russia"),
					"rwanda"                                       => T_("rwanda"),
					"saint barthélemy"                             => T_("saint barthélemy"),
					"saint helena"                                 => T_("saint helena"),
					"saint kitts and nevis"                        => T_("saint kitts and nevis"),
					"saint lucia"                                  => T_("saint lucia"),
					"saint martin"                                 => T_("saint martin"),
					"saint pierre and miquelon"                    => T_("saint pierre and miquelon"),
					"saint vincent and the grenadines"             => T_("saint vincent and the grenadines"),
					"samoa"                                        => T_("samoa"),
					"san marino"                                   => T_("san marino"),
					"são tomé and príncipe"                        => T_("são tomé and príncipe"),
					"saudi arabia"                                 => T_("saudi arabia"),
					"senegal"                                      => T_("senegal"),
					"serbia"                                       => T_("serbia"),
					"seychelles"                                   => T_("seychelles"),
					"sierra leone"                                 => T_("sierra leone"),
					"singapore"                                    => T_("singapore"),
					"sint maarten"                                 => T_("sint maarten"),
					"slovakia"                                     => T_("slovakia"),
					"slovenia"                                     => T_("slovenia"),
					"solomon islands"                              => T_("solomon islands"),
					"somalia"                                      => T_("somalia"),
					"south africa"                                 => T_("south africa"),
					"south georgia and the south sandwich islands" => T_("south georgia and the south sandwich islands"),
					"south korea"                                  => T_("south korea"),
					"south sudan"                                  => T_("south sudan"),
					"spain"                                        => T_("spain"),
					"sri lanka"                                    => T_("sri lanka"),
					"sudan"                                        => T_("sudan"),
					"suriname"                                     => T_("suriname"),
					"svalbard and jan mayen"                       => T_("svalbard and jan mayen"),
					"swaziland"                                    => T_("swaziland"),
					"sweden"                                       => T_("sweden"),
					"switzerland"                                  => T_("switzerland"),
					"syria"                                        => T_("syria"),
					"taiwan"                                       => T_("taiwan"),
					"tajikistan"                                   => T_("tajikistan"),
					"tanzania"                                     => T_("tanzania"),
					"thailand"                                     => T_("thailand"),
					"togo"                                         => T_("togo"),
					"tokelau"                                      => T_("tokelau"),
					"tonga"                                        => T_("tonga"),
					"trinidad and tobago"                          => T_("trinidad and tobago"),
					"tunisia"                                      => T_("tunisia"),
					"turkey"                                       => T_("turkey"),
					"turkmenistan"                                 => T_("turkmenistan"),
					"turks and caicos islands"                     => T_("turks and caicos islands"),
					"tuvalu"                                       => T_("tuvalu"),
					"minor outlying islands"                       => T_("minor outlying islands"),
					"uganda"                                       => T_("uganda"),
					"ukraine"                                      => T_("ukraine"),
					"united arab emirates"                         => T_("united arab emirates"),
					"united kingdom"                               => T_("united kingdom"),
					"united states"                                => T_("united states"),
					"uruguay"                                      => T_("uruguay"),
					"uzbekistan"                                   => T_("uzbekistan"),
					"vanuatu"                                      => T_("vanuatu"),
					"vatican city"                                 => T_("vatican city"),
					"venezuela"                                    => T_("venezuela"),
					"vietnam"                                      => T_("vietnam"),
					"wallis and futuna"                            => T_("wallis and futuna"),
					"western sahara"                               => T_("western sahara"),
					"yemen"                                        => T_("yemen"),
					"zambia"                                       => T_("zambia"),
					"zimbabwe"                                     => T_("zimbabwe"),

				];
				$key = array_search($_name, $trans);
				$key = $key ? $key : null;
				// $country                 =
				// [
				// 'آذربایجان'              => null,
				// 'آرژانتین'               => null,
				// 'آروبا'                  => null,
				// 'آفریقای جنوبی'          => null,
				// 'آلبانی'                 => null,
				// 'آلمان'                  => null,
				// 'آمریکا'                 => null,
				// 'آنتیگوا و باربودا'      => null,
				// 'اتیوپی'                 => null,
				// 'ارمنستان'               => null,
				// 'استرالیا'               => null,
				// 'افغانستان'              => null,
				// 'امارات متحده عربی'      => null,
				// 'اندونزی'                => null,
				// 'انگلستان'               => null,
				// 'اوکراین'                => null,
				// 'اوگاندا'                => null,
				// 'ایالات فدرال میکرونزیی' => null,
				// 'ایتالیا'                => null,
				// 'ایران'                  => null,
				// 'ایرلند'                 => null,
				// 'باربادوس'               => null,
				// 'بحرین'                  => null,
				// 'بلژیک'                  => null,
				// 'بنگلادش'                => null,
				// 'بورکینافاسو'            => null,
				// 'پاکستان'                => null,
				// 'تاجیکستان'              => null,
				// 'تانزانیا'               => null,
				// 'ترکیه'                  => null,
				// 'تونس'                   => null,
				// 'رواندا'                 => null,
				// 'روسیه'                  => null,
				// 'ساحل عاج'               => null,
				// 'سوریه'                  => null,
				// 'سوید'                   => null,
				// 'سیرالیون'               => null,
				// 'عراق'                   => null,
				// 'عربستان سعودی'          => null,
				// 'فرانسه'                 => null,
				// 'قزاقستان'               => null,
				// 'کانادا'                 => null,
				// 'کویت'                   => null,
				// 'لبنان'                  => null,
				// 'مالزی'                  => null,
				// 'نیجریه'                 => null,
				// 'نیوزیلند'               => null,
				// 'هند'                    => null,
				// 'هندوراس'                => null,
				// ];
				break;

			case 'province':
				$key = \dash\utility\location\provinces::get_key($_name);
				break;

		}
		return $key;
	}
}
?>