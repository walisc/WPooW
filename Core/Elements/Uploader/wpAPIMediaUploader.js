jQuery(document).ready(function($){

    var mediaUploader;

    $("#"+uploaderJsData.id+"_upload_button").on("click", function(e) {
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
                multiple: uploaderJsData.multiple == "true"
            });

        mediaUploader.on("select", function(){
            uploadedItem = mediaUploader.state().get('selection').first().toJSON();



            var uploadedDataItem = JSON.stringify({
                id: uploadedItem.id,
                url: uploadedItem.url,
                filename: uploadedItem.filename
            });
            $("#"+uploaderJsData.id+"_selected_file").val(uploadedDataItem);

            $("#"+uploaderJsData.id+"_selected_file_display").html(uploadedItem.filename);
        });

        mediaUploader.open();

        });


});