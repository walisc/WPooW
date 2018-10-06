( function() {
    $( "#{{ element_id }}_edit_view" ).each(function(){
        var value = $(this).children("div").text()

        //set the hidden input value. This input value is what is sent back when a post occurs
        $("#{{element_id}}_value").val(value)

        // Initialize the slider
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

