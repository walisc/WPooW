/* wpOOWBaseMenu Js File*/

jQuery(document).ready(function(){
    jQuery(".wpoow-menu-separator > a").each(function(){
        jQuery(this).click(function(ev){
            ev.preventDefault()
        })
        jQuery(this).attr('href', '');
    })
    
  });