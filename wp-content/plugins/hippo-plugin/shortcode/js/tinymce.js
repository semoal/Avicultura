/*------------------------------------*\

 Name: tinymce.js
 Author: Theme Hippo
 Author URI: http://www.themehippo.com
 Version: 0.1

 \*------------------------------------*/

jQuery(function ($) {

    tinymce.create('tinymce.plugins.em_shortcode', {
        init : function (ed, url) {
            // Register command for when button is clicked
            ed.addCommand('em_action', function () {
                var mce_selected_contents = tinyMCE.activeEditor.selection.getContent();

                tb_show(em_shortcode_obj.window_title, em_shortcode_obj.shortcode_popup_url + '&width=640&height=300');

                //tinymce.execCommand('mceInsertContent', false, data);
            });

            // Register buttons - trigger above command when clicked
            ed.addButton('em_button',
                {
                    title : em_shortcode_obj.button_title,
                    cmd   : 'em_action',
                    //image: url + '/../images/em_shortcode.png' ,
                    icon  : 'icon dashicons-carrot'
                });
        }
    });

    // Register our TinyMCE plugin
    // first parameter is the button ID1
    // second parameter must match the first parameter of the tinymce.create() function above
    tinymce.PluginManager.add('em_button', tinymce.plugins.em_shortcode);
});