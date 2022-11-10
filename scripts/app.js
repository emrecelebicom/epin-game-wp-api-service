jQuery(document).ready(function(){
    jQuery('.message a.close').click(function(e){
        e.preventDefault();
        jQuery('.message').fadeOut(300);
    });

    setInterval(function() {
        jQuery('.message').fadeOut(300);
    }, 5000);
});