<?php
/*
***********************************************************************************
DaDaBIK (DaDaBIK is a DataBase Interfaces Kreator) https://dadabik.com/
Copyright (C) 2001-2026 Eugenio Tacchini

This program is distributed "as is" and WITHOUT ANY WARRANTY, either expressed or implied, without even the implied warranties of merchantability or fitness for a particular purpose.

This program is distributed under the terms of the DaDaBIK license, which is included in this package (see dadabik_license.txt). For all the details see dadabik_license.txt.

If you are unsure about what you are allowed to do with this license, feel free to contact info@dadabik.com
***********************************************************************************
*/
?>

function dadabikUploader($el, $template) {
    let uploaderBaseConfiguration = {
        url: 'api_fe.php?function=upload_file',
        html: $template.html()
    };

    const uploaderConfig = {
        onBeforeUpload: (root) => {
            var $el = $(root);
            var payload = {
                tablename: $el.attr('data-tablename'),
                form_type: $el.attr('data-form-type'),
                id: $el.attr('data-id'),
                fieldname: $el.attr('data-field-name'),
                fid: $el.attr('data-fid'),
            };
            return payload;
        },
        onDragEnter: (root) => {
            console.log('Drag Enter!');
            ($(root).find('[data-uploader-drop-zone]'))
                .addClass('dropzone_enter');
        },
        onDragLeave: (root) => {
            console.log('Drag leave!');
            ($(root).find('[data-uploader-drop-zone]'))
                .removeClass('dropzone_enter');

        },
        onUploadStart: (root, file) => {
            $(root).find('[data-preview]').empty();
             $(root).find('[data-uploader-input]')[0].value = '';
            console.log('Upload started!', file);
        },
        onUploadComplete: (root, response, uploadedFile) => {
            console.log('Upload ok!', uploadedFile);
            var resultMessage = response.result === 'done'
                ? '<?php echo 'File uploaded! The file will replace the current one (if any) after saving the form.';?>'
                : response.error_message;
            $(root).find('[data-uploader-result]').html(resultMessage);

            if (response.file) {
                var $img = $('<img>').attr('src', response.file).addClass('object-contain w-full h-full');
                $(root).find('[data-preview]').append($img);
            }
        },
        onUploadProgress: (root, e) => {
            console.log('Progress    !', (e.loaded * 100.0 / e.total) || 100 + '%');
        },
        onUploadError: (root, errorResponse) => {
            console.log('Upload Error!' + JSON.stringify(errorResponse));

            /*const textError = errorResponse.reseponseText || null;
            const jsonError = (errorResponse.responseJSON || {}).error_message || null;
            const status = errorResponse.status;
            const statusText = errorResponse.statusText;

            $(root).find('[data-uploader-result]').html(jsonError || statusText)*/
            
            $(root).find('[data-uploader-result]').html('<?php echo 'Generic upload error!';?>')
        }
    };

    const options = Object.assign(uploaderBaseConfiguration, uploaderConfig, {});
    var uploader = new FileUploader(uploaderBaseConfiguration)

    uploader.attachTo($el);
}
