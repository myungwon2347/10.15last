<?php
require_once '../lib/db.php';

$id = $_REQUEST['id'];
$query = "SELECT * FROM `board` WHERE `idx` ={$id}";
$result = mysqli_query($conn, $query);
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
                <td><?=$data['name']?></td>
            </tr>
            
            <tr>
                <th>제목</th>
                <td><?=$data['title']?></td>
            </tr>

            <tr>
                <th>내용</th>
                <td>
                    <textarea style="width:100%; height: 400px;" disabled><?=$data['memo']?></textarea>
                </td>
            </tr>
            <tr>
                <th colspan="2"><input type="submit" id="board-delete" value="삭제"></th>
                <th colspan="2"><a href="update.php?id=<?=$data['idx']?>"><input type="submit" id="board-update" value="수정"></a></th>
            </tr>
        </table>
    <div><a href="board.php">돌아가기</div>
    <script>
        
$(function(){
    $("#board-delete").on('click', function(e){

        if(confirm('글을 삭제 하시겠습니까?') === false){ return; }

            var params = {
                    id : <?=$data['idx']?>
            }
                
            $.ajax({
                type : "POST",            
                url : "../process/process_delete.php",    
                data : params,         
                success : function(res){ 
      
                    alert('삭제 완료');
                    location.href="board.php";
                },
                
                error : function(XMLHttpRequest, textStatus, errorThrown){ 
                    alert("통신 실패.")
                }
            });
    });
});
    </script>
</body>
</html>