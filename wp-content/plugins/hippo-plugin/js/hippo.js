/*------------------------------------*\

 Name: hippo.js
 Author: Theme Hippo
 Author URI: http://www.themehippo.com
 Version: 0.1

 \*------------------------------------*/

jQuery(function ($) {





    // Uploading files


    $(document).off("click", "button.hippo_upload_image_button");
    $(document).on('click', 'button.hippo_upload_image_button', function (event) {

        event.preventDefault();
        event.stopPropagation();
        var $this = $(this);
        var hippo_file_frame;

        if (typeof wp !== 'undefined' && wp.media && wp.media.editor) {

            // If the media frame already exists, reopen it.
            if (hippo_file_frame) {
                hippo_file_frame.open();
                return;
            }

            // Create the media frame.
            hippo_file_frame = wp.media.frames.select_image = wp.media({
                title    : 'Choose',
                button   : {
                    text : 'Use this'
                },
                multiple : false,
            });

            // When an image is selected, run a callback.
            hippo_file_frame.on('select', function () {
                var attachment = hippo_file_frame.state().get('selection').first().toJSON();

                if ($.trim(attachment.id) !== '') {
                    $this.prev().val(attachment.id);
                    var url = ( typeof(attachment.sizes.thumbnail) == 'undefined' ) ? attachment.sizes.full.url : attachment.sizes.thumbnail.url;
                    $this.closest('.meta-image-field-wrapper').find('img').attr('src', url);
                    $this.next().show();
                }
                //file_frame.close();
            });

            // When open select selected
            hippo_file_frame.on('open', function () {

                // Grab our attachment selection and construct a JSON representation of the model.
                var selection = hippo_file_frame.state().get('selection');
                var current = $this.prev().val();
                var attachment = wp.media.attachment(current);
                attachment.fetch();
                selection.add(attachment ? [attachment] : []);
            });

            // Finally, open the modal.
            hippo_file_frame.open();
        }
    });

    $(document).on('click', 'button.hippo_remove_image_button', function () {

        var $this = $(this);

        var placeholder = $this.closest('.meta-image-field-wrapper').find('img').data('placeholder');
        $this.closest('.meta-image-field-wrapper').find('img').attr('src', placeholder);
        $this.prev().prev().val('');
        $this.hide();
        return false;
    });


    (function () {

        $("select.hippo-plugin-select2").select2({
            placeholder : "Select",
            allowClear  : true
        });

        function icon_format(state) {
            if (!state.id) {
                return state.text;
            }
            return $("<span><i class='" + state.id + "'></i> &nbsp; &nbsp; " + state.text + "</span>");
        }

        function old_icon_format(state) {
            if (!state.id) return state.text; // optgroup
            return "<span><i class='" + state.id + "'></i> &nbsp; &nbsp; " + state.text + "</span>";
        }


        $("select.hippo-plugin-select2-icon").select2({
            templateResult    : icon_format,
            templateSelection : icon_format,
            placeholder       : "Select Icon",
            allowClear        : true,
            formatResult      : old_icon_format,
            formatSelection   : old_icon_format
        });

        /*

         $("select.hippo-plugin-select2-old-icon").select2({
         formatResult    : old_icon_format,
         formatSelection : old_icon_format,
         placeholder     : "Select Icon",
         allowClear      : true,
         escapeMarkup    : function (m) {
         return m;
         }
         });*/


        $('input.hippocolorpicker').wpColorPicker();

        $('[data-depends]').hippoFormFieldDependency();


    }());


});




