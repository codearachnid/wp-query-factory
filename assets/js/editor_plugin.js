var wpqf_id = jQuery('#wp-query-factory-mce-select-query');
var wpqf_query = wpqf_id.find('#query_factory_id');
wpqf_id.dialog({ 
    autoOpen: false,
    width: 450,
    zIndex: 998,
    height: 200,
    resizable: false
});
wpqf_id.find('.insert').click(function(){
    if( wpqf_query.val() != null) {
        shortcode = '[query_factory id="' + wpqf_query.val() + '"]';
        wpqf_shortcode( shortcode );
        wpqf_id.find('.close').trigger('click');
    }
});
wpqf_id.find('.close').click(function(){
    wpqf_id.dialog('close');
    wpqf_query.val('');
});
(function() {
    tinymce.create('tinymce.plugins.wp_query_factory', {
        init : function(ed, url) {
            ed.addButton('wp_query_factory', {
                onclick : function() {
                    wpqf_id.dialog('open');
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
        getInfo : function() {
            return {
                longname : 'WP Query Factory',
                author : 'Timothy Wood (@codearachnid)',
                authorurl : 'http://www.codearachnid.com/',
                infourl : 'http://www.codearachnid.com/',
                version : "1.0"
            };
        }
    });
    tinymce.PluginManager.add('wp_query_factory', tinymce.plugins.wp_query_factory);
})();
function wpqf_shortcode( shortcode ) {
    if (query_factory_id != null && query_factory_id != 'undefined') {
        if ( typeof tinyMCE != 'undefined' && ( ed = tinyMCE.activeEditor ) && !ed.isHidden() ) {
            ed.focus();
            if (tinymce.isIE)
                ed.selection.moveToBookmark(tinymce.EditorManager.activeEditor.windowManager.bookmark);

            ed.execCommand('mceInsertContent', false, shortcode);
        } else
            edInsertContent(edCanvas, shortcode);
    }
}