( function() {
    $( "#{{ element_id }}_edit_view" ).each(function(){
        var value = $(this).children("div").text()
        $("#{{element_id}}_value").val(value)

        $(this).slider({
            value: value,
            slide: function( event, ui ) {
                $("#{{element_id}}_value").val(ui.value)
                $(this).children("div").text( ui.value );
            }
        });

    })

    $( "#{{ element_id }}_read_view" ).each(function(){
        $(this).slider({
            value: $(this).children("div").text(),
            disabled: true
        });

    })

})();

