<?php
namespace content\transfer\model;


trait lesson
{
	public static function lesson()
	{
		$query =
		"
			SELECT
				*
			FROM
				classes
			ORDER BY classes.start_date ASC

		";
		$result = \dash\db::get($query, null, false , 'quran_hadith');

		$semester_name =
		[
			1 => "تابستان",
			2 => "پاییز",
			3 => "زمستان",
		];

		$month =
		[
			1 => 3,
			2 => 3,

			3 => 1,
			4 => 1,
			5 => 1,
			6 => 1,

			7 => 2,
			8 => 2,
			9 => 2,

			10 => 3,
			11 => 3,
			12 => 3,
		];

		$semester_start =
		[
			1 => "0301",
			2 => "0701",
			3 => "1001",
		];

		$semester_end =
		[
			1 => "0631",
			2 => "0930",
			3 => "0231",
		];

		$azvir = new \dash\utility\ermile\azvir(azvir_api_key, azvir_api_school, 1);

		$azvir_semester = [];

		foreach ($result as $key => $value)
		{
			$start_date = $value['start_date'];
			$start_date_month = strtotime($start_date);
			if(!$start_date_month)
			{
				\dash\notif::error(T_("Can not set the time"));
				continue;
			}

			$start_date_month = date("m", strtotime($start_date));
			$start_date_month = intval($start_date_month);

			$semester_name_temp = $semester_name[$month[$start_date_month]];

			$endyear = $year = date("Y", strtotime($start_date));
			if($month[$start_date_month] == 3)
			{
				$endyear = intval($year) + 1;
			}
			$semester_name_temp .= ' '. \dash\utility\convert::to_fa_number($year);

			$new_id = null;

			if(array_search($semester_name_temp, $azvir_semester) === false)
			{
				$insert_semester =
				[
					'title' => $semester_name_temp,
					'start' => (string) $year . (string) $semester_start[$month[$start_date_month]],
					'end'   => (string) $endyear . (string) $semester_end[$month[$start_date_month]],
				];
				$insert_semester['status'] = 'expire';

				if(intval($year) >= 1396)
				{
					$insert_semester['status'] = 'active';
				}


				$semester_id = self::fix($azvir->semester('post', $insert_semester), null, $insert_semester);
				if(isset($semester_id['id']))
				{
					$new_id = $semester_id['id'];

					$azvir_semester[$semester_id['id']] = $semester_name_temp;
				}
				else
				{
					$semester_id = self::fix($azvir->semester_search('get', ['search' => $semester_name_temp]));
					if(isset($semester_id[0]['id']))
					{
						$new_id = $semester_id[0]['id'];
						$azvir_semester[$semester_id[0]['id']] = $semester_name_temp;
					}
					else
					{
						\dash\notif::warn("Can not add semester $semester_name_temp");
					}
				}

			}
			else
			{
				$new_id = array_search($semester_name_temp, $azvir_semester);
			}

			if($new_id)
			{
				\dash\db::query("UPDATE classes set azvir_semester_id = '$new_id' WHERE classes.id = $value[id] ", 'quran_hadith');
			}
		}

		$query   = [];
		$query[] = "UPDATE classes set azvir_teacher_id = (SELECT azvir_teacher_id FROM person WHERE person.users_id = classes.teacher)";
		$query[] = "UPDATE classes set azvir_topic_id = (SELECT azvir_topic_id FROM plan WHERE plan.id = classes.plan_id)";
		$query[] = "UPDATE classification SET azvir_member_id = (SELECT azvir_member_id from person where person.users_id = classification.users_id)";
		$query[] = "UPDATE classes SET classes.branch_id = (SELECT branch_id from plan where classes.plan_id = plan.id)";
		$query[] = "UPDATE classes SET classes.gender = (SELECT gender from branch where classes.branch_id = branch.id)";
		$query[] = "UPDATE classes SET classes.azvir_maxperson = (SELECT max_person from plan where classes.plan_id = plan.id)";

		foreach ($query as $key => $value)
		{
			\dash\db::query($value, 'quran_hadith');
		}

		self::add_lesson();
	}


	private static function add_lesson()
	{
		$query =
		"
			SELECT
				*
			FROM
				classes
			ORDER BY classes.start_date ASC

		";
		$result = \dash\db::get($query, null, false , 'quran_hadith');

		$azvir = new \dash\utility\ermile\azvir(azvir_api_key, azvir_api_school, 1);

		$azvir_lesson = [];


		foreach ($result as $key => $value)
		{
			$status = ['draft','cancel','awaiting','full','enable','disable','expire'];

			if($value['status'] === 'ready')
			{
				$status = 'draft';
			}
			elseif($value['status'] === 'done')
			{
				$status = 'disable';
			}
			elseif ($value['status'] === 'running')
			{
				$status = 'enable';
			}
			else
			{
				$status = 'draft';
			}

			$topicteacher = ['topic_id' => $value['azvir_topic_id'], 'teacher_id' => $value['azvir_teacher_id']];
			$azvir->topicteacher('post', $topicteacher);

			$insert_lesson                   = [];
			$insert_lesson['status']         = $status;
			$insert_lesson['force_semester'] = true;
			$insert_lesson['semester_id']    = $value['azvir_semester_id'];
			$insert_lesson['topic_id']       = $value['azvir_topic_id'];
			$insert_lesson['teacher_id']     = $value['azvir_teacher_id'];

			$xg = $value['gender'];

			if($xg == 'male' || $xg == 'female')
			{
				$gender = $xg;
			}
			else
			{
				$gender = 'all';
			}

			$insert_lesson['gender']         = $gender;
			$insert_lesson['maxperson']      = $value['azvir_maxperson'];
			$insert_lesson['examdate']       = $value['end_date'];

			$lesson_id = self::fix($azvir->lesson('post', $insert_lesson), false, $insert_lesson);

			if(isset($lesson_id['lesson_id']))
				{
					$new_id = $lesson_id['lesson_id'];

					$azvir_lesson[$lesson_id['lesson_id']] = $lesson_id;
				}
				else
				{
					$new_id = null;
					// $lesson_id = self::fix($azvir->semester_search('get', ['search' => $semester_name_temp]));
					// if(isset($lesson_id[0]['id']))
					// {
					// 	$new_id = $lesson_id[0]['id'];
					// 	$azvir_lesson[$lesson_id[0]['id']] = $lesson_id[0];
					// }
					// else
					// {
					// 	\dash\notif::warn("Can not add lesson $semester_name_temp");
					// }
				}

				if($new_id)
				{
					\dash\db::query("UPDATE classes set azvir_lesson_id = '$new_id' WHERE classes.id = $value[id] ", 'quran_hadith');
				}

			# code...
		}

		$query   = [];
		$query[] = "UPDATE classification SET azvir_lesson_id = (SELECT azvir_lesson_id from classes where classes.id = classification.classes_id)";

		foreach ($query as $key => $value)
		{
			\dash\db::query($value, 'quran_hadith');
		}

	}


	public static function get_azvir_topic_id($_plan_id)
	{
		 $azvir_topic_plan =
		 [
		 'fc'  => 26,
		 'fd'  => 27,
		 'ff'  => 36,
		 'fg'  => 37,
		 'fh'  => 69,
		 'fj'  => 70,
		 'fk'  => 159,
		 'fm'  => 348,
		 'fn'  => 375,
		 'fp'  => 73,
		 'fq'  => 40,
		 'fr'  => 73,
		 'fs'  => 39,
		 'ft'  => 39,
		 'fv'  => 25,
		 'fw'  => 25,
		 'fx'  => 38,
		 'fy'  => 38,
		 'fz'  => 29,
		 'fB'  => 40,
		 'fC'  => 41,
		 'fD'  => 42,
		 'fF'  => 42,
		 'fG'  => 43,
		 'fH'  => 43,
		 'fJ'  => 44,
		 'fK'  => 44,
		 'fL'  => 45,
		 'fM'  => 48,
		 'fN'  => 48,
		 'fP'  => 49,
		 'fQ'  => 49,
		 'fR'  => 50,
		 'fS'  => 51,
		 'fT'  => 52,
		 'fV'  => 53,
		 'fW'  => 54,
		 'fX'  => 55,
		 'fY'  => 57,
		 'fZ'  => 58,
		 'g2'  => 59,
		 'g3'  => 60,
		 'g4'  => 63,
		 'g5'  => 64,
		 'g6'  => 66,
		 'g7'  => 73,
		 'g8'  => 29,
		 'g9'  => 372,
		 'gb'  => 418,
		 'gc'  => 435,
		 'gd'  => 72,
		 'gf'  => 86,
		 'gg'  => 86,
		 'gh'  => 86,
		 'gj'  => 74,
		 'gk'  => 75,
		 'gm'  => 75,
		 'gn'  => 77,
		 'gp'  => 78,
		 'gq'  => 79,
		 'gr'  => 80,
		 'gs'  => 81,
		 'gt'  => 82,
		 'gv'  => 83,
		 'gw'  => 84,
		 'gx'  => 85,
		 'gy'  => 89,
		 'gz'  => 169,
		 'gB'  => 169,
		 'gC'  => 186,
		 'gD'  => 186,
		 'gF'  => 203,
		 'gG'  => 220,
		 'gH'  => 394,
		 'gJ'  => 91,
		 'gK'  => 92,
		 'gL'  => 435,
		 'gM'  => 194,
		 'gN'  => 94,
		 'gP'  => 94,
		 'gQ'  => 95,
		 'gR'  => 96,
		 'gS'  => 97,
		 'gT'  => 98,
		 'gV'  => 99,
		 'gW'  => 100,
		 'gX'  => 101,
		 'gY'  => 102,
		 'gZ'  => 103,
		 'h2'  => 103,
		 'h3'  => 104,
		 'h4'  => 104,
		 'h5'  => 105,
		 'h6'  => 105,
		 'h7'  => 106,
		 'h8'  => 106,
		 'h9'  => 107,
		 'hb'  => 107,
		 'hc'  => 108,
		 'hd'  => 108,
		 'hf'  => 110,
		 'hg'  => 110,
		 'hh'  => 111,
		 'hj'  => 111,
		 'hk'  => 112,
		 'hm'  => 112,
		 'hn'  => 113,
		 'hp'  => 113,
		 'hq'  => 114,
		 'hr'  => 114,
		 'hs'  => 115,
		 'ht'  => 115,
		 'hv'  => 116,
		 'hw'  => 116,
		 'hx'  => 117,
		 'hy'  => 117,
		 'hz'  => 118,
		 'hB'  => 118,
		 'hC'  => 119,
		 'hD'  => 119,
		 'hF'  => 120,
		 'hG'  => 120,
		 'hH'  => 121,
		 'hJ'  => 121,
		 'hK'  => 122,
		 'hL'  => 122,
		 'hM'  => 123,
		 'hN'  => 123,
		 'hP'  => 124,
		 'hQ'  => 124,
		 'hR'  => 125,
		 'hS'  => 125,
		 'hT'  => 126,
		 'hV'  => 126,
		 'hW'  => 127,
		 'hX'  => 127,
		 'hY'  => 128,
		 'hZ'  => 128,
		 'j2'  => 129,
		 'j3'  => 129,
		 'j4'  => 130,
		 'j5'  => 130,
		 'j6'  => 131,
		 'j7'  => 131,
		 'j8'  => 132,
		 'j9'  => 132,
		 'jb'  => 133,
		 'jc'  => 133,
		 'jd'  => 134,
		 'jf'  => 134,
		 'jg'  => 135,
		 'jh'  => 135,
		 'jj'  => 136,
		 'jk'  => 136,
		 'jm'  => 137,
		 'jn'  => 137,
		 'jp'  => 138,
		 'jq'  => 138,
		 'jr'  => 139,
		 'js'  => 139,
		 'jt'  => 140,
		 'jv'  => 140,
		 'jw'  => 141,
		 'jx'  => 141,
		 'jy'  => 142,
		 'jz'  => 142,
		 'jB'  => 143,
		 'jC'  => 143,
		 'jD'  => 144,
		 'jF'  => 144,
		 'jG'  => 145,
		 'jH'  => 145,
		 'jJ'  => 146,
		 'jK'  => 146,
		 'jL'  => 147,
		 'jM'  => 147,
		 'jN'  => 148,
		 'jP'  => 148,
		 'jQ'  => 149,
		 'jR'  => 149,
		 'jS'  => 150,
		 'jT'  => 150,
		 'jV'  => 151,
		 'jW'  => 152,
		 'jX'  => 71,
		 'jY'  => 426,
		 'jZ'  => 154,
		 'k2'  => 154,
		 'k3'  => 155,
		 'k4'  => 155,
		 'k5'  => 156,
		 'k6'  => 24,
		 'k7'  => 426,
		 'k8'  => 165,
		 'k9'  => 165,
		 'kb'  => 166,
		 'kc'  => 166,
		 'kd'  => 164,
		 'kf'  => 164,
		 'kg'  => 162,
		 'kh'  => 162,
		 'kj'  => 160,
		 'kk'  => 160,
		 'km'  => 163,
		 'kn'  => 163,
		 'kp'  => 161,
		 'kq'  => 161,
		 'kr'  => 167,
		 'ks'  => 68,
		 'kt'  => 258,
		 'kv'  => 170,
		 'kw'  => 170,
		 'kx'  => 171,
		 'ky'  => 171,
		 'kz'  => 172,
		 'kB'  => 172,
		 'kC'  => 173,
		 'kD'  => 173,
		 'kF'  => 174,
		 'kG'  => 174,
		 'kH'  => 175,
		 'kJ'  => 175,
		 'kK'  => 176,
		 'kL'  => 176,
		 'kM'  => 178,
		 'kN'  => 180,
		 'kP'  => 180,
		 'kQ'  => 181,
		 'kR'  => 182,
		 'kS'  => 259,
		 'kT'  => 185,
		 'kV'  => 260,
		 'kW'  => 87,
		 'kX'  => 187,
		 'kY'  => 187,
		 'kZ'  => 188,
		 'm2'  => 189,
		 'm3'  => 190,
		 'm4'  => 190,
		 'm5'  => 191,
		 'm6'  => 191,
		 'm7'  => 192,
		 'm8'  => 192,
		 'm9'  => 193,
		 'mb'  => 167,
		 'mc'  => 195,
		 'md'  => 196,
		 'mf'  => 197,
		 'mg'  => 197,
		 'mh'  => 198,
		 'mj'  => 199,
		 'mk'  => 200,
		 'mm'  => 201,
		 'mn'  => 202,
		 'mp'  => 203,
		 'mq'  => 88,
		 'mr'  => 204,
		 'ms'  => 205,
		 'mt'  => 206,
		 'mv'  => 207,
		 'mw'  => 208,
		 'mx'  => 209,
		 'my'  => 210,
		 'mz'  => 211,
		 'mB'  => 212,
		 'mC'  => 213,
		 'mD'  => 214,
		 'mF'  => 214,
		 'mG'  => 216,
		 'mH'  => 217,
		 'mJ'  => 218,
		 'mK'  => 219,
		 'mL'  => 220,
		 'mM'  => 223,
		 'mN'  => 221,
		 'mP'  => 222,
		 'mQ'  => 223,
		 'mR'  => 231,
		 'mS'  => 224,
		 'mT'  => 225,
		 'mV'  => 226,
		 'mW'  => 30,
		 'mX'  => 228,
		 'mY'  => 229,
		 'mZ'  => 230,
		 'n2'  => 231,
		 'n3'  => 239,
		 'n4'  => 233,
		 'n5'  => 234,
		 'n6'  => 416,
		 'n7'  => 241,
		 'n8'  => 242,
		 'n9'  => 34,
		 'nb'  => 456,
		 'nc'  => 246,
		 'nd'  => 247,
		 'nf'  => 249,
		 'ng'  => 250,
		 'nh'  => 251,
		 'nj'  => 255,
		 'nk'  => 253,
		 'nm'  => 257,
		 'nn'  => 441,
		 'np'  => 33,
		 'nq'  => 31,
		 'nr'  => 32,
		 'ns'  => 227,
		 'nt'  => 244,
		 'nv'  => 261,
		 'nw'  => 262,
		 'nx'  => 262,
		 'ny'  => 263,
		 'nz'  => 263,
		 'nB'  => 264,
		 'nC'  => 264,
		 'nD'  => 265,
		 'nF'  => 265,
		 'nG'  => 266,
		 'nH'  => 267,
		 'nJ'  => 267,
		 'nK'  => 268,
		 'nL'  => 268,
		 'nM'  => 269,
		 'nN'  => 269,
		 'nP'  => 270,
		 'nQ'  => 270,
		 'nR'  => 271,
		 'nS'  => 271,
		 'nT'  => 272,
		 'nV'  => 272,
		 'nW'  => 273,
		 'nX'  => 273,
		 'nY'  => 274,
		 'nZ'  => 274,
		 'p2'  => 275,
		 'p3'  => 275,
		 'p4'  => 276,
		 'p5'  => 276,
		 'p6'  => 277,
		 'p7'  => 277,
		 'p8'  => 278,
		 'p9'  => 278,
		 'pb'  => 279,
		 'pc'  => 279,
		 'pd'  => 280,
		 'pf'  => 280,
		 'pg'  => 281,
		 'ph'  => 282,
		 'pj'  => 282,
		 'pk'  => 283,
		 'pm'  => 283,
		 'pn'  => 284,
		 'pp'  => 285,
		 'pq'  => 285,
		 'pr'  => 286,
		 'ps'  => 286,
		 'pt'  => 90,
		 'pv'  => 90,
		 'pw'  => 288,
		 'px'  => 288,
		 'py'  => 289,
		 'pz'  => 153,
		 'pB'  => 153,
		 'pC'  => 291,
		 'pD'  => 292,
		 'pF'  => 293,
		 'pG'  => 294,
		 'pH'  => 295,
		 'pJ'  => 296,
		 'pK'  => 297,
		 'pL'  => 298,
		 'pM'  => 299,
		 'pN'  => 300,
		 'pP'  => 301,
		 'pQ'  => 302,
		 'pR'  => 303,
		 'pS'  => 304,
		 'pT'  => 305,
		 'pV'  => 306,
		 'pW'  => 307,
		 'pX'  => 308,
		 'pY'  => 309,
		 'pZ'  => 310,
		 'q2'  => 311,
		 'q3'  => 313,
		 'q4'  => 314,
		 'q5'  => 315,
		 'q6'  => 316,
		 'q7'  => 317,
		 'q8'  => 318,
		 'q9'  => 319,
		 'qb'  => 320,
		 'qc'  => 321,
		 'qd'  => 322,
		 'qf'  => 323,
		 'qg'  => 324,
		 'qh'  => 325,
		 'qj'  => 326,
		 'qk'  => 327,
		 'qm'  => 328,
		 'qn'  => 329,
		 'qp'  => 330,
		 'qq'  => 331,
		 'qr'  => 333,
		 'qs'  => 334,
		 'qt'  => 335,
		 'qv'  => 336,
		 'qw'  => 337,
		 'qx'  => 338,
		 'qy'  => 340,
		 'qz'  => 341,
		 'qB'  => 342,
		 'qC'  => 343,
		 'qD'  => 344,
		 'qF'  => 345,
		 'qG'  => 347,
		 'qH'  => 245,
		 'qJ'  => 349,
		 'qK'  => 349,
		 'qL'  => 350,
		 'qM'  => 350,
		 'qN'  => 351,
		 'qP'  => 351,
		 'qQ'  => 352,
		 'qR'  => 352,
		 'qS'  => 353,
		 'qT'  => 353,
		 'qV'  => 354,
		 'qW'  => 354,
		 'qX'  => 355,
		 'qY'  => 355,
		 'qZ'  => 356,
		 'r2'  => 356,
		 'r3'  => 357,
		 'r4'  => 358,
		 'r5'  => 358,
		 'r6'  => 359,
		 'r7'  => 359,
		 'r8'  => 360,
		 'r9'  => 360,
		 'rb'  => 361,
		 'rc'  => 361,
		 'rd'  => 362,
		 'rf'  => 363,
		 'rg'  => 364,
		 'rh'  => 364,
		 'rj'  => 365,
		 'rk'  => 365,
		 'rm'  => 366,
		 'rn'  => 366,
		 'rp'  => 367,
		 'rq'  => 367,
		 'rr'  => 368,
		 'rs'  => 368,
		 'rt'  => 369,
		 'rv'  => 369,
		 'rw'  => 370,
		 'rx'  => 370,
		 'ry'  => 371,
		 'rz'  => 371,
		 'rB'  => 248,
		 'rC'  => 373,
		 'rD'  => 373,
		 'rF'  => 374,
		 'rG'  => 252,
		 'rH'  => 376,
		 'rJ'  => 376,
		 'rK'  => 377,
		 'rL'  => 377,
		 'rM'  => 378,
		 'rN'  => 378,
		 'rP'  => 379,
		 'rQ'  => 379,
		 'rR'  => 380,
		 'rS'  => 380,
		 'rT'  => 381,
		 'rV'  => 382,
		 'rW'  => 184,
		 'rX'  => 256,
		 'rY'  => 287,
		 'rZ'  => 287,
		 's2'  => 385,
		 's3'  => 387,
		 's4'  => 388,
		 's5'  => 383,
		 's6'  => 383,
		 's7'  => 390,
		 's8'  => 391,
		 's9'  => 392,
		 'sb'  => 393,
		 'sc'  => 384,
		 'sd'  => 395,
		 'sf'  => 396,
		 'sg'  => 397,
		 'sh'  => 398,
		 'sj'  => 399,
		 'sk'  => 400,
		 'sm'  => 401,
		 'sn'  => 402,
		 'sp'  => 403,
		 'sq'  => 404,
		 'sr'  => 405,
		 'ss'  => 406,
		 'st'  => 407,
		 'sv'  => 408,
		 'sw'  => 409,
		 'sx'  => 412,
		 'sy'  => 413,
		 'sz'  => 414,
		 'sB'  => 415,
		 'sC'  => 384,
		 'sD'  => 417,
		 'sF'  => 254,
		 'sG'  => 419,
		 'sH'  => 420,
		 'sJ'  => 422,
		 'sK'  => 423,
		 'sL'  => 424,
		 'sM'  => 425,
		 'sN'  => 290,
		 'sP'  => 290,
		 'sQ'  => 427,
		 'sR'  => 432,
		 'sS'  => 93,
		 'sT'  => 93,
		 'sV'  => 436,
		 'sW'  => 437,
		 'sX'  => 438,
		 'sY'  => 439,
		 'sZ'  => 440,
		 't2'  => 389,
		 't3'  => 442,
		 't4'  => 443,
		 't5'  => 444,
		 't6'  => 445,
		 't7'  => 446,
		 't8'  => 447,
		 't9'  => 448,
		 'tb'  => 449,
		 'tc'  => 450,
		 'td'  => 451,
		 'tf'  => 452,
		 'tg'  => 453,
		 'th'  => 454,
		 'tj'  => 455,
		 'tk'  => 389,
		 ];

		return array_search($_plan_id, $azvir_topic_plan);

	}
}
?>