jQuery(document).ready(function(){
    jQuery(".wpoow-menu-separator > a").each(function(){
        $(this).click(function(ev){
            ev.preventDefault()
        })
        $(this).attr('href', '');
    })
    
  });