( function () {
        var scaleValues = {}

        $( "#{{element_id}}_main > div" ).each(function() {
            // read initial values from markup and remove that
            var value = $(this).text();
            $( this ).empty().slider({
                value: value,
                range: "min",
                animate: true,
                orientation: "horizontal",
                create: function() {
                    var setValue = $( this ).slider( "value" );

                    UpdateMainScore(this, setValue)
                    $( this ).children("span").text( setValue );
                },
                slide: function( event, ui ) {

                    UpdateMainScore(this, ui.value)
                    $( this ).children("span").text( ui.value );
                }
            });
        });

        $( "#{{element_id}}_read_view > div" ).each(function() {
            // read initial values from markup and remove that
            var value = $(this).text();
            $( this ).empty().slider({
                disabled: true,
                value: value,
                range: "min",
                animate: true,
                orientation: "horizontal",
                create: function() {
                    var setValue = $( this ).slider( "value" );
                    UpdateMainScore(this, setValue)
                    $( this ).children("span").text( setValue );
                }
            });
        });


        function UpdateMainScore(score_elements, value) {

            scaleValues[$(score_elements).attr("id").replace("{{element_id}}_", "")] = value;

            var totalScores = Object.keys(scaleValues).reduce(function (total, key) {
                return total+scaleValues[key]
            }, 0 )

            var trueScore = Math.round(totalScores/Object.keys(scaleValues).length)

            $(score_elements).parent().children("h1").html("Score: <span>" +trueScore+"</span>")
            $("#{{element_id}}_value").val(JSON.stringify(scaleValues))

        }


    }
)();