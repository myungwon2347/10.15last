<?php	
namespace service;

require_once $_SERVER['DOCUMENT_ROOT'] . "/config.php";

use util\DB;
use util\Log;

class Visiter
{
    static $service_name = "visiter";

    public static function getVisitStatics($interval = null, $is_distinct = FALSE)
    {   // 방문자 통계 조회 (2020.04.22 / By.Chungwon)
		$service_name = self::$service_name;
        /*  
            방문자 수 (interval은 지난일)
            ex) 어제 방문자수는 interval 1, 오늘 방문자는 interval 0
            빈 값은 총 방문자 수 !
        */
        $interval_sql = $interval === null ? "" : "
            AND 
                DATE_FORMAT(visit_date, '%Y-%m-%d') 
                = 
                DATE_SUB(
                    DATE_FORMAT(NOW(), '%Y-%m-%d')
                    , INTERVAL {$interval} DAY
                )
        ";
        $distinct_sql = $is_distinct ? "DISTINCT ip" : "*";
		$count = DB::Execute("
			SELECT
				COUNT({$distinct_sql}) as count
            FROM
                `{$service_name}`marketing_action_media
            WHERE
                1 = 1
                {$interval_sql}
		");

		return $count ? $count[0]['count'] : false;
    }
    public static function getActionList()
    {   // 방문자 수 조회 (2020.04.02 / By.Chungwon)
		$service_name = self::$service_name;
       return DB::Execute("
            SELECT
                `idx`
                ,   `key`
                ,   `name`
            FROM
                `marketing_action_media`
            WHERE
                status = 1
         ");
    }
    public static function getOptionList($type)
    {   // 방문자 수 조회 (2020.04.02 / By.Chungwon)
		$service_name = self::$service_name;
       return DB::Execute("
             SELECT
                 {$type}
             FROM
                 `{$service_name}`
             GROUP BY
                 {$type}
         ");
    } 
    public static function reVisiterCount($is_real = null)
	{   // 재 방문자 수 (2020.04.22 By. Chungwon)
		$service_name = self::$service_name;

        $is_real_sql = empty($is_real) ? "" : "WHERE vl.cnt > 1";

		$count = DB::Execute("
			SELECT
				COUNT(*) AS count
			FROM
				(
					SELECT
						COUNT(*) AS cnt
						, ip
					FROM
						`{$service_name}`
					GROUP BY
						ip
				) AS vl
			{$is_real_sql}
			ORDER BY
				vl.cnt desc
		");

		return $count ? $count[0]['count'] : $count;
	}
    

	public static function getVisitLog($interval = 30)
	{   // 방문자 수 조회 (2020.04.02 / By.Chungwon)
		$service_name = self::$service_name;

        return DB::Execute("
			SELECT
                DATE_FORMAT(visit_date, '%Y-%m-%d') AS date
                ,   COUNT(DISTINCT ip) AS count
			FROM
				`{$service_name}`
			GROUP BY
				date
			ORDER BY
				date DESC
			LIMIT
                {$interval}
        ");
	}
    public static function insertClient($ip, $lang, $device_type, $device_name, $browser, $referer, $current_url)
    {   // 방문자 로그 등록 (2020.04.02 / By.Chungwon)
        
        return DB::Execute("
            INSERT INTO `visiter`
            (
                site,
                ip, 
                lang, 
                device_type, 
                device_name, 
                browser, 
                referer,
                current_url
            )
            VALUES
            (
                '{$GLOBALS['SITE']['NAME']}',
                '{$ip}', 
                '{$lang}', 
                '{$device_type}', 
                '{$device_name}', 
                '{$browser}', 
                '{$referer}',
                '{$current_url}'
            );
        ");
    }
    public static function getVisitInfoCount($interval = null)
	{   // 방문자 통계 정보 수 조회 (2020.04.06 / By.Chungwon)
		$service_name = self::$service_name;

        $interval_sql = empty($interval) ? "" : "
            AND 
                DATE_FORMAT(visit_date, '%Y-%m-%d') 
                = 
                DATE_SUB(
                    DATE_FORMAT(NOW(), '%Y-%m-%d')
                    , interval {$interval} day
                )
        ";

        $result = DB::Execute("
            SELECT 
                COUNT(*) AS count 
            FROM 
                `{$service_name}` 
            WHERE
                1 = 1
                {$interval_sql}
            
        ");

		return $result ? $result[0]['count'] : false;
	}
    public static function getVisitInfo($ip, $referer, $current_url, $current_url2, $start_date, $end_date, $device_type, $device_name, $browser, $is_revisit, $sort_list, $limit, $offset)
    {   // 방문자 통계 정보 조회 (2020.07.10 / By.Chungwon)
		$service_name = self::$service_name;
        $order_sql = empty($sort_list) ? "" : "ORDER BY {$sort_list}";
  
        $ip_sql = empty($ip) ? "" : "AND (ip LIKE '%{$ip}%' OR replace(ip, ' ', '') LIKE '%{$ip}%')";
        $referer_sql = empty($referer) ? "" : "AND (referer LIKE '%{$referer}%' OR replace(referer, ' ', '') LIKE '%{$referer}%')";
        $current_url_sql = empty($current_url) ? "" : "AND (current_url LIKE '%{$current_url}%' OR replace(current_url, ' ', '') LIKE '%{$current_url}%')";
        $current_url2_sql 			= is_null($current_url2) 			 || $current_url2 		  	=== ""		? 	"" 	: "AND current_url REGEXP REPLACE('{$current_url2}', ',', '|')";
        
        $device_type_sql = empty($device_type) ? "" : "AND device_type = '{$device_type}'";
        $device_name_sql = empty($device_name) ? "" : "AND device_name = '{$device_name}'";
        $browser_sql = empty($browser) ? "" : "AND browser = '{$browser}'";

        $is_revisit_sql = empty($is_revisit) ? "" : "HAVING COUNT(ip) > 1";
        $date_sql = "AND (DATE(visit_date) BETWEEN '{$start_date}' AND '{$end_date}')";
  
	    return DB::Execute("
	        SELECT SQL_CALC_FOUND_ROWS
	        	*
	        FROM
                `{$service_name}`
            WHERE
                1 = 1
                {$ip_sql}
                {$referer_sql}
                {$current_url_sql}
                {$current_url2_sql}
                {$device_type_sql}
                {$device_name_sql}
                {$browser_sql}
                {$date_sql}

            GROUP BY ip

            {$is_revisit_sql}

            {$order_sql}

            LIMIT {$limit} 
            OFFSET {$offset}
	    ", TRUE);
	}
    public static function getVisitInfoAll($ip, $referer, $current_url, $current_url2, $start_date, $end_date, $device_type, $device_name, $browser, $sort_list, $limit, $offset)
    {   // 모든 페이지 방문자 통계 정보 조회 (2020.07.10 / By.Chungwon)
		$service_name = self::$service_name;
        $order_sql = empty($sort_list) ? "" : "ORDER BY {$sort_list}";
  
        $ip_sql = empty($ip) ? "" : "AND (ip LIKE '%{$ip}%' OR replace(ip, ' ', '') LIKE '%{$ip}%')";
        $referer_sql = empty($referer) ? "" : "AND (referer LIKE '%{$referer}%' OR replace(referer, ' ', '') LIKE '%{$referer}%')";
        $current_url_sql = empty($current_url) ? "" : "AND (current_url LIKE '%{$current_url}%' OR replace(current_url, ' ', '') LIKE '%{$current_url}%')";
        $current_url2_sql 			= is_null($current_url2) 			 || $current_url2 		  	=== ""		? 	"" 	: "AND current_url REGEXP REPLACE('{$current_url2}', ',', '|')";

        $device_type_sql = empty($device_type) ? "" : "AND device_type = '{$device_type}'";
        $device_name_sql = empty($device_name) ? "" : "AND device_name = '{$device_name}'";
        $browser_sql = empty($browser) ? "" : "AND browser = '{$browser}'";
  
        $date_sql = "AND (DATE(visit_date) BETWEEN '{$start_date}' AND '{$end_date}')";
  
	    return DB::Execute("
	        SELECT SQL_CALC_FOUND_ROWS
	        	*
                ,   IFNULL(current_url, '') AS current_url
	        FROM
                `{$service_name}`
            WHERE
                1 = 1
                {$ip_sql}
                {$referer_sql}
                {$current_url_sql}
                {$current_url2_sql}
                {$device_type_sql}
                {$device_name_sql}
                {$browser_sql}
                {$date_sql}

            {$order_sql}

            LIMIT {$limit} 
            OFFSET {$offset}
	    ", TRUE);
	}
    
}