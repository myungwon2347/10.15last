<?php	
namespace service;

use util\DB;
use util\Log;

require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

class Popup
{
	static $service_name = "popup";

	/************************************** INSERT **************************************/
	public static function insert($title, $view_state, $start_date, $end_date, $render_type, $device_type, $z_index, $size_x, $size_y, $location_x, $location_y)
	{	// 추가 (2020.07.22 / By.Minseo)
		$service_name = self::$service_name;

		return DB::Execute("
            INSERT INTO `{$service_name}`
            (
                title
               , 	view_state
               , 	start_date
               ,    end_date
               , 	render_type
			   , 	device_type
			   ,	z_index
               , 	size_x
               , 	size_y
               , 	location_x
               , 	location_y
            )
            VALUES
            (
                '{$title}'
               , 	{$view_state}
               , 	'{$start_date}'
               ,    '{$end_date}'
               , 	{$render_type}
               , 	{$device_type}
               , 	{$z_index}
               , 	{$size_x}
               , 	{$size_y}
               , 	{$location_x}
               , 	{$location_y}
            )
		");
    }
    
	/************************************** UPDATE **************************************/
	public static function update($is_admin, $user_common_idx, $target_idx, $title, $view_state, $start_date, $end_date, $render_type, $device_type, $z_index, $size_x, $size_y, $location_x, $location_y)
	{	// 수정 (2020.07.25 / By.Chungwon)
		$service_name = self::$service_name;
		$auth_sql = "";
		$update_list = array();

		// 접근 권한 설정 (작성자만 접근가능, 관리자는 무조건 실행)
		$auth_sql = $is_admin ? "" : "AND reg_user_idx = {$user_common_idx}";

		// 수정 파라미터 매핑
		array_push($update_list, is_null($title) ? 				 "" : ",	 title 		 = '{$title}'      ");
		array_push($update_list, is_null($view_state) ? 		 "" : ",	 view_state  = {$view_state} ");
		array_push($update_list, is_null($start_date) ? 		 "" : ",	 start_date  = '{$start_date}' ");
		array_push($update_list, is_null($end_date) ? 		     "" : ",	 end_date    = '{$end_date}'   ");
		array_push($update_list, is_null($render_type) ? 		 "" : ",	 render_type  = {$render_type}       ");
		array_push($update_list, is_null($device_type) ? 		 "" : ",	 device_type = {$device_type}       ");
		array_push($update_list, is_null($z_index) ? 			 "" : ",	 z_index 	 = {$z_index}     ");
		array_push($update_list, is_null($size_x) ? 			 "" : ",	 size_x 	 = {$size_x}     ");
		array_push($update_list, is_null($size_y) ? 			 "" : ",	 size_y 	 = {$size_y}     ");
		array_push($update_list, is_null($location_x) ? 		 "" : ",	 location_x  = {$location_x} ");
		array_push($update_list, is_null($location_y) ? 		 "" : ",	 location_y  = {$location_y} ");
		$update_list = join("\n", $update_list);

		return DB::Execute("
			UPDATE `{$service_name}` SET
				update_date = NOW()
				{$update_list}
			WHERE
				idx = {$target_idx}
				{$auth_sql}
		");
	}
    
	/************************************** DELETE **************************************/
	public static function delete($target_idx, $is_admin, $user_common_idx)
	{	// 삭제 (2020.07.15 / By.Chungwon)
		$service_name = self::$service_name;
		$auth_sql = "";

		// 접근 권한 설정 (작성자만 접근가능, 관리자는 무조건 실행)
		$auth_sql = $is_admin ? "" : "AND reg_user_idx = {$user_common_idx}";

		return DB::Execute("
			DELETE FROM `{$service_name}`
			WHERE
				idx = {$target_idx}
				{$auth_sql}
		");
    }
    
	/************************************** SELECT DETAIL **************************************/
	public static function isDuplicate($column, $value, $wrap)
	{	// 컬럼 중복 검사 (2020.07.15 / By.Chungwon)
		$service_name = self::$service_name;

		$result = DB::Execute("
			SELECT 
				COUNT(*) as count
			FROM
				`{$service_name}`
			WHERE
				{$column} = {$wrap}{$value}{$wrap}
		");

		return $result && (intval($result[0]['count']) > 0) ? true : false;
    }
    public static function get($target_idx, $user_common_idx)
	{	// 상세 조회 (2020.07.22 / By.Minseo)
		$service_name = self::$service_name;

		$result = DB::Execute("
            SELECT
                title
                , 	start_date
                ,   end_date
				, 	render_type
				,	device_type
				,	view_state
				,	z_index
                , 	size_x
                , 	size_y
                , 	location_x
				, 	location_y
				,	CASE
						WHEN pp2.idx IS NOT NULL THEN 1
						ELSE 0
					END AS render_state
            FROM
                `{$service_name}`

			LEFT OUTER JOIN
				(
					SELECT
						idx
					FROM
						`{$service_name}`
					WHERE
						DATE(start_date) <= NOW()
						AND DATE_ADD(DATE(end_date), INTERVAL 1 DAY) > NOW()
				)
			AS pp2
			ON `{$service_name}`.idx = pp2.idx

			WHERE
				`{$service_name}`.idx = {$target_idx}
		");

		return $result ? $result[0] : false;
    }
    /************************************** SELECT LIST **************************************/
	public static function getList($action_flag, $title, $view_state, $start_date, $end_date, $device_type, $sort_list, $limit, $offset)
	{	// 목록 조회 (2020.07.25 / By.Chungwon)
		$service_name = self::$service_name;
        $order_sql = empty($sort_list) ? "" : "ORDER BY {$sort_list}";
		
		// 검색 쿼리
        $title_sql 		 = is_null($title) 		 ? 	"" : "AND title LIKE '%{$title}%'";

		$start_date_sql  = empty($start_date)    ? 	"" : "AND DATE('{$start_date}') <= start_date";
		$end_date_sql 	 = empty($end_date) 	 ? 	"" : "AND DATE_ADD(DATE('{$end_date}'), INTERVAL 1 DAY) > end_date";
		$view_state_sql  = $view_state === ""    ? 	"" : "AND view_state IN ({$view_state})";

		if($action_flag === "user")
		{
			$today = date("Y-m-d");
			$start_date_sql  = empty($start_date)    ? 	"" : "AND DATE(start_date) <= NOW()";
			$end_date_sql 	 = empty($end_date) 	 ? 	"" : "AND DATE_ADD(DATE(end_date), INTERVAL 1 DAY) > NOW()";	
		}

		$device_type_sql  = $device_type === ""    ? 	"" : "AND device_type IN ({$device_type})";

		$join_state = $action_flag === "user" ? "INNER" : "LEFT OUTER";

		// 데이터 검색
		return DB::Execute("
            SELECT SQL_CALC_FOUND_ROWS
                pp.idx
                ,   pp.title
                , 	pp.start_date
                ,   pp.end_date
                , 	pp.render_type
                , 	pp.view_state
				, 	pp.device_type
				,	pp.z_index
                , 	pp.size_x
                , 	pp.size_y
                , 	pp.location_x
                , 	pp.location_y
				,   fi1.path AS res_image_path
				,	fi1.total_hit
				,	pp.insert_date
				,	CASE
						WHEN pp2.idx IS NOT NULL THEN 1
						ELSE 0
					END AS render_state
			FROM
				(
					SELECT
						*
					FROM
						`{$service_name}`
					WHERE
						1 = 1
						{$title_sql}
						{$start_date_sql}
						{$end_date_sql}
						{$device_type_sql}
                        {$view_state_sql}
				)
			AS pp

			{$join_state} JOIN
				(
					SELECT
						idx
					FROM
						`{$service_name}`
					WHERE
						DATE(start_date) <= NOW()
						AND DATE_ADD(DATE(end_date), INTERVAL 1 DAY) > NOW()
				)
			AS pp2
			ON pp.idx = pp2.idx						
			
			LEFT OUTER JOIN
				(
					SELECT
						idx
						,	ref_idx
						,	CONCAT('/', type, '/', ref_table, '/', ref_key, '/', n_name) AS path
						,	SUM(hit) AS total_hit
					FROM
						files
					WHERE
                        1 = 1
						AND type = 'image'
						AND ref_table = 'popup'
						AND ref_key = 'common'
					GROUP BY
						ref_idx
				)
			AS	fi1
			ON fi1.ref_idx = pp.idx
			
			{$order_sql}

			LIMIT {$limit}
			OFFSET {$offset}

		", TRUE);
	}
}