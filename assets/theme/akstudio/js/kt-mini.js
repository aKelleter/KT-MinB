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
        moreUploadTag += '<span class="input-group-text"><a class="notUnderline colorlink" href="javascript:deleteAttachment(' + upload_number + ')" onclick="return confirm(\'Are you really want to delete? \')"><i class="mdi mdi-trash-can-outline vBot"></i></a></span></div>';
        $('<div id="deleteAttach' + upload_number + '">' + moreUploadTag + '</div>').fadeIn('slow').appendTo('#more-upload');
        upload_number++;
    });
});

// TinyMCE / Popover / Tooltip
$(document).ready(function() {
    
      
    tinymce.init({
      selector: 'textarea.editor',
      plugins: 'advlist autolink lists link image charmap preview anchor pagebreak code',
      toolbar_mode: 'floating',
    });
    
    // Popover
    const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');
    const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl));
    
    // Tooltip
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
    });
    
    // Fade Out Div message Index
    var fade_out = function() {
        $("#message-fade").fadeOut().empty();
        cleanUriQueries();        
    }    
    setTimeout(fade_out, 3000);
});


/**
 * Suppression d'un fichier joint
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

/**
 * Nettoie les paramètres de l'URI
 * 
 * @returns {undefined}
 */
function cleanUriQueries(){
    
    var uri = window.location.toString();
    //alert( $(location).attr('pathname'));
    
    // Si on est sur la page index
    if (window.location.href.indexOf("index") > -1 || window.location.href.indexOf("edit-article") > -1)
    {
        if (uri.indexOf("?") > 0)
        {
            var clean_uri = uri.substring(0, uri.indexOf("?"));
            window.history.replaceState({}, document.title, clean_uri);
        }
    }    
}
