/*
 Field Color (color)
 */

/*global jQuery, document, redux_change, redux*/

(function ($) {
    'use strict';

    redux.field_objects = redux.field_objects || {};
    redux.field_objects.hippo_preset_color = redux.field_objects.hippo_preset_color || {};

    $(document).ready(
        function () {
            $('#info-preset_change_warning').slideUp('slow');
        }
    );

    redux.field_objects.hippo_preset_color.init = function (selector) {

        if (!selector) {
            selector = $(document).find('.redux-container-hippo_preset_color');
        }

        $(selector).each(
            function () {

                var el = $(this);
                var parent = el;

                if (!el.hasClass('redux-field-container')) {
                    parent = el.parents('.redux-field-container:first');
                }

                if (parent.hasClass('redux-field-init')) {
                    parent.removeClass('redux-field-init');
                }
                else {
                    return;
                }

                el.find('.hippo-redux-color-init').wpColorPicker({
                    change : function (u) {
                        redux_change($(this));
                        el.find('#' + u.target.getAttribute('data-id') + '-transparency').removeAttr('checked');
                        $('#info-preset_change_warning').slideDown('slow');
                    },
                    clear  : function () {
                        redux_change($(this).parent().find('.hippo-redux-color-init'));
                        $('#info-preset_change_warning').slideDown('slow');
                    }
                });

                el.find('.hippo-redux-color').on(
                    'focus', function () {
                        $(this).data('oldcolor', $(this).val());
                    }
                );

                el.find('.hippo-redux-color').on(
                    'keyup', function () {
                        var value = $(this).val();
                        var color = colorValidate(this);
                        var id = '#' + $(this).attr('id');

                        if (value === "transparent") {
                            $(this).parent().parent().find('.wp-color-result').css(
                                'background-color', 'transparent'
                            );

                            el.find(id + '-transparency').attr('checked', 'checked');
                        }
                        else {
                            el.find(id + '-transparency').removeAttr('checked');

                            if (color && color !== $(this).val()) {
                                $(this).val(color);
                            }
                        }
                    }
                );

                // Replace and validate field on blur
                el.find('.hippo-redux-color').on(
                    'blur', function () {
                        var value = $(this).val();
                        var id = '#' + $(this).attr('id');

                        if (value === "transparent") {
                            $(this).parent().parent().find('.wp-color-result').css(
                                'background-color', 'transparent'
                            );

                            el.find(id + '-transparency').attr('checked', 'checked');
                        }
                        else {
                            if (colorValidate(this) === value) {
                                if (value.indexOf("#") !== 0) {
                                    $(this).val($(this).data('oldcolor'));
                                }
                            }

                            el.find(id + '-transparency').removeAttr('checked');
                        }
                    }
                );

                // Store the old valid color on keydown
                el.find('.hippo-redux-color').on(
                    'keydown', function () {
                        $(this).data('oldkeypress', $(this).val());
                    }
                );

                // When transparency checkbox is clicked
                el.find('.color-transparency').on(
                    'click', function () {
                        if ($(this).is(":checked")) {

                            el.find('.hippo-redux-saved-color').val($('#' + $(this).data('id')).val());
                            el.find('#' + $(this).data('id')).val('transparent');
                            el.find('#' + $(this).data('id')).parent().parent().find('.wp-color-result').css(
                                'background-color', 'transparent'
                            );
                        }
                        else {
                            if (el.find('#' + $(this).data('id')).val() === 'transparent') {
                                var prevColor = $('.redux-saved-color').val();

                                if (prevColor === '') {
                                    prevColor = $('#' + $(this).data('id')).data('default-color');
                                }

                                el.find('#' + $(this).data('id')).parent().parent().find('.wp-color-result').css(
                                    'background-color', prevColor
                                );

                                el.find('#' + $(this).data('id')).val(prevColor);
                            }
                        }

                        $('#info-preset_change_warning').slideDown('slow');
                    }
                );
            }
        );
    };
})(jQuery);