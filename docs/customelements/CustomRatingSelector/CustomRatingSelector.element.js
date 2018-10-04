( function () {
        var scaleValues = {}

        jQuery( "#{{element_id}}_main > div" ).each(function() {
            // read initial values from markup and remove that
            var value =30
            $( this ).empty().slider({
                range: "min",
                animate: true,
                orientation: "horizontal",
                create: function() {
                    var setValue = $( this ).slider( "value" );

                    updateMainScore($(this).attr("id"), setValue)
                    $( this ).children("span").text( setValue );
                },
                slide: function( event, ui ) {

                    updateMainScore($(this).attr("id"), ui.value)
                    $( this ).children("span").text( ui.value );
                }
            });
        });

        function updateMainScore(id, value) {
            scaleValues[id] = value;

            var totalScores = Object.keys(scaleValues).reduce(function (total, key) {
                return total+scaleValues[key]
            }, 0 )

            var trueScore = Math.round(totalScores/Object.keys(scaleValues).length)

            jQuery("#{{element_id}}_score_total").text(trueScore)
            jQuery("#{{element_id}}_value").val(JSON.stringify(scaleValues))

        }


    }
)();