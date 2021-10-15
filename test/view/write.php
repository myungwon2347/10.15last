<?php
require_once '../lib/db.php';

$sql = "SELECT * FROM `board`";
$result = mysqli_query($conn, $sql);
$data = mysqli_fetch_array($result);
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../js/jquery-3.6.0.js"></script>
    <title>게시판</title>
</head>
<body>
        <table style="width:800px;" border="1" cellpadding=5>
            <tr>
                <th>이름</th>
                <td><input type="text" name="name"/></td>
            </tr>
            
            <tr>
                <th>제목</th>
                <td><input type="text" name="title"/></td>
            </tr>

            <tr>
                <th>내용</th>
                <td><textarea name="memo" style="width:100%; height: 400px;"></textarea></td>
            </tr>
            <tr>
                <th colspan="2"><input type="submit" id="board-write" value="저장하기"></th>
            </tr>
        </table>
    <div><a href="board.php">돌아가기</div>
    <script>
        
    // 'request'라는 id를 가진 버튼 클릭 시 실행.
$(function(){
    $("#board-write").on('click', function(e){
        if(confirm('글을 등록 하시겠습니까?') === false){ return; }
            // json 형식으로 데이터 set
            var params = {
                    name : $("input[name=name]").val(),
                    title : $("input[name=title]").val(),
                    memo : $("textarea[name=memo]").val()
            }
                
            // ajax 통신
            $.ajax({
                type : "POST",            // HTTP method type(GET, POST) 형식이다.
                url : "../process/process_write.php",      // 컨트롤러에서 대기중인 URL 주소이다.
                data : params,            // Json 형식의 데이터이다.
                success : function(res){ // 비동기통신의 성공일경우 success콜백으로 들어옵니다. 'res'는 응답받은 데이터이다.
                    // 응답코드 > 0000
                    alert('등록 완료');
                    location.href="board.php";
                },
                error : function(XMLHttpRequest, textStatus, errorThrown){ // 비동기 통신이 실패할경우 error 콜백으로 들어옵니다.
                    alert("통신 실패.")
                }
            });
    });
});
    </script>
</body>
</html>