<?php

require_once '../lib/db.php';

require_once '../lib/fb_paging_data.php';
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/style.css?ver=1">
    <script src="../js/jquery-3.6.0.js"></script>
    <title>게시판</title>
</head>
<body>
        <table>
            <tr>
                <th>check</th>
                <th>번호</th>
                <th>제목</th>
                <th>내용</th>
                <th>작성자</th>
                <th>일자</th>
            </tr>

<?php
    while($data = mysqli_fetch_array($result)){
?>

        <tr>
            <td> <input type="checkbox" value="<?=$data['idx']?>" /></td>
            <td> <?=$data['idx']?> </td>
            <td> <a href="view.php?id=<?=$data['idx']?>"> <?=$data['title']?> </a> </td>
            <td> <?=$data['content']?> </td>
            <td> <?=$data['reg_user_idx']?> </td>
            <td> <?=$data['update_date']?> </td>
        </tr>

<?php } ?>

        </table>

    <a href="board.php?page=1"><span>처음으로</span></a>
    <span>&#8592;</span>

    
<?php
require_once '../lib/fb_paging_sheet.php';
    for($i = 1; $i < $sheetCount + 1; $i++){
        echo "<span><a href='board.php?page={$i}'> $i </a></span>";
    }
?>

    <a href=" board.php?page= <?=$i + 1?> & data= <?=$i + 1?> ">&#8594;</span></a>

    <a href="board.php?page=<?=$num1?>"><span>마지막으로</span></a>
    <a href="write.php"><button>글쓰기</button></a>
    <a href="../index.php"><button>돌아가기</button></a>
    <button id="list-delete">삭제</button>

<?php
    require_once '../lib/fb_empty.php';
?>
<!-- <script src="../js/main.js"></script> -->
<script>
    
    $(function(){
        
        var deleteArr = new Array;

        $("#list-delete").on('click', function(e){
            
            if( confirm('글을 삭제 하시겠습니까?') ){

                $('input[type="checkbox"]:checked').each(function(){

                    deleteArr.push($(this).val());

                });
                
            }
            
            var params = {
                id :   deleteArr
            };

            $.ajax({

                type : "POST",          
                url : "../process/process_delete.php",      
                data : params,
                success : function(res){ 

                    location.href = 'board.php';
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