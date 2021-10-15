<?php	
namespace service;

use util\DB;
use util\Log;

require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';


class Popup
{// 팝업
	static $service_name = "popup";
	/************************************** INSERT / UPDATE **************************************/
	public static function action($action, $datas)
	{// 액션 쿼리 실행
		$service_name = self::$service_name;

		$datas['action'] = $action;
		$datas['table'] = empty($datas['table']) ? $service_name : $datas['table'];

		return DB::action($datas);
	}







	// 공통 SQL 영역입니다.
	/************************************** SELECT DETAIL **************************************/









	// 관리자 SQL 영역입니다.
	/************************************** SELECT DETAIL **************************************/
    public static function getPopupAdmin($request)
	{// 관리자 - 상세 조회 (2021.09.28 / By.Chungwon)
		$service_name = self::$service_name;

		return DB::Execute("
			SELECT
				mt.*
			FROM
				(

					SELECT
						*
					FROM
						`{$service_name}`
					WHERE
						idx = {$request['target_idx']}
				)
			AS mt
		");
	}

	/************************************** SELECT LIST **************************************/
	public static function getListPopupAdmin($request)
    {/* 
		관리자 - 목록 조회 (2021.09.28 / By.Chungwon)
	*/
		$service_name 		= self::$service_name;
        $order_sql 				= empty($request['sort_list']) 	? "" : "ORDER BY " . stripslashes($request['sort_list']);		

		$mt_where = DB::createWhereQuery($request, array(
			"like_space" => array( // 텍스트
				array("key"					=> "title"),
			),
			"in" => array(	// 체크박스 or
				array("key"					=> "view_status"),
			),
			"date_range" => array( // 날짜
				array("key"					=> "insert_date"),
			),
		));


		return DB::Execute("
			SELECT SQL_CALC_FOUND_ROWS
				mt.*
			FROM
				(
					SELECT
						*
					FROM
						`{$service_name}`
					WHERE
						1 = 1
						{$mt_where}
				)
			AS mt
						
			{$order_sql}

			LIMIT {$request['limit']}
			OFFSET {$request['offset']}

		", TRUE);
    }















	// 사용자 SQL 영역입니다.
	/************************************** SELECT DETAIL **************************************/
	/************************************** SELECT LIST **************************************/
	public static function getListPopup($request)
    {/* 
		관리자 - 목록 조회 (2021.09.29 / By.Chungwon)
	*/
		$service_name 		= self::$service_name;
        $order_sql 				= empty($request['sort_list']) 	? "" : "ORDER BY " . stripslashes($request['sort_list']);		

		$mt_where = DB::createWhereQuery($request, array(
			"like_space" => array( // 텍스트
				array("key"					=> "title"),
			),
			"in" => array(	// 체크박스 or
				array("key"					=> "view_status"),
				array("key"					=> "device_type"),
			),
			// "date_range" => array( // 날짜
			// 	array("key"					=> "insert_date"),
			// ),
		));


		return DB::Execute("
			SELECT SQL_CALC_FOUND_ROWS
				mt.*
			FROM
				(
					SELECT
						*
					FROM
						`{$service_name}`
					WHERE
						1 = 1
						{$mt_where}
						AND DATE(start_date) <= NOW()
						AND DATE_ADD(DATE(end_date), INTERVAL 1 DAY) > NOW()
				)
			AS mt
			
			LEFT OUTER JOIN
                (
                    SELECT
                        *
                        ,	CONCAT('/', type, '/', ref_table, '/', ref_key, '/', n_name) AS path
                    FROM
						`files`
                    WHERE
						ref_table = '{$service_name}'
						AND ref_key = 'thumbnail'
					GROUP BY
						ref_idx
                )
            AS fi2
			ON fi2.ref_idx = mt.idx
						
			{$order_sql}

			LIMIT {$request['limit']}
			OFFSET {$request['offset']}

		", TRUE);
    }
}