(function() {
    tinymce.create('tinymce.plugins.Absportfolio', {
        init : function(ed, url) {
        
            ed.addButton('abs_portfolio', {
                title : 'ABS Portfolio Shortcode',
                cmd : 'abs_portfolio',
                image : url + '/abs_portfolio.png'
            });
 
             
            ed.addCommand('abs_portfolio', function() {
                var categoryname = prompt("Put the category name"),
                    shortcode;
                        shortcode = '[abs_portfolio category="'+ categoryname +'"]' ;
                        ed.execCommand('mceInsertContent', 0, shortcode);
                 
                    
            });
        },
        // ... Hidden code
    });
    // Register plugin
    tinymce.PluginManager.add( 'absportfolio', tinymce.plugins.Absportfolio );
})();