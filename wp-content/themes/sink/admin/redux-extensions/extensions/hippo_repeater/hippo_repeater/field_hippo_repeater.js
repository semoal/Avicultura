/* global confirm, redux, redux_change */

/*global redux_change, redux*/

(function ($) {
    "use strict";

    redux.field_objects = redux.field_objects || {};
    redux.field_objects.hippo_repeater = redux.field_objects.hippo_repeater || {};

    $(document).ready(
        function () {
            redux.field_objects.hippo_repeater.init();
        }
    );


    redux.field_objects.hippo_repeater.getTemplate = function (template_data, dataObject) {

        _.templateSettings = {
            interpolate : /\{\{(.+?)\}\}/g
        };
        var template = _.template(template_data);

        return template(dataObject);
    };

    redux.field_objects.hippo_repeater.init = function (selector) {



// hippo-repeatable-new-field
        if (!selector) {
            selector = $(document).find('table.hippo-repeatable-fields-table');
        }

        $(selector).each(
            function () {
                var el = $(this);
                var template = $(this).next().html();
                var objects_need_re_init = $.parseJSON($(this).attr('data-fields'));


                $(el).on('click', '.hippo-repeatable-new-field', function (event) {

                    event.preventDefault();
                    event.stopPropagation();


                    var $total = $(this).closest('tbody').find('.hippo-repeatable-fields').length;
                    var $data = redux.field_objects.hippo_repeater.getTemplate(template, {'index' : $total});


                    $(document.body).trigger('before-hippo-repeatable-field-add', [$(this)]);


                    // Add Field
                    $(this).closest('.hippo-repeatable-fields-table-add-new').before($($data));


                    $(document.body).trigger('after-hippo-repeatable-field-add', [$(this)]);

                    //_.delay(function () {

                    _.each(objects_need_re_init, function (object) {

                        if (typeof(redux.field_objects[object]) != 'undefined') {
                            redux.field_objects[object].init();
                        }

                    });

                    // }, 100);

                    redux_change($(el));

                });


                $(el).on('click', '.hippo-repeatable-delete-field', function (event) {

                    event.preventDefault();
                    event.stopPropagation();

                    $(document.body).trigger('before-hippo-repeatable-field-remove', [$(this)]);

                    $(this).closest('.hippo-repeatable-fields').remove();


                    $(el).find('.hippo-repeatable-fields').each(function ($i) {

                        $(this).find('input, select, textarea, fieldset, span, a, label, img').each(function () {

                            var $old_id = $(this).attr('id');
                            if (typeof($old_id) != 'undefined') {
                                $(this).attr('id', $old_id.replace(/_\d+/g, '_' + $i));
                            }

                            var $old_data_id = $(this).attr('data-id');
                            if (typeof($old_data_id) != 'undefined') {
                                $(this).attr('data-id', $old_data_id.replace(/_\d+/g, '_' + $i));
                            }

                            var $old_for = $(this).attr('for');
                            if (typeof($old_for) != 'undefined') {
                                $(this).attr('for', $old_for.replace(/_\d+/g, '_' + $i));
                            }

                            var $old_rel = $(this).attr('rel');
                            if (typeof($old_rel) != 'undefined') {
                                $(this).attr('rel', $old_rel.replace(/_\d+/g, '_' + $i));
                            }

                            var $old_name = $(this).attr('name');
                            if (typeof($old_name) != 'undefined') {
                                $(this).attr('name', $old_name.replace(/\[\d+\]/g, '['+$i+']'));
                            }

                        });

                    });


                    $(document.body).trigger('before-hippo-repeatable-field-remove', [$(this)]);

                    redux_change($(el));

                });


                // Used to display a full image preview of a tile/pattern
                el.find('.tiles').qtip(
                    {
                        content  : {
                            text : function () {
                                return "<img src='" + $(this).attr('rel') + "' style='max-width:150px;' alt='' />";
                            }
                        },
                        style    : 'qtip-tipsy',
                        position : {
                            my : 'top center', // Position my top left...
                            at : 'bottom center' // at the bottom right of...
                        }
                    }
                );
            }
        );

    };
})(jQuery);