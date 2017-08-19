
(function () {
    tinymce.create('tinymce.plugins.HippoButton', {
        /**
         * Initializes the plugin, this will be executed after the plugin has been created.
         * This call is done before the editor instance has finished it's initialization so use the onInit event
         * of the editor instance to intercept that event.
         *
         * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
         * @param {string} url Absolute URL to where the plugin is located.
         */
        init: function (ed, url) {


            ed.addButton('addspan', {
                title: 'Add Span',
                cmd  : 'addspan',
                image: url + '/images/span.png'
            });


            ed.addCommand('addspan', function () {
                var selected_text = ed.selection.getContent();
                var return_text;
                return_text = ' <span>' + selected_text + '</span>';
                ed.execCommand('mceInsertContent', 0, return_text);
            });




        },


        getInfo: function () {
            return {
                longname : 'Hippo MCE Buttons',
                author   : 'Theme Hippo',
                authorurl: 'http://themeforest.net/user/themehippo',
                infourl  : 'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/example',
                version  : "0.1"
            };
        }
    });

// Register plugin
    tinymce.PluginManager.add('hippo_buttons', tinymce.plugins.HippoButton);
})();