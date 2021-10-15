<?php	
namespace util; 

require_once "{$_SERVER['DOCUMENT_ROOT']}/config.php";

// https://docs.google.com/spreadsheets/d/1Dnqq-q77TfKFxzUxB5ai7hIV0kMjIz__dE3GA2tAAo4/edit#gid=650532241

class Front
{
	/******************** VIEW 함수 ********************/
	/*
			array(
				"key"                       => "id",                            // 데이터 컬럼명 (필수)
				"name"                      => "아이디",                        // 데이터 이름 (필수) 파라미터에만 쓰이는 경우 생략 가능
				"is_order"                  => true,                            // 정렬 여부 (선택-기본값 false)
				"is_list"                   => true,                            // 조회 여부 (선택-기본값 false)

				"is_param"                  => true,                            // 검색 파라미터 여부 (선택-기본값 false) (is_form = true 시, 자동으로 true)
				"default"                   => "",                              // 검색 파라미터 기본 값 (선택-기본값 "")

				"is_form"                   => true,                            // 검색 폼 여부 (선택-기본값 false)
				"type"                      => "",                              // 검색 폼 데이터 타입 (html에 반영됌)  (선택-기본값 text)
				"placeholder"               => "검색 할 아이디를 입력해주세요.",   // 검색 폼 - placeholder (선택-기본값은 type에 따라 다름.)
				"value_list"                => array(                           // 검색 폼 - 값 목록 (보통 변수) (선택-기본값 "")
					array(
						"value"             => "",
						"text"              => "전체",
					),
					array(
						"value"             => "0",
						"text"              => "중지",
					),
					array(
						"value"             => "1",
						"text"              => "진행",
					),
				),                              
			),
		*/
	/* 목록 */

	public static function orderForCreate($item_list, $option)
	{/*
		create 데이터 타입을 위한 정렬 (3차원?)
		2021.09.11 / By.Chungwon
	*/

		$com_item_list = array();
		for($i = 0; $i < count($item_list); $i++)
		{// 정렬
			$item = $item_list[$i];

			// 우선순위 생성하기 (기본값 0)
			$order_num = isset($item[$option['key']]) ? $item[$option['key']] : 0;

			// 정렬 배열에 무작위로 담기
			// array_push($com_item_list, array($item['key'] => $order_num));
			$com_item_list[$item['key']] = $order_num;

			// 우선순위를 기준으로 desc 정렬
			$option['sort']($com_item_list);
		}
		
		return $com_item_list;
	}





	public static function createListOrder($item_list)
	{/*
		목록페이지 - 정렬 HTML 생성
		2021.09.11 / By.Chungwon
	*/
		$result_list = array();

		$sorted_item_list = Front::orderForCreate($item_list, array("sort" => "arsort", "key" => "order_order_num"));
		$sorted_key_list = array_keys($sorted_item_list);

		for($i = 0; $i < count($sorted_key_list); $i++)
		{
			$key = $sorted_key_list[$i];
			
			for($j = 0; $j < count($item_list); $j++)
			{
				$item = $item_list[$j];
				
				if(isset($item['is_order']) && $item['is_order'])
				{
					if($item['key'] === $key)
					{// 우선순위 키와 일치하는 경우
						array_push($result_list, "
							<button class='align-btn align-join-date data-filter'>
								<span class='sort-item' data-sort_key='{$item['key']}' data-event_type='click'>
									{$item['name']}
									<i class='xi-align-justify'></i>
									<i class='xi-sort-desc'></i>
									<i class='xi-sort-asc'></i>
								</span>
							</button>
						");
					}
				}
			}
		}
		
		return join("", $result_list);
	}





	public static function createListTable($item_list)
	{/*
		목록페이지 - 테이블 HTML 생성
		2021.09.11 / By.Chungwon
	*/
		$result_list = array(
			"head" => array(),	// 리스트 thead (제목)
			"count" => 0,	// create 커스텀에서 count 설정을 위해
			"create_index" => array(),	// create 인덱스 {0} 부분
			"create_data" => array(),	// create 데이터 매핑 부분
		);

		$sorted_item_list = Front::orderForCreate($item_list, array("sort" => "arsort", "key" => "list_order_num"));
		$sorted_key_list = array_keys($sorted_item_list);

		for($i = 0; $i < count($sorted_key_list); $i++)
		{
			$key = $sorted_key_list[$i];
			
			for($j = 0; $j < count($item_list); $j++)
			{
				$item = $item_list[$j];
				
				if(isset($item['is_list']) && $item['is_list'])
				{
					if($item['key'] === $key)
					{// 우선순위 키와 일치하는 경우

						if(empty($item['is_custom']) || $item['is_custom'] === false)
						{// is_custom이 true인 경우에는 생략
							$result_list['count']++;

							array_push($result_list['head'], 			"<th class='tr-item'>{$item['name']}</th>");
							array_push($result_list['create_data'], 	", data['{$item['key']}']");
							array_push($result_list['create_index'], 	"<td class='tr-item' title='{{$result_list['count']}}' data-item_key='{$item['key']}'>{{$result_list['count']}}</td>\\");
						}

						break;
					}
				}
			}
		}
		
		$result_list['head'] 			= join("", $result_list['head']);
		$result_list['create_index'] 	= join("", $result_list['create_index']);
		$result_list['create_data'] 	= join("", $result_list['create_data']);

		return $result_list;
	}





	public static function createListJSParam($params, $item_list)
	{/*
		목록페이지 - 파라미터 JS 생성
		2021.08.28 / By.Chungwon
	*/
		$result_list = array();

		for($i = 0; $i < count($item_list); $i++)
		{
			$item = $item_list[$i];

			if((isset($item['is_param']) && $item['is_param']) || 
				(isset($item['is_form']) && $item['is_form']))
			{
				$key = $item['key'];
				$value = $params[$key];

				$item['default'] = empty($item['default']) 	? "" 		: $item['default']; // default 기본 값 설정
				$item['is_number'] = empty($item['is_number']) 	? false 		: $item['is_number']; // is_number 기본 값 설정

				if(isset($item['type']) && $item['is_number'])
				{// 숫자인 경우 (0도 유효해야함.)
					$value = (isset($value) === false) ? $item['default'] : $value;
					// $value = ($value !== "") ? $value : $item['default'];

				}
				else
				{// 그 외
					$value = isset($value) ? $value : $item['default'];
				}
				

				if(isset($item['type']) && $item['type'] === "date")
				{// 데이트 타입인 경우
					
					$key_start = $item['key'] . "_start";
					$key_end = $item['key'] . "_end";

					$item['default_start'] = empty($item['default_start']) 	? "" 		: $item['default_start']; // default 기본 값 설정
					$item['default_end'] = empty($item['default_end']) 	? "" 		: $item['default_end']; // default 기본 값 설정

					$value_start = $params[$key_start];
					$value_start = isset($value_start) ? $value_start : $item['default_start'];// date("Y-m-d", strtotime("-1 year"));

					$value_end = $params[$key_end];					
					$value_end = isset($value_end) ? $value_end : $item['default_end'];//date("Y-m-d");

					// 값 유지를 위해 기존 값도 전달 필요 (key)
					array_push($result_list, "
						{$key} : '{$value}',
						{$key_start}: '{$value_start}',
						{$key_end} : '{$value_end}',
					");
				}
				else
				{// 그 외
					
					array_push($result_list, "
						{$key} : '{$value}',
					");
				}
			}
		}
		return join("", $result_list);
	}





	public static function createListSearch($item_list)
	{/*
		목록페이지 - 검색 폼 HTML 생성
		2021.08.28 / By.Chungwon
		
	*/
		$result_list = array();




		$sorted_item_list = Front::orderForCreate($item_list, array("sort" => "arsort", "key" => "form_order_num"));
		$sorted_key_list = array_keys($sorted_item_list);

		for($i = 0; $i < count($sorted_key_list); $i++)
		{
			$key = $sorted_key_list[$i];
			
			for($j = 0; $j < count($item_list); $j++)
			{
				$item = $item_list[$j];
				
				if(isset($item['is_form']) && $item['is_form'])
				{
					if($item['key'] === $key)
					{// 우선순위 키와 일치하는 경우
						$str_item = "";

						$item['type'] 		= empty($item['type']) 			? "text" 	: $item['type']; // type 기본 값 설정
						$item['value_list'] = empty($item['value_list']) 	? "" 		: $item['value_list']; // value_list 기본 값 설정

						if(isset($item['type']) && $item['type'] === "text")
						{// 텍스트 타입인 경우
							
							$item['placeholder'] = empty($item['placeholder']) ? "{$item['name']}을 입력해주세요." : $item['placeholder']; // placeholder 기본 값 설정
							
							$str_item = "
								<form class='search-item search-text search-form' data-event_type='submit' onsubmit='return false;'>
									<p class='search-item-tit'>{$item['name']}</p>
									<input class='event-search_keyword' type='text' data-search_key='{$item['key']}' placeholder='{$item['placeholder']}' />
									<button><i class='xi-search'></i></button>
								</form>
							";
						}
						else if(isset($item['type']) && $item['type'] === "date")
						{// 데이터 타입인 경우

							$str_item = "
								<div class='search-item'>
									<p class='search-item-tit'>{$item['name']}</p>
									<input type='text' class='datepicker' data-search_key='{$item['key']}_start' data-event_type='change'/>
									<input type='text' class='datepicker' data-search_key='{$item['key']}_end' data-event_type='change'/> 
								</div>
							";
						}
						
						else if(isset($item['type']) && ($item['type'] === "radio" || $item['type'] === "checkbox"))
						{// 라디오버튼 OR 체크박스

							$option_list = array();

							if(isset($item['value_list']))
							{
								for($k = 0; $k < count($item['value_list']); $k++)
								{
									$opt = $item['value_list'][$k];
									$opt['text'] = empty($opt['text']) ? $opt['value'] : $opt['text']; // text가 비어있으면 자동으로 value로 대체됩니다.

									array_push($option_list, "<label><input type='{$item['type']}' data-event_type='change' data-search_key='{$item['key']}' name='{$item['key']}' value='{$opt['value']}'>{$opt['text']}</label>");
									
								}
							}
							$str_opt = join("", $option_list);
							$str_item = "
								<div class='search-item search-form'>
									<span class='search-item-tit'>{$item['name']}</span>
									{$str_opt}
								</div>
							";
						}

						else if(isset($item['type']) && $item['type'] === "select")
						{// 셀렉트박스 경우

							$option_list = array();

							if(isset($item['value_list']))
							{
								for($k = 0; $k < count($item['value_list']); $k++)
								{
									$opt = $item['value_list'][$k];
									$opt['text'] = empty($opt['text']) ? $opt['value'] : $opt['text']; // text가 비어있으면 자동으로 value로 대체됩니다.

									array_push($option_list, "<option value='{$opt['value']}'>{$opt['text']}</option>");
								}
							}
							$str_opt = join("", $option_list);
							$str_item = "
								<div class='search-item search-check'>
									<p class='search-item-tit'>{$item['name']}</p>
									<select data-search_key='{$item['key']}' data-event_type='change'>
										{$str_opt}
									</select>
								</div>
							";
						}

						array_push($result_list, $str_item);
					}
				}
			}
		}


		return join("", $result_list);
	}
	/* 목록 끝 */










	/* 등록/상세 */
	public static function createForm($item_list, $page_type = "detail")
	{/*
		등록/상세 - 폼 HTML 생성
		2021.09.15 / By.Chungwon
		
		$page_type = detail 또는 upload


		
        array(
            "type"                      => "text",					// 데이터 타입
            "key"                       => "company_name",			// DB 컬럼명
            "name"                      => "회사명",				 // 출력 이름
            "default"                   => "", 						// 기본 값
            "placeholder"               => "회사명을 입력하세요.",	  // placeholder
			"order_num"                 => "20",
            "value_list"                => array(
                array(
                    "value"             => "",
                    "text"              => "전체",			// text가 비어있으면 자동으로 value로 대체됩니다.
                ),
                array("value"             => "에프디컴퍼니",),
            ),

			// text 변수
            "is_number"                => true,
            "is_comma"                => true,

			// 엑셀 변수
            "is_excel"                  => true, // 엑셀 등록 여부
            "excel_default"                   => "",
            "excel_description"               => "회사명을 입력하세요.", // 엑셀 설명

			// 이미지 변수
            'view_type'                 => 'list', // 'bg' or 'list'
            'width'                     => 100, // 이미지 너비 (파일에서는 안쓰임)
            'height'                    => 100, // 이미지 높이 (파일에서는 안쓰임)
            'format'                    => '.png, .jpg, .jpeg, .gif, .bmp, .webp, .heic, .jfif',// 허용 파일 포맷 (공백 시, 모두 허용. 썸네일에서는 예시값이 기본 값)
            'target_flag'               => $target_flag,// 파일의 부모 구분 값
			
            'intro_list'                => array(// 이미지 안내 문구
				'영화 상세페이지에 노출됩니다.',			
				'주의사항을 꼭 지켜주세요.',			
            ),
			
        ),
	*/
		$result_list = array();
		$section_list = array();
		// upload-cont
		/***** 우선순위 정렬*****/
		$sorted_item_list = Front::orderForCreate($item_list, array("sort" => "arsort", "key" => "order_num"));
		$sorted_key_list = array_keys($sorted_item_list);

		for($i = 0; $i < count($sorted_key_list); $i++)
		{// 정렬 키 값 목록
			$key = $sorted_key_list[$i];
			
			for($j = 0; $j < count($item_list); $j++)
			{// 요청 파라미터 (등록/상세 정보)
				$item = $item_list[$j];
				
				if($item['key'] === $key)
				{// 우선순위 키와 일치하는 경우
					$str_item = "";

					if($page_type === "upload")
					{// 등록페이지인 경우

						$item['type'] 				= empty($item['type']) 				? "text" 		: $item['type']; // type 기본 값 설정
						$item['default'] 			= isset($item['default']) == false 	? "" 			: $item['default']; // 기본 값
						
						$item['name'] 				= empty($item['name']) 				? $item['type'] : $item['name']; // name 기본 값 설정
						$item['is_hide'] 			= empty($item['is_hide']) 			? "" 			: "fit-hide"; // is_hide
						$item['alert_title'] 		= empty($item['alert_title']) 		? "{$item['name']} 값을 등록해주세요." 			: $item['alert_title']; // alert_title
						$item['placeholder'] 		= empty($item['placeholder']) 		? "" 			: $item['placeholder']; // placeholder
						$item['value_list'] 		= empty($item['value_list']) 		? "" 			: $item['value_list']; // value_list 기본 값 설정

						$item['is_excel'] 			= isset($item['is_excel']) 			? 'excel_item' 	: ''; // 엑셀 자동생성 여부 설정 (기본값-true)
						$item['excel_default'] 		= empty($item['excel_default']) 	? "" 			: $item['excel_default']; // 엑셀 기본 값					
						$item['excel_description'] 	= empty($item['excel_description']) ? "" 			: $item['excel_description']; // 엑셀 설명
						
						if(isset($item['type']) && $item['type'] === "text")
						{// 텍스트
							$item['is_number'] = empty($item['is_number']) 	? "" 		: "data-option='number' onkeyup='vaildateNumber(this);'"; // 엑셀 자동생성 여부 설정
							$item['is_comma'] = empty($item['is_comma']) 	? "" 		: "data-is_comma='true'"; // 엑셀 자동생성 여부 설정

							$str_item = "
								<div class='upload-item upload-title {$item['is_hide']}'>
									<label class='upload-tit {$item['is_excel']}' data-excel_default='{$item['excel_default']}' data-excel_type='{$item['type']}' data-excel_key='{$item['key']}' data-excel_name='{$item['name']}' data-excel_intro='{$item['excel_description']}' >{$item['name']}</label>
									<div class='upload-input'>
										<input type='text' data-{$page_type}_key='{$item['key']}' value='{$item['default']}' title='{$item['alert_title']}' placeholder='{$item['placeholder']}' {$item['is_number']} {$item['is_comma']}/>
									</div>
								</div>
							";
						}
						else if(isset($item['type']) && $item['type'] === "textarea")
						{// textarea

							$str_item = "
								<div class='upload-item upload-title {$item['is_hide']}'>
									<label class='upload-tit {$item['is_excel']}' data-excel_default='{$item['excel_default']}' data-excel_type='{$item['type']}' data-excel_key='{$item['key']}' data-excel_name='{$item['name']}' data-excel_intro='{$item['excel_description']}' >{$item['name']}</label>
									<div class='upload-input'>
										<textarea data-{$page_type}_key='{$item['key']}' title='{$item['alert_title']}' placeholder='{$item['placeholder']}'>{$item['default']}</textarea>
									</div>
								</div>
							";
						}
						else if(isset($item['type']) && $item['type'] === "editor")
						{// editor
							$str_item = "
								<div class='upload-item upload-detail {$item['is_hide']}'>
									<label class='upload-tit {$item['is_excel']}' data-excel_default='{$item['excel_default']}' data-excel_type='{$item['type']}' data-excel_key='{$item['key']}' data-excel_name='{$item['name']}' data-excel_intro='{$item['excel_description']}' >{$item['name']}</label>
									<div class='upload-input editor-content' type='text' placeholder='{$item['placeholder']}' data-{$page_type}_key='{$item['key']}'> </div>
								</div>
							";
						}
						else if(isset($item['type']) && $item['type'] === "radio")
						{// 라디오
							$option_list = array();

							if(isset($item['value_list']))
							{
								for($k = 0; $k < count($item['value_list']); $k++)
								{
									$opt = $item['value_list'][$k];
									$opt['text'] = empty($opt['text']) ? $opt['value'] : $opt['text']; // text가 비어있으면 자동으로 value로 대체됩니다.
									$checked = $opt['value'] === $item['default'] ? "checked" : "";

									array_push($option_list, "
										<label class='option-check'>
											<input type='radio' required name='{$item['key']}' data-{$page_type}_key='{$item['key']}' value='{$opt['value']}' title='{$item['alert_title']}' placeholder='{$item['placeholder']}' {$checked}>
											{$opt['text']}
										</label>
									");
								}
							}
							$str_opt = join("", $option_list);
							$str_item = "
								<div class='upload-item {$item['is_hide']}'>
									<label class='upload-tit {$item['is_excel']}' data-excel_default='{$item['excel_default']}' data-excel_type='{$item['type']}' data-excel_key='{$item['key']}' data-excel_name='{$item['name']}' data-excel_intro='{$item['excel_description']}' >{$item['name']}</label>
									{$str_opt}
								</div>
							";
						}
						else if(isset($item['type']) && $item['type'] === "select")
						{// 셀렉트
							$option_list = array();

							if(isset($item['value_list']))
							{
								for($k = 0; $k < count($item['value_list']); $k++)
								{
									$opt = $item['value_list'][$k];
									$opt['text'] = empty($opt['text']) ? $opt['value'] : $opt['text']; // text가 비어있으면 자동으로 value로 대체됩니다.
									$selected = $opt['value'] === $item['default'] ? "selected" : "";

									array_push($option_list, "<option value='{$opt['value']}'>{$opt['text']}</option>");
								}
							}
							$str_opt = join("", $option_list);
							$str_item = "
								<div class='upload-item {$item['is_hide']}'>
									<label class='upload-tit {$item['is_excel']}' data-excel_default='{$item['excel_default']}' data-excel_type='{$item['type']}' data-excel_key='{$item['key']}' data-excel_name='{$item['name']}' data-excel_intro='{$item['excel_description']}' >{$item['name']}</label>
									<select data-{$page_type}_key='{$item['key']}' data-event_type='change'>
										{$str_opt}
									</select>
								</div>
							";
						}
						else if(isset($item['type']) && $item['type'] === "date")
						{// 날짜 (단일)
							$str_item = "
								<div class='upload-item upload-title {$item['is_hide']}'>
									<label class='upload-tit {$item['is_excel']}' data-excel_default='{$item['excel_default']}' data-excel_type='{$item['type']}' data-excel_key='{$item['key']}' data-excel_name='{$item['name']}' data-excel_intro='{$item['excel_description']}' >{$item['name']}</label>
									<div class='item-dater'>
										<div class='dater-item'>
											<i class='xi-calendar'></i>
											<input type='text' class='datepicker {$item['is_excel']}' data-{$page_type}_key='{$item['key']}' autocomplete='off' title='{$item['alert_title']}' placeholder='{$item['placeholder']}' readonly  data-excel_type='date' data-excel_key='{$item['key']}' data-excel_name='{$item['name']}' data-excel_intro='ex) 2021-08-16 17:00:00' />
										</div>
									</div>
								</div>
							";
						}
						else if(isset($item['type']) && $item['type'] === "date_double")
						{// 날짜 (시작-끝)

							$str_item = "
								<div class='upload-item upload-title {$item['is_hide']}'>
									<label class='upload-tit {$item['is_excel']}' data-excel_default='{$item['excel_default']}' data-excel_type='{$item['type']}' data-excel_key='{$item['key']}' data-excel_name='{$item['name']}' data-excel_intro='{$item['excel_description']}' >{$item['name']}</label>
									<div class='item-dater'>
										<div class='dater-item'>
											<i class='xi-calendar'></i>
											<input type='text' class='datepicker {$item['is_excel']}' data-{$page_type}_key='{$item['key']}' autocomplete='off' title='{$item['alert_title']}' placeholder='{$item['placeholder']}' readonly  data-excel_type='date' data-excel_key='{$item['key']}' data-excel_name='{$item['name']} 시작일' data-excel_intro='ex) 2021-08-16 17:00:00' />
										</div>
										<div class='dater-item'>~</div>
										<div class='dater-item'>
											<i class='xi-calendar'></i>
											<input type='text' class='datepicker {$item['is_excel']}' data-{$page_type}_key='{$item['key']}' autocomplete='off' title='{$item['alert_title']}' placeholder='{$item['placeholder']}' readonly  data-excel_type='date' data-excel_key='{$item['key']}' data-excel_name='{$item['name']} 마감일' data-excel_intro='ex) 2021-08-16 17:00:00' />
										</div>
									</div>
								</div>
							";
						}
						else if(isset($item['type']) && $item['type'] === "image")
						{// 단일 이미지 (ex. 썸네일)
							$item['excel_description'] = $item['excel_description'] === "" ? "사진은 등록페이지에서 등록해주세요." : $item['excel_description']; // 엑셀 설명
							$item['view_type'] = empty($item['view_type']) 	? "bg" 		: $item['view_type']; // 'bg' or 'list'
							$item['width'] = empty($item['width']) 	? "100" 		: $item['width']; // 이미지 너비
							$item['height'] = empty($item['height']) 	? "100" 		: $item['height']; // 이미지 높이
							$item['format'] = empty($item['format']) 	? ".png, .jpg, .jpeg, .gif, .bmp, .webp, .heic, .jfif" 		: $item['format']; // 허용 파일 포맷
							$item['size'] = empty($item['size']) 	? 20 		: $item['size']; // 허용 파일 사이즈
							
							/***** 이미지 등록 안내 *****/
							$str_intro = array();

							if(isset($item['intro_list']))
							{
								for($k = 0; $k < count($item['intro_list']); $k++)
								{
									$intro = $item['intro_list'][$k];
									array_push($str_intro, "<p>* {$intro}</p>");
								}
							}
							$str_intro = join("<br/>", $str_intro);
							/***** 이미지 등록 안내 끝 *****/

							$str_item = "
								<div class='upload-item upload-thumb {$item['is_hide']}'>
									<label class='upload-tit {$item['is_excel']}' data-excel_default='{$item['excel_default']}' data-excel_type='{$item['type']}' data-excel_key='{$item['key']}' data-excel_name='{$item['name']}' data-excel_intro='{$item['excel_description']}' >{$item['name']}</label>
									<div class='upload-input' >
										<div class='dater-item image-bg_cont' style='margin-right:20px;width:{$item['width']};height:{$item['height']};'>
											<button class='image-bg_set_btn bg' data-image_canvas='{$item['key']}'></button>
											<input id='file-{$item['key']}' accept='{$item['format']}' class='fit-hide' data-view_type='{$item['view_type']}' data-file_format='{$item['format']}' data-file_key='{$item['key']}' type='file' data-file_size='{$item['size']}' data-method='change_thumbnail' data-method_event='change'/>
											<div class='thumb-btn'>
												<label class='img-add-btn' for='file-{$item['key']}'>등록</label>
												<i class='image-bg_reset_btn' data-method='click_delete_thumbnail' data-method_event='click'>삭제</i>
											</div>
										</div>
										<div class='img-txt'>
											<p>* 권장 사이즈 {$item['width']}x{$item['height']}</p>
											<p>* 허용 파일형식 {$item['format']}</p>
											<p>* 최대 파일크기 {$item['size']}MB</p>
											{$str_intro}
										</div>
									</div>
								</div>
							";
						}
						else if(isset($item['type']) && ($item['type'] === "file_list" || $item['type'] === "image_list"))
						{// 다중 이미지 or 파일 (list-create에서 컨트롤함.)
							$item['excel_description'] = $item['excel_description'] === "" ? "사진은 등록페이지에서 등록해주세요." : $item['excel_description']; // 엑셀 설명
							$item['view_type'] = empty($item['view_type']) 	? "list" 		: $item['view_type']; // 'bg' or 'list'
							$item['width'] = empty($item['width']) 	? "100" 		: $item['width']; // 이미지 너비
							$item['height'] = empty($item['height']) 	? "100" 		: $item['height']; // 이미지 높이
							$item['format'] = empty($item['format']) 	? "" 		: $item['format']; // 허용 파일 포맷
							$item['size'] = empty($item['size']) 	? 20 		: $item['size']; // 허용 파일 사이즈
							$item['target_flag'] = empty($item['target_flag']) 	? "" 		: $item['target_flag']; // 파일의 부모 구분 값
							
							/***** 이미지 등록 안내 *****/
							$str_intro = array();

							if(isset($item['intro_list']))
							{
								for($k = 0; $k < count($item['intro_list']); $k++)
								{
									$intro = $item['intro_list'][$k];
									array_push($str_intro, "<p>* {$intro}</p>");
								}
							}
							$str_intro = join("<br/>", $str_intro);
							/***** 이미지 등록 안내 끝 *****/

							$str_item = "
								<div class='upload-item upload-title {$item['is_hide']}'>
									<label class='upload-tit {$item['is_excel']}' data-excel_default='{$item['excel_default']}' data-excel_type='{$item['type']}' data-excel_key='{$item['key']}' data-excel_name='{$item['name']}' data-excel_intro='{$item['excel_description']}' >{$item['name']}</label>
									<div class='upload-input image-bg_cont'>
										<input id='file-{$item['key']}' class='fit-hide' accept='{$item['format']}' type='file' multiple='multiple' data-view_type='{$item['view_type']}' data-file_format='{$item['format']}' data-file_size='{$item['size']}' data-file_key='{$item['key']}' data-method='change_file_list' data-method_event='change'/>
										<div class='file-btn'>
											<label for='file-{$item['key']}' class='pi-upload'>업로드</label>
											<div class='img-txt'>
												<p>* 권장 사이즈 {$item['width']}x{$item['height']}</p>
												<p>* 허용 파일형식 {$item['format']}</p>
												<p>* 최대 파일크기 {$item['size']}MB</p>
												{$str_intro}
											</div>
										</div>
										<div class='item-file-list' data-image_canvas='{$item['key']}' id='list-getFileList{$item['target_flag']}-{$item['key']}'>
										</div>
									</div>
								</div>
							";
						}

					}
					else if($page_type === "detail")
					{// 상세페이지인 경우

						$item['type'] 		= empty($item['type']) 			? "text" 	: $item['type']; // type 기본 값 설정
						
						$item['name'] 				= empty($item['name']) 				? $item['type'] : $item['name']; // name 기본 값 설정
						$item['is_hide'] 			= empty($item['is_hide']) 			? "" 			: "fit-hide"; // is_hide
						
						if(isset($item['type']) && 
							($item['type'] === "text")
							|| ($item['type'] === "radio")
							|| ($item['type'] === "select")
							|| ($item['type'] === "date")
							|| ($item['type'] === "date_double")
						)
						{// 텍스트, 라디오, 셀렉트, 날짜인 경우
							$str_item = "
								<div class='upload-item upload-title {$item['is_hide']}'>
									<label class='upload-tit'>{$item['name']}</label>
									<div class='upload-input'>
										<span data-{$page_type}_key='{$item['key']}'></span>
									</div>
								</div>
							";
						}
						else if(isset($item['type']) && ($item['type'] === "textarea" || $item['type'] === "editor"))
						{// textarea || editor
							$str_item = "
								<div class='upload-item upload-title {$item['is_hide']}'>
									<label class='upload-tit'>{$item['name']}</label>
									<div class='upload-input'>
										<pre data-{$page_type}_key='{$item['key']}'></pre>
									</div>
								</div>
							";
						}
						else if(isset($item['type']) && $item['type'] === "image")
						{// 이미지인 경우
							$str_item = "
								<div class='upload-item upload-thumb {$item['is_hide']}'>
									<label class='upload-tit'>{$item['name']}</label>
									<div class='upload-input'>
										<div class='dater-item image-bg_cont' style='margin-right:20px;'>
											<button class='image-bg_set_btn bg' data-image_canvas='{$item['key']}'></button>
											<input class='fit-hide' data-file_key='{$item['key']}' type='file' />
										</div>
									</div>
								</div>
							";
						}
						else if(isset($item['type']) && $item['type'] === "image_list")
						{// 이미지 목록인 경우
							$item['target_flag'] = empty($item['target_flag']) 	? "list" 		: $item['target_flag']; // 파일의 부모 구분 값

							$str_item = "
								<div class='upload-item upload-thumb {$item['is_hide']}'>
									<label class='upload-tit'>{$item['name']}</label>
									<div class='upload-input image-bg_cont'>
										<div class='item-file-list' data-image_canvas='{$item['key']}' id='list-getFileList{$item['target_flag']}-{$item['key']}'>
										</div>
									</div>
								</div>
							";
						}
						else if(isset($item['type']) && $item['type'] === "file_list")
						{// 파일 목록인 경우
							$item['target_flag'] = empty($item['target_flag']) 	? "list" 		: $item['target_flag']; // 파일의 부모 구분 값

							$str_item = "
								<div class='upload-item upload-thumb {$item['is_hide']}'>
									<label class='upload-tit'>{$item['name']}</label>
									<div class='upload-input image-bg_cont'>
										<div class='item-file-list' data-image_canvas='{$item['key']}' id='list-getFileList{$item['target_flag']}-{$item['key']}'>
										</div>
									</div>
								</div>
							";
							
						}
					}

					array_push($result_list, $str_item);
				}
			}
		}


		return join("", $result_list);
	}
	/* 등록/상세 끝 */
}
