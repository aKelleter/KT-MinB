// Ajouter des fichiers joints
$(document).ready(function() {
    $("input[id^='file_article']").each(function() {
        var id = parseInt(this.id.replace("file_article", ""));
        $("#file_article" + id).change(function() {
                if ($("#file_article" + id).val() != "") {
                        $("#more-upload-link").show();
                }                
        });        
    });
});

// Supprimer des fichiers joints
$(document).ready(function() {
    var upload_number = 2;
    $('#attachMore').click(function() {
        //add more file
        var moreUploadTag = '';
        moreUploadTag += '<div class="input-group mb-3">';
        moreUploadTag += '<input class="form-control" type="file" id="file_article' + upload_number + '" name="file_article' + upload_number + '"/>';
        moreUploadTag += '<span class="input-group-text"><a class="notUnderline" href="javascript:deleteAttachment(' + upload_number + ')" onclick="return confirm(\'Are you really want to delete? \')"><i class="mdi mdi-trash-can-outline vBot"></i></a></span></div>';
        $('<div id="deleteAttach' + upload_number + '">' + moreUploadTag + '</div>').fadeIn('slow').appendTo('#more-upload');
        upload_number++;
    });
});

// TinyMCE and Popover
$(document).ready(function() {
    
      
    tinymce.init({
      selector: 'textarea.editor',
      plugins: 'advlist autolink lists link image charmap preview anchor pagebreak',
      toolbar_mode: 'floating',
    });
    
    // Popover
    const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');
    const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl));
});


/**
 * Fonction de suppression d'un fichier joint
 * 
 * @param {type} eleId
 * @returns {undefined}*
 */
function deleteAttachment(eleId) {
    var ele = document.getElementById("deleteAttach" + eleId);
    ele.parentNode.removeChild(ele);
    
    // Affiche le lien d'ajout si on supprime le premier input
    var elementIdExist = document.body.contains(document.getElementById("file_article1"));
    //DEBUG//console.log('Element exist:' + elementIdExist);
    if (elementIdExist === false) {
            $("#more-upload-link").show();
    }
}
