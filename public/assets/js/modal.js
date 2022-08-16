function OkayModal(title, message) {

    var html = '';

    html += '<div class="modal fade" id="modal-default">'
    html += '<div class="modal-dialog">'
    html += '<div class="modal-content">'
    html += ' <div class="modal-header">'
    html += '<button type="button" class="close" data-dismiss="modal" aria-label="Close">'
    html += '<span aria-hidden="true">&times;</span></button>'
    html += '<h4 class="modal-title">' + title + '</h4>'
    html += '</div>'
    html += '<div class="modal-body">'
    html += '<p>' + message + '</p>'
    html += '</div>'
    html += '<div class="modal-footer">'
        // html+=            '<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>'
    html += '<button type="button" class="btn btn-primary popupModalButton" data-dismiss="modal">Okay</button>'
    html += '</div>'
    html += '</div>'
    html += ' </div>'
    html += '</div>'

    $('#displaymodal').html(html);


}