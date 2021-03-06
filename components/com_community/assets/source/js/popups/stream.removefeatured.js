(function( root, $, factory ) {

    joms.popup || (joms.popup = {});
    joms.popup.stream || (joms.popup.stream = {});
    joms.popup.stream.removeFeatured = factory( root, $ );

    define([ 'utils/popup' ], function() {
        return joms.popup.stream.removeFeatured;
    });

})( window, joms.jQuery, function( window ) {

var popup, elem, id;

function render( _popup, _id ) {
    if ( elem ) elem.off();
    popup = _popup;
    id = _id;

    var context = window.joms_page;
    var contextid;

    if ( context === 'profile' ) {
        context = 'profile';
        contextid = window.joms_user_id;
    } else if ( context === 'groups' ) {
        context = 'group';
        contextid = window.joms_group_id;
    } else if ( context === 'events' ) {
        context = 'event';
        contextid = window.joms_event_id;
    } else {
        context = 'frontpage';
        contextid = 0;
    }

    joms.ajax({
        func: 'system,ajaxUnfeatureStream',
        data: [ context, contextid, id ],
        callback: function( json ) {
            if ( json.success ) {
                window.location.reload();
                return;
            }

            popup.items[0] = {
                type: 'inline',
                src: buildHtml( json )
            };

            popup.updateItemHTML();
        }
    });
}

function buildHtml( json ) {
    json || (json = {});

    return [
        '<div class="joms-popup joms-popup--whiteblock">',
        '<div class="joms-popup__title"><button class="mfp-close" type="button" title="',window.joms_lang.COM_COMMUNITY_CLOSE_BUTTON_TITLE,'">×</button>', (json.title || '&nbsp;'), '</div>',
        '<div class="joms-popup__content joms-popup__content--single">', ( json.error || json.message ), '</div>',
        '</div>'
    ].join('');
}

// Exports.
return function( id ) {
    joms.util.popup.prepare(function( mfp ) {
        render( mfp, id );
    });
};

});
