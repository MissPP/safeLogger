
$(document).ready(function(){
    $('a').each(function () {
        var relative = $(this).attr('href');
        $(this).attr('href',getPath(relative));
    });
    function getPath(url){
        var host = location.href;
        var ROOT_PATH = host.split("slentry.php")[0];
        return ROOT_PATH+url;
    }

});


