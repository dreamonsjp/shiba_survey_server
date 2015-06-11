$(document).ready(function(){
    doFileUpload();
});

function doFileUpload(){
    var fieldname = $('#ia').val();

    /* Load the previously uploaded files */
    var filecount = window.parent.window.$('#'+fieldname+'_filecount').val();
    $('#'+fieldname+'_filecount').val(filecount);

    var image_extensions = new Array("gif", "jpeg", "jpg", "png", "swf", "psd", "bmp", "tiff", "jp2", "iff", "bmp", "xbm", "ico");

    if (filecount > 0)
    {
        var jsontext = window.parent.window.$('#'+fieldname).val();
        var json = eval('(' + jsontext + ')');

        var i;
        $('#'+fieldname+'_licount').val(filecount);

        for (i = 1; i <=  filecount; i++)
        {
            
            if (isValueInArray(image_extensions, json[i-1].ext.toLowerCase()))
                previewblock += "<img src='uploader.php?filegetcontents="+json[i-1].filename+"' height='60px' />"+decodeURIComponent(json[i-1].name);
            

            // add file to the list
            $('#'+fieldname+'_listfiles').append(previewblock);
        }
    }

    // The upload button
    var button = $('#button1'), interval;
	 new AjaxUpload(button, {
        action: uploadurl + '/sid/'+surveyid+'/preview/'+questgrppreview+'/fieldname/'+fieldname+'/',
        name: 'uploadfile',
        data: {
            valid_extensions : $('#'+fieldname+'_allowed_filetypes').val(),
            max_filesize : $('#'+fieldname+'_maxfilesize').val(),
            preview : $('#preview').val(),
            surveyid : surveyid,
            fieldname : fieldname
        },
		autoSubmit: true,
        onSubmit : function(file, ext){

            var maxfiles = parseInt($('#'+fieldname+'_maxfiles').val());
            var filecount = parseInt($('#'+fieldname+'_filecount').val());
            var allowed_filetypes = $('#'+fieldname+'_allowed_filetypes').val().split(",");

            /* If maximum number of allowed filetypes have already been uploaded,
             * do not upload the file and display an error message ! */
            if (filecount >= maxfiles)
            {
                $('#notice').html('<p class="error">'+translt.errorNoMoreFiles+'</p>');
                return false;
            }

            /* If the file being uploaded is not allowed,
             * do not upload the file and display an error message ! */
            var allowSubmit = false;
            for (var i = 0; i < allowed_filetypes.length; i++)
            {
                //check to see if it's the proper extension
                if (jQuery.trim(allowed_filetypes[i].toLowerCase()) == jQuery.trim(ext.toLowerCase()) )
                {
                    //it's the proper extension
                    allowSubmit = true;
                    break;
                }
            }
            if (allowSubmit == false)
            {
                $('#notice').html('<p class="error">'+translt.errorOnlyAllowed.replace('%s',$('#'+fieldname+'_allowed_filetypes').val())+'</p>');
                return false;
            }

            // change button text, when user selects file
            button.text(translt.uploading);

            // If you want to allow uploading only 1 file at time,
            // you can disable upload button
            this.disable();

            // Uploding -> Uploading. -> Uploading...
            interval = window.setInterval(function(){
                var text = button.text();
                if (text.length < 13){
                    button.text(text + '.');
                } else {
                    button.text(translt.uploading);
                }
            }, 400);
        },
        onComplete: function(file, response){
            button.text(translt.selectfile);
            window.clearInterval(interval);
            // enable upload button
            this.enable();
				//alert(response);
			$( "p.list" ).append(response);
        }
    });
	
   

    // if it has been jst opened, the upload button should be automatically clicked !
    // TODO: auto open using click() not working at all ! :(
}
