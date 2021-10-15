<style>
    .view-pc{display:inline-block;}
    .view-mobile{display:none;}
    .floating-kakao{display:flex;  align-items:center; justify-content:center; position:fixed; z-index:1000; right:40px; bottom:120px; width:67px; height:67px; background-color:#ffde00; border-radius:50%;}
    .floating-kakao img{width:42px; height:auto; cursor:pointer;}

    @media screen and (max-width: 768px) {
        .view-pc{display:none;}
        .view-mobile{display:block;}
        .floating-kakao{position:fixed; z-index:10000; left:86%; bottom:85px; width:40px; height:40px;}
        .floating-kakao img{width:24px; height:auto;}
    }
</style>

<div class='floating-kakao'>
    <a href='http://pf.kakao.com/_nxnrxas/chat' target='_blank'>
        <img class='view-pc' src='<?=$PATH['RESOURCES']?>/image/onfif/kakao.png' />
        <img class='view-mobile' src='<?=$PATH['RESOURCES']?>/image/onfif/kakao.png' />
    </a>
</div>