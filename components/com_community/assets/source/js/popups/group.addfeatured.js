(function( root, $, factory ) {

    joms.popup || (joms.popup = {});
    joms.popup.group || (joms.popup.group = {});
    joms.popup.group.addFeatured = factory( root, $ );

    define([ 'utils/popup' ], function() {
        return joms.popup.group.addFeatured;
    });

})( window, joms.jQuery, function( window ) {

var popup, elem, id;

function render( _popup, _id ) {
    if ( elem ) elem.off();
    popup = _popup;
    id = _id;

    joms.ajax({
        func: 'groups,ajaxAddFeatured',
        data: [ id ],
        callback: function( json ) {
            popup.items[0] = {
                type: 'inline',
                src: buildHtml( json )
            };

            popup.st.callbacks || (popup.st.callbacks = {});
            popup.st.callbacks.close = function() {
                window.location.reload();
            };

            popup.updateItemHTML();
        }
    });
}

function buildHtml( json ) {
    json || (json = {});

    return [
        '<div class="joms-popup joms-popup--whiteblock">',
        '<div class="joms-popup__title"><button class="mfp-close" type="button" title="',window.joms_lang.COM_COMMUNITY_CLOSE_BUTTON_TITLE,'">×</button>', json.title, '</div>',
        '<div class="joms-popup__content joms-popup__content--single">', ( json.html || json.error || '' ), '</div>',
        '<div class="joms-popup__action">',
        '<button class="joms-button--neutral joms-button--small joms-js--button-close">', window.joms_lang.COM_COMMUNITY_CLOSE_BUTTON, '</button>',
        '</div>',
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
