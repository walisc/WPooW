jQuery("#{{id}}{{upload_button}}").on("click",  function (e) {
    e.preventDefault();

    if (wpOOWMediaUploaders["{{id}}"]) {
        wpOOWMediaUploaders["{{id}}"].open();
        return;
    }
    loadMediaUploader({
        id : "{{id}}",
        title: "{{title}}",
        buttonText: "{{buttonText}}",
        multiple: "{{multiple}}",
        selected_file_preview: "{{selected_file_preview}}",
        selected_file_display: "{{selected_file_display}}",
        selected_file: "{{selected_file}}"
    });
});


