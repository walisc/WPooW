jQuery(document).ready(function($) {

    var mediaUploaders = {};

    i = 1;


    while (window.hasOwnProperty("uploaderJsData_bag_"+i)) {

        uploaderJsData = window["uploaderJsData_bag_"+i];

        $("#" + uploaderJsData.id + uploaderJsData.upload_button).on("click", {p_uploaderJsData: uploaderJsData},  function (e) {
            //Tricky js. Needed to do this (pass the object) as it was using the global one, which will be set as the last element
            uploaderJsData = e.data.p_uploaderJsData;
            e.preventDefault();

            if (mediaUploaders[uploaderJsData.id]) {
                mediaUploaders[uploaderJsData.id].open();
                return;
            }
            loadMediaUploader(uploaderJsData);
        });
        i++;
    }
    loadMediaUploader = function (uploaderJsData) {


        mediaUploaders[uploaderJsData.id] = wp.media({
            title: uploaderJsData.title,
            button: {
                text: uploaderJsData.buttonText
            },
            multiple: uploaderJsData.multiple == "true"
        });

        mediaUploaders[uploaderJsData.id].on("select", function () {
            uploadedItem = mediaUploaders[uploaderJsData.id].state().get('selection').first().toJSON();


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

        mediaUploaders[uploaderJsData.id].open();

    }
});