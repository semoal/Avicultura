jQuery(function ($) {

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


        $("select.hippo-plugin-select2-icon").select2({
            templateResult    : icon_format,
            templateSelection : icon_format
        });


    }());


    (function () {

        $(".meta_box_items.icons .meta-icon-meta-box label").on("click", function (e) {
            //e.stopPropagation();
            $(this).closest('.meta-icon-meta-box').find('label').removeClass('selected');
            $(this).addClass('selected');
        });

        var tb_opener = null;
        $('a.meta-icon-selector').on('click', function () {
            tb_opener = this;
            var href = $(this).attr('href');
            tb_show('Icons', href);
            return false;
        });

        $('a.meta-icon-remove').on('click', function () {
            $(this).closest('ul.meta_box_items').find('.preview-icon > i').removeClass();
            $(this).closest('ul.meta_box_items').find('.hidden-textbox').val('');
            $(this).hide();
            return false;
        });

        $('.meta-icon-meta-box i').on('click', function () {
            var iconname = $(this).prev().val();
            $(tb_opener).closest('ul.meta_box_items').find('.preview-icon > i').removeClass().addClass(iconname);
            $(tb_opener).closest('ul.meta_box_items').find('.hidden-textbox').val(iconname);
            $(tb_opener).next().show();
            tb_remove();
            tb_opener = null;
        });

    }());


    (function () {
        $("input.datepicker").datepicker({
            dateFormat : 'yy-mm-dd'
        });
    }());

    (function () {
        $('input[type="range"]').on('input', function () {
            var value = $(this).val().trim();
            $(this).next().find('>span').text(value);
        });
    }());

    (function () {
        $('input.hippo-map-autocomplete-input').HippoMapForMeta();
    }());


    $("input[name=post_format]:radio").on('change', function (e) {

        var value = $(this).val();
        if (value == '0') {
            value = 'standard'
        }
        $('#post_video_meta, #post_audio_meta, #post_gallery_meta').hide();
        $('#post_' + value + '_meta').show();
    });

    $("input[name=post_format]:radio:checked").trigger('change');

    $('input.metacolorpicker').wpColorPicker();

    // the upload image button, saves the id and outputs a preview of the image
    var imageFrame;
    $('body').on('click', '.meta_box_upload_image_button', function (event) {
        event.preventDefault();

        var options, attachment;

        $self = $(event.target);
        $div = $self.closest('div.meta_box_image');

        // if the frame already exists, open it
        if (imageFrame) {
            imageFrame.open();
            return;
        }

        // set our settings
        imageFrame = wp.media({
            title    : 'Choose Image',
            multiple : false,
            library  : {
                type : 'image'
            },
            button   : {
                text : 'Use This Image'
            }
        });

        // set up our select handler
        imageFrame.on('select', function () {
            selection = imageFrame.state().get('selection');

            if (!selection)
                return;

            // loop through the selected files
            selection.each(function (attachment) {
                console.log(attachment);
                var src = attachment.attributes.sizes.full.url;
                var id = attachment.id;

                $div.find('.meta_box_preview_image').attr('src', src);
                $div.find('.meta_box_upload_image').val(id);
            });
        });

        // open the frame
        imageFrame.open();
    });

    // the remove image link, removes the image id from the hidden field and replaces the image preview
    $('body').on('click', '.meta_box_clear_image_button', function () {
        var defaultImage = $(this).parent().siblings('.meta_box_default_image').text();
        $(this).parent().siblings('.meta_box_upload_image').val('');
        $(this).parent().siblings('.meta_box_preview_image').attr('src', defaultImage);
        return false;
    });

    // the file image button, saves the id and outputs the file name
    var fileFrame;
    $('body').on('click', '.meta_box_upload_file_button', function (e) {
        e.preventDefault();

        var options, attachment;

        $self = $(event.target);
        $div = $self.closest('div.meta_box_file_stuff');

        // if the frame already exists, open it
        if (fileFrame) {
            fileFrame.open();
            return;
        }

        // set our settings
        fileFrame = wp.media({
            title    : 'Choose File',
            multiple : false,
            /*library : {
             type: 'file'
             },*/
            button   : {
                text : 'Use This File'
            }
        });

        // set up our select handler
        fileFrame.on('select', function () {
            selection = fileFrame.state().get('selection');

            if (!selection)
                return;

            // loop through the selected files
            selection.each(function (attachment) {
                //console.log(attachment);
                var src = attachment.attributes.url;
                var id = attachment.id;

                $div.find('.meta_box_filename').text(src);
                $div.find('.meta_box_upload_file').val(src);
                $div.find('.meta_box_file').addClass('checked');
            });
        });

        // open the frame
        fileFrame.open();
    });

    // the remove image link, removes the image id from the hidden field and replaces the image preview
    $('body').on('click', '.meta_box_clear_file_button', function () {
        $(this).parent().siblings('.meta_box_upload_file').val('');
        $(this).parent().siblings('.meta_box_upload_media').val('');
        $(this).parent().siblings('.meta_box_filename').text('');
        $(this).parent().siblings('.meta_box_file').removeClass('checked');
        return false;
    });


    $('body').on('click', '.meta_box_upload_media_button', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();

        if (typeof wp !== 'undefined' && wp.media && wp.media.editor) {


            var opener = $(this);
            var id = 'id_' + $(this).attr('name');
            var value = opener.parent().find('input.meta_box_upload_media').val();
            var metabox_media;

            if (metabox_media) {
                metabox_media.open(id);
                return;
            }


            metabox_media = wp.media.frames.metabox_media = wp.media({
                //Create our media frame
                editing   : true,
                className : 'media-frame shortcode-media-frame',
                frame     : 'select', //Allow Select Only
                multiple  : false //Disallow Mulitple selections
                //,library  : {
                //  type: 'image' //Only allow images
                //}
            });


            metabox_media.on('open', function () {

                // Grab our attachment selection and construct a JSON representation of the model.
                var selection = metabox_media.state().get('selection');
                attachment = wp.media.attachment(value);
                attachment.fetch();
                console.log(attachment);
                selection.add(attachment ? [attachment] : []);
            });

            metabox_media.on('select', function () {

                // Grab our attachment selection and construct a JSON representation of the model.
                var media_attachment = metabox_media.state().get('selection').first().toJSON();

                // Send the attachment URL to our custom input field via jQuery.
                opener.parent().find('input.meta_box_upload_media').val(media_attachment.id);
                opener.parent().find('.meta_box_file').addClass('checked');
            });
            metabox_media.open(id);
        }
    });


    // function to create an array of input values
    function ids(inputs) {
        var a = [];
        for (var i = 0; i < inputs.length; i++) {
            a.push(inputs[i].val);
        }
        //$("span").text(a.join(" "));
    }

    // repeatable fields
    $('.meta_box_repeatable_add').on('click', function () {
        // clone
        var row = $(this).closest('.meta_box_repeatable').find('tbody tr:last-child');
        var clone = row.clone();
        clone.find('select.chosen').removeAttr('style', '').removeAttr('id', '').removeClass('chzn-done').data('chosen', null).next().remove();
        clone.find('input.regular-text, textarea, select').val('');
        clone.find('input[type=checkbox], input[type=radio]').attr('checked', false);
        row.after(clone);
        // increment name and id
        clone.find('input, textarea, select')
            .attr('name', function (index, name) {
                return name.replace(/(\d+)/, function (fullMatch, n) {
                    return Number(n) + 1;
                });
            });
        var arr = [];
        $('input.repeatable_id:text').each(function () {
            arr.push($(this).val());
        });
        clone.find('input.repeatable_id')
            .val(Number(Math.max.apply(Math, arr)) + 1);
        /*if (!!$.prototype.chosen) {
         clone.find('select.chosen')
         .chosen({allow_single_deselect : true});
         }*/
        //
        return false;
    });

    $('body').on('click', '.meta_box_repeatable_remove', function () {
        $(this).closest('tr').remove();
        return false;
    });

    $('.meta_box_repeatable tbody').sortable({
        opacity : 0.6,
        revert  : true,
        cursor  : 'move',
        handle  : '.hndle',
        update  : function (event, ui) {
            var result = $(this).sortable('toArray');

            $(this).find('>*').each(function (i, el) {

                $(this).find('input, select, textarea')
                    .attr('name', function (index, name) {
                        return name.replace(/(\d+)/, function () {
                            return Number(i);
                        });
                    });
            })

            //var thisID = $(this).attr('id');
            //$('.store-' + thisID).val(result)
        }
    });

    // post_drop_sort
    $('.sort_list').sortable({
        connectWith : '.sort_list',
        opacity     : 0.6,
        revert      : true,
        cursor      : 'move',
        cancel      : '.post_drop_sort_area_name',
        items       : 'li:not(.post_drop_sort_area_name)',
        update      : function (event, ui) {
            var result = $(this).sortable('toArray');

            var thisID = $(this).attr('id');
            $('.store-' + thisID).val(result)
        }
    });

    $('.sort_list').disableSelection();

    // turn select boxes into something magical
    /*
     if (!!$.prototype.chosen)
     $('.chosen').chosen({allow_single_deselect : true});

     */

    /**
     * Gallery
     */


    var file_frame;

    $('body').on('click', '.gallery-metabox a.gallery-add', function (e) {

        e.preventDefault();

        var $parent = $(this).closest('.gallery-metabox');

        if (file_frame) file_frame.close();

        file_frame = wp.media.frames.file_frame = wp.media({

            editing : true,

            title    : $(this).data('uploader-title'),
            button   : {
                text : $(this).data('uploader-button-text')
            },
            multiple : 'add'
        });


        file_frame.on('select', function () {
            var listIndex = $($parent).find('.gallery-metabox-list li:last').index(),
                selection = file_frame.state().get('selection');

            selection.map(function (attachment, i) {
                attachment = attachment.toJSON(),
                    index = listIndex + (i + 1);

                var $gallery_name = $($parent).attr('data-name');
                var $src;

                if (typeof(attachment.sizes.thumbnail) == 'undefined') {
                    $src = attachment.sizes.full.url;
                }
                else {
                    $src = attachment.sizes.thumbnail.url;
                }

                $($parent).find('.gallery-metabox-list').append('<li class="gallery-metabox-list-li"><input type="hidden" name="' + $gallery_name + '[' + index + ']" value="' + attachment.id + '"><img class="image-preview" src="' + $src + '"><a class="change-image button button-small" href="javascript:;" data-uploader-title="Change image" data-uploader-button-text="Change image">Change image</a><br><small><a class="remove-image button button-small button-danger" href="javascript:;">Remove image</a></small></li>');
            });
        });

        makeSortable();

        file_frame.open();

    });

    $('body').on('click', '.gallery-metabox a.change-image', function (e) {

        e.preventDefault();

        var that = $(this);

        if (file_frame) file_frame.close();

        file_frame = wp.media.frames.file_frame = wp.media({
            title    : $(this).data('uploader-title'),
            button   : {
                text : $(this).data('uploader-button-text'),
            },
            multiple : false
        });

        // When selected items
        file_frame.on('select', function () {
            var attachment = file_frame.state().get('selection').first().toJSON();
            var $src;

            that.parent().find('input:hidden').val(attachment.id);

            if (typeof(attachment.sizes.thumbnail) == 'undefined') {
                $src = attachment.sizes.full.url;
            }
            else {
                $src = attachment.sizes.thumbnail.url;
            }

            that.parent().find('img.image-preview').attr('src', $src);
        });


        // When open select selected
        file_frame.on('open', function () {

            // Grab our attachment selection and construct a JSON representation of the model.
            var selection = file_frame.state().get('selection');
            var current = that.parent().find('input:hidden').val();
            var attachment = wp.media.attachment(current);
            attachment.fetch();
            selection.add(attachment ? [attachment] : []);
        });

        file_frame.open();

    });

    function resetIndex() {
        $('.gallery-metabox-list li').each(function (i) {

            var $parent = $(this).closest('.gallery-metabox');

            $($parent).find('.gallery-metabox-list li').each(function (index) {
                var $gallery_name = $($parent).attr('data-name');
                $(this).find('input:hidden').attr('name', $gallery_name + '[' + index + ']');
            });


        });
    }

    function makeSortable() {
        $('.gallery-metabox-list').sortable({
            opacity : 0.6,
            stop    : function () {
                resetIndex();
            }
        });
    }

    $('body').on('click', '.gallery-metabox a.remove-image', function (e) {
        e.preventDefault();

        var $parent = $(this).closest('.gallery-metabox');

        $(this).parents('li').animate({opacity : 0}, 200, function () {
            $(this).remove();
            resetIndex();
        });
    });

    makeSortable();

});