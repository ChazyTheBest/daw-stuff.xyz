Dropzone.autoDiscover = false;

$(function ()
{
    $( 'div#myDropzone' ).dropzone(
    {
        url: '/product/create',
        paramName: 'images',
        acceptedFiles: 'image/jpeg,image/png',
        thumbnailWidth: 180,
        thumbnailHeight: 180,
        autoProcessQueue: false,
        uploadMultiple: true,
        maxFiles: 50,
        parallelUploads: 50,
        filesizeBase: 1024,
        maxFilesize: 7,
        addRemoveLinks: false,
        dictDefaultMessage: 'Drop images here or click to manually select.',
        dictFallbackMessage: 'Your browser does not support drag and drop.',
        dictFallbackText: 'Please, click upload files to manually select images.',
        dictFileTooBig: 'Max file size: {{maxFilesize}}MiB.',
        dictInvalidFileType: 'Allowed files: JPG and PNG.',
        dictResponseError: 'Unknown error. Please, contact support ({{statusCode}}).',
        dictCancelUpload: 'Cancel upload',
        dictCancelUploadConfirmation: 'Are you sure?',
        dictRemoveFile: 'Remove file',
        dictRemoveFileConfirmation: 'Are you sure?',
        dictMaxFilesExceeded: 'You can only upload 50 files.',
        init: function()
        {
            const myDropzone = this;
            let form = null;

            function getData( data )
            {
                form = data;
                myDropzone.processQueue();
            }

            $('form > input[type=submit]').on('click', function(e)
            {
                dropzoneData = function ( data )
                {
                    getData(data)
                }
            });

            myDropzone.on('sendingmultiple', function(data, xhr, formData)
            {
                for (let pair of new FormData(form).entries()) { formData.append(pair[0], pair[1]); }
            });

            myDropzone.on('successmultiple', function(files, response)
            {
                myDropzone.removeAllFiles(true);

                if (response.message)
                    alert(response.message)

                else if (response.redirect)
                    window.location.assign(response.redirect);
            });

            myDropzone.on('errormultiple', function(files, response)
            {
                //myDropzone.removeFile(files);
                //noty({ text: response })
                alert(response)
            });
        }/*,
        accept: function(file, done) {
            file.acceptDimensions = done;
            file.rejectDimensions = function() { done('Maximum resolution is 4500 by 4500 pixels).') }
        }*/
    });

    $( 'select.cat' ).on('change', function ()
    {
        $.get('/product/create?cat=' + this.value, function (data)
        {
            if (data.subcategories.length === 0)
                return;

            let html = '<option>Select a Subcategory</option>';

            Object.keys(data.subcategories).forEach(function (i) {
                html += '<option value="' + i + '">' + data.subcategories[i] + '</option>';
            });

            $( 'select.sub' ).empty().html(html);
        });
    })
});
