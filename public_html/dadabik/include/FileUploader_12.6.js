/*
***********************************************************************************
DaDaBIK (DaDaBIK is a DataBase Interfaces Kreator) https://dadabik.com/
Copyright (C) 2001-2026 Eugenio Tacchini

This program is distributed "as is" and WITHOUT ANY WARRANTY, either expressed or implied, without even the implied warranties of merchantability or fitness for a particular purpose.

This program is distributed under the terms of the DaDaBIK license, which is included in this package (see dadabik_license.txt). For all the details see dadabik_license.txt.

If you are unsure about what you are allowed to do with this license, feel free to contact info@dadabik.com
***********************************************************************************
*/

function FileUploader(options) {
    var url = options.url;
    var $template = options.html;

    var root = null;
    var id = null;

    var dropArea = null;
    var fileInput = null;
    var label = null;
    var progressBar = null;
    var gallery = null;

    var dropEnabled = false;
    var progressEnabled = false;
    var previewEnabled = false;

    let uploadProgress = [];

    // Closure per l'onchange sull 'input[type=file]
    var uploadHandler = function () {
        handleFilesUpload(fileInput.files);
    }

    var nullCallback = function () {
    };

    var onBeforeUpload = nullCallback;
    var onUploadStart = nullCallback;
    var onUploadProgress = nullCallback;
    var onUploadComplete = nullCallback;
    var onUploadError = nullCallback;
    var onDragEnter = nullCallback;
    var onDragLeave = nullCallback;

    function attachTo($el) {
        root = $el;
        root.html($template);

        onBeforeUpload = options.onBeforeUpload || nullCallback;
        onUploadStart = options.onUploadStart || nullCallback;
        onUploadProgress = options.onUploadProgress || nullCallback;
        onUploadComplete = options.onUploadComplete || nullCallback;
        onUploadError = options.onUploadError || nullCallback;
        onDragEnter = options.onDragEnter ? function () {
            options.onDragEnter(root);
        } : nullCallback;
        onDragLeave = options.onDragLeave ? function () {
            options.onDragLeave(root);
        } : nullCallback;

        initialize();
    }

    function initialize() {
        // Generiamo un id univoco per il binding tra label e file input
        id = generateUniqueId();

        // Referenziamo i vari elementi nel template
        dropArea = root.find('[data-uploader-drop-zone]').get(0);
        fileInput = root.find('[data-uploader-input]').get(0);
        label = root.find('[data-uploader-label]').get(0);
        progressBar = root.find('[data-uploader-progress-bar]').get(0);
        gallery = root.find('[data-uploader-gallery]').get(0);

        $(fileInput).attr('id', 'file-' + id);
        $(label).attr('for', 'file-' + id);

        dropEnabled = dropArea !== undefined;
        progressEnabled = progressBar !== undefined;
        previewEnabled = gallery !== undefined;

        bindEvents();
    }

    function destroy() {
        unbindEvents();
        $(root).empty();
    }

    function preventDefaults(e) {
        e.preventDefault()
        e.stopPropagation()
    }

    function handleDrop(e) {
        var dt = e.dataTransfer
        var files = dt.files

        handleFilesUpload(files)
    }

    function initializeProgress(numFiles) {
        progressBar.value = 0
        uploadProgress = []

        

        for (let i = numFiles; i > 0; i--) {
            uploadProgress.push(0)
        }
    }

    function updateProgress(fileNumber, percent) {
        uploadProgress[fileNumber] = percent
        progressBar.value = uploadProgress.reduce((tot, curr) => tot + curr, 0) / uploadProgress.length
    }

    function handleFilesUpload(files) {
        files = [...files]

        if (progressEnabled) {
            initializeProgress(files.length)
        }

        files.forEach(uploadFile)
    }

    function previewFile(file) {
        if (previewEnabled) {
            let reader = new FileReader()
            reader.readAsDataURL(file)
            reader.onloadend = function () {
                let img = document.createElement('img')
                img.src = reader.result
                gallery.appendChild(img)
            }
        }
    }

    function uploadFile(file, i) {
        var formData = new FormData()
        formData.append('file', file)

        var additionalFormData = onBeforeUpload(root);

        if (additionalFormData) {
            for (var key in additionalFormData) {
                if (additionalFormData.hasOwnProperty(key)) {
                    formData.append(key, additionalFormData[key]);
                }
            }
        }

        onUploadStart(root, file);

        if (progressEnabled) {
            updateProgress(i, 0)
        }

        var promise = $.ajax({
            xhr: function () {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function (e) {
                    onUploadProgress(root, e);
                    if (progressEnabled) {
                        updateProgress(i, (e.loaded * 100.0 / e.total) || 100)
                    }
                })
                return xhr;
            },
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
            },
            url,
            type: 'POST',
            processData: false, // important
            contentType: false, // important
            data: formData
        });

        promise.done((response) => {
            if (progressEnabled) {
                updateProgress(i, 100) // <- Add this
            }
            onUploadComplete(root, response, file);
            previewFile(file);
        });

        promise.fail((jqXHR, textStatus, errorThrown) => {
            onUploadError(root, jqXHR);
        })
    }

    function bindEvents() {

        fileInput.addEventListener('change', uploadHandler);

        if (dropEnabled) {
            // Prevent default drag behaviors
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, preventDefaults, false)
                document.body.addEventListener(eventName, preventDefaults, false)
            });

            // Highlight drop area when item is dragged over it
            ['dragenter', 'dragover'].forEach(eventName => {
                dropArea.addEventListener(eventName, onDragEnter, false)
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, onDragLeave, false)
            });

            dropArea.addEventListener('drop', handleDrop, false)
        }
    }

    function unbindEvents() {

        if (dropEnabled) {
            // Prevent default drag behaviors
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropArea.removeEventListener(eventName, preventDefaults)
                document.body.removeEventListener(eventName, preventDefaults)
            });

            // Highlight drop area when item is dragged over it
            ['dragenter', 'dragover'].forEach(eventName => {
                dropArea.removeEventListener(eventName, onDragEnter)
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropArea.removeEventListener(eventName, onDragLeave)
            });

            dropArea.removeEventListener('drop', handleDrop);
        }

        fileInput.removeEventListener('change', uploadHandler);

        root.html('');
    }

    function generateUniqueId() {
        var randomId = Math.floor(Math.random() * 10000);
        var existingElement = $('[data-upload-' + randomId + ']');

        if (existingElement.length > 0) {
            return generateUniqueId();
        }

        id = randomId;
        root.attr('data-upload-' + id, true)

        return id;
    }

    return {
        attachTo,
        destroy,
    }
}
