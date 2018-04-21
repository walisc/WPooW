var wpOOWMediaUploaders = {};

jQuery(document).ready(function($) {


    loadMediaUploader = function (uploaderJsData) {


        wpOOWMediaUploaders[uploaderJsData.id] = wp.media({
            title: uploaderJsData.title,
            button: {
                text: uploaderJsData.buttonText
            },
            multiple: uploaderJsData.multiple == "true"
        });

        wpOOWMediaUploaders[uploaderJsData.id].on("select", function () {
            uploadedItem = wpOOWMediaUploaders[uploaderJsData.id].state().get('selection').first().toJSON();


            var uploadedDataItem = JSON.stringify({
                id: uploadedItem.id,
                url: uploadedItem.url,
                filename: uploadedItem.filename
            });
            //TODO:Change the id extensions
            $("#" + uploaderJsData.id + uploaderJsData.selected_file).val(uploadedDataItem);
            $("#" + uploaderJsData.id + uploaderJsData.selected_file_preview + " img").attr("src", uploadedItem.url);

            $("#" + uploaderJsData.id + uploaderJsData.selected_file_display).html(uploadedItem.filename);
        });

        wpOOWMediaUploaders[uploaderJsData.id].open();

    }
});