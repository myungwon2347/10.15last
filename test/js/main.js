function loadJQuery() {
    var oScript = document.createElement("script");
    oScript.type = "text/javascript";
    oScript.charset = "utf-8";		  
    oScript.src = "jquery-3.6.0.js";	
    document.getElementsByTagName("head")[0].appendChild(oScript);
}

var heightResult = 0;

$('nav a').on({
    mouseover : function(){
        // $('.main-menu').outerHeight(true)
        // $('.sub-menu').outerHeight(true)
        $('.sub-menu').each(function(){
            if(heightResult < $('.sub-menu').outerHeight(true))
            heightResult = $('.sub-menu').outerHeight(true)
        });
        $('nav').css('height', $('.main-menu').outerHeight(true) + heightResult);
        $(this).css('opacity', '1');
    },

    mouseout : function(){
        $('nav').css('height', $('.main-menu').outerHeight(true));
        $('nav a').css('opacity', '0.25');
    }
});

$('.contents div').each(function(index, e){
    
    $('.tabs li').click(function(){
        
        if( $(e).attr('id') === $(this).attr('class') ){
            $(e).css('display', 'block');
        }else{
            $(e).css('display', 'none');
        }
    })
})

// $('#next').click(function(){
//     "<?php $a + 10; ?>";
// })
// function next() {
//     "<?php echo $a ?>";
// }