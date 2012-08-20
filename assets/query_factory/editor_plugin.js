
(function() {
    tinymce.create('tinymce.plugins.wp_query_factory', {
        init : function(ed, url) {
            ed.addButton('wp_query_factory', {
                title : 'wp_query_factory.query_factory',
                image : url + '/button.png',
                onclick : function() {
                    var query_factory_id = prompt("Query Factory", "Enter the id or url for your video");
                    if (query_factory_id != null && query_factory_id != 'undefined')
                        ed.execCommand('mceInsertContent', false, '[query_factory id="' + query_factory_id + '"]');
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