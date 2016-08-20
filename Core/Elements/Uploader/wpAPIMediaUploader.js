jQuery(document).ready(function($){

    var mediaUploader;

    $("#"+uploaderJsData.id).on("click", function(e) {
        e.preventDefault();

        if (mediaUploader) {
            mediaUploader.open();
            return;
        }

        mediaUploader = wp.media({
                title: uploaderJsData.title,
                button: {
                    text: uploaderJsData.buttonText
                },
                multiple: uploaderJsData.multiple == "true"  //TODO: this is not working properly
            });
        });

    });