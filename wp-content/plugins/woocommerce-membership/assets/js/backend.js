/**
 * WooCommerce Membership Plugin Backend Scripts
 */
jQuery(document).ready(function() {

    /**
     * Handle expiration date change
     */
    jQuery('.rpwcm_membership_plan_expiration_date_change_link').click(function(e) {

        // Get nearest date input field
        var date_field = jQuery(this).closest('td').find('input[name=rpwcm_date]').first();

        // Get current date
        var current_date_field = jQuery(this).closest('td').find('input[name=rpwcm_default_date]').first();
        var current_date = current_date_field.val();

        // Datepicker configuration
        var datepicker_config = {
            showButtonPanel:    true,
            currentText:        rpwcm_backend_vars.never_text,
            closeText:          rpwcm_backend_vars.close_text,
            dateFormat:         'yy-mm-dd',
        };

        // Set current date (if any)
        if (current_date != '') {
            datepicker_config.defaultDate = current_date;
        }

        // On select
        datepicker_config.onSelect = function(date) {
            rpwcm_expiration_date_changed(date, jQuery(this));
            current_date_field.val(date);
            date_field.datepicker('destroy');
        };

        // On close
        datepicker_config.onClose = function() {
            date_field.datepicker('destroy');
        }

        // Initialize datepicker
        date_field.datepicker(datepicker_config);
        date_field.datepicker('show');

        // Change purpose of the "current" button (using it for "never")
        jQuery('button.ui-datepicker-current').unbind('click').bind('click', function() {
            date_field.datepicker('hide');
            current_date_field.val('');
            date_field.val('');
            rpwcm_expiration_changed_to_never(date_field);
            date_field.datepicker('destroy');
        })

        e.preventDefault();
    });

    /**
     * Handle expiration date change
     */
    function rpwcm_expiration_date_changed(date, field)
    {
        var cell = field.closest('td');

        jQuery.post(
            ajaxurl,
            {
                'action':   'change_expiration_date',
                'user_id':  cell.find('input[name=rpwcm_user_id]').first().val(),
                'plan_key': cell.find('input[name=rpwcm_plan_key]').first().val(),
                'plan_id':  cell.find('input[name=rpwcm_plan_id]').first().val(),
                'date':     date
            },
            function(response) {
                var result = jQuery.parseJSON(response);

                // Update date in view
                if (typeof result.newdate !== 'undefined') {
                    cell.find('.rpwcm_expiration_date').removeClass('rpwcm_nothing_to_display').html(result.newdate);
                }
            }
        );
    }

    /**
     * Handle expiration date change to never
     */
    function rpwcm_expiration_changed_to_never(field)
    {
        var cell = field.closest('td');

        jQuery.post(
            ajaxurl,
            {
                'action':   'change_expiration_never',
                'user_id':  cell.find('input[name=rpwcm_user_id]').first().val(),
                'plan_key': cell.find('input[name=rpwcm_plan_key]').first().val(),
                'plan_id':  cell.find('input[name=rpwcm_plan_id]').first().val(),
            },
            function(response) {
                var result = jQuery.parseJSON(response);

                // Update date in view
                if (typeof result.newdate !== 'undefined') {
                    cell.find('.rpwcm_expiration_date').addClass('rpwcm_nothing_to_display').html(result.newdate);
                }
            }
        );
    }

    /**
     * Handle search submit
     */
    function rpwcm_member_search()
    {
        // Create the new url for search
        var admin_url = jQuery('#rpwcm_admin_url').val();
        var search_query = jQuery('#rpwcm_member_search_field').val();
        var full_url = admin_url + jQuery.trim(search_query);

        // Redirect to this url
        location.replace(full_url);
    }

    // On button click
    jQuery('#rpwcm_member_search_button').click(function(e) {
        rpwcm_member_search();
    });

    // On 'enter' key press
    jQuery('#rpwcm_member_search_field').each(function() {
        jQuery(this).keydown(function(e) {
            if (e.keyCode === 13) {
                e.preventDefault();
                rpwcm_member_search();
            }
        });
    });

    /**
     * URL RESTRICTION
     * Set up URL Restriction interface
     */
    jQuery('#rpwcm_url_restriction').each(function() {

        // Display existing rules
        if (typeof rpwcm_backend_vars.block_urls !== 'undefined' && rpwcm_backend_vars.block_urls.length > 0) {
            for (var i = 0; i < rpwcm_backend_vars.block_urls.length; i++) {
                url_restriction_add_new_rule(rpwcm_backend_vars.block_urls[i]);
            }
        }

        // Display no rules notification
        else {
            jQuery('#rpwcm_url_restriction').html(jQuery('#rpwcm_url_restriction_template_no_rules').html());
        }

        // Set up add new rule button
        jQuery('#rpwcm_url_restriction_add_rule button').click(function() {
            url_restriction_add_new_rule(false);
        });

    });

    /**
     * URL RESTRICTION
     * Add new rule
     */
    function url_restriction_add_new_rule(config)
    {
        // Fix wrapper
        url_restriction_toggle_no_rules(true);

        // Add rule html
        jQuery('#rpwcm_url_restriction').find('#rpwcm_url_restriction_rule_wrapper').append(jQuery('#rpwcm_url_restriction_template_rule').html());

        // Fix rule elements
        jQuery('#rpwcm_url_restriction').find('#rpwcm_url_restriction_rule_wrapper').find('.rpwcm_url_restriction_rule').last().each(function() {

            // URL
            var url = config !== false ? config['url'] : '';
            jQuery(this).find('.rpwcm_url_restriction_field_url').val(url);

            // Method
            if (config !== false) {
                jQuery(this).find('.rpwcm_url_restriction_field_method').val(config['method']);
            }

            // Display plans if needed
            url_restriction_method_changed(jQuery(this).find('.rpwcm_url_restriction_field_method'));

            // Method changed
            jQuery(this).find('.rpwcm_url_restriction_field_method').change(function() {
                url_restriction_method_changed(jQuery(this));
            });

            // Plans
            if (config !== false) {
                for (var i = 0; i < config['plans'].length; i++) {
                    jQuery(this).find('.rpwcm_url_restriction_field_plans option[value=' + config['plans'][i] + ']').prop('selected', true);
                }
            }

            // Make plans field select2
            RP_Select2.call(jQuery(this).find('.rpwcm_url_restriction_field_plans'), {
                placeholder: rpwcm_vars.title_plans_placeholder,
                width: '100%'
            });

            // Remove button
            jQuery(this).find('.rpwcm_url_restriction_remove_handle').click(function() {
                jQuery(this).closest('.rpwcm_url_restriction_rule').remove();
                url_restriction_fix_field_ids();
                url_restriction_toggle_no_rules(false);
            });
        });

        // Fix field identifiers
        url_restriction_fix_field_ids();
    }

    /**
     * URL RESTRICTION
     * Method changed
     */
    function url_restriction_method_changed(field)
    {
        // Plans need to be displayed
        if (field.val() === 'members_with_plans' || field.val() === 'users_without_plans') {
            field.closest('.rpwcm_url_restriction_rule').find('.rpwcm_url_restriction_field_plans_wrapper').css('display', 'inline-block');
            field.closest('.rpwcm_url_restriction_rule').find('.rpwcm_url_restriction_field_method_wrapper').css('width', '33%').find('.rpwcm_url_restriction_field_method').css('width', '98%');
        }

        // Plans need to be hidden
        else {
            field.closest('.rpwcm_url_restriction_rule').find('.rpwcm_url_restriction_field_plans_wrapper').css('display', 'none');
            field.closest('.rpwcm_url_restriction_rule').find('.rpwcm_url_restriction_field_method_wrapper').css('width', '66%').find('.rpwcm_url_restriction_field_method').css('width', '100%');
        }
    }

    /**
     * URL RESTRICTION
     * Toggle no rules notification and add rule wrapper
     */
    function url_restriction_toggle_no_rules(add)
    {
        if (add === false && jQuery('#rpwcm_url_restriction #rpwcm_url_restriction_rule_wrapper').children().length === 0) {
            jQuery('#rpwcm_url_restriction').html(jQuery('#rpwcm_url_restriction_template_no_rules').html());
        }
        else if (jQuery('#rpwcm_url_restriction').find('#rpwcm_url_restriction_rule_wrapper').length == 0) {
            jQuery('#rpwcm_url_restriction').html(jQuery('#rpwcm_url_restriction_template_rule_wrapper').html());
        }
    }

    /**
     * URL RESTRICTION
     * Fix field ids
     */
    function url_restriction_fix_field_ids()
    {
        // Track rules
        var i = 0;

        // Iterate over rules
        jQuery('#rpwcm_url_restriction').find('#rpwcm_url_restriction_rule_wrapper').find('.rpwcm_url_restriction_rule').each(function() {

            // Iterate over all field elements of this rule
            jQuery(this).find('input, select').each(function() {

                // Attribute id
                if (typeof jQuery(this).prop('id') !== 'undefined') {
                    var new_value = jQuery(this).prop('id').replace(/(\{i\}|\d+)?$/, i);
                    jQuery(this).prop('id', new_value);
                }

                // Attribute name
                if (typeof jQuery(this).prop('name') !== 'undefined') {
                    var new_value = jQuery(this).prop('name').replace(/^rpwcm_options\[rpwcm_block_urls\]\[(\{i\}|\d+)\]?/, 'rpwcm_options[rpwcm_block_urls][' + i + ']');
                    jQuery(this).prop('name', new_value);
                }
            });

            // Increment rule id
            i++;
        });
    }

    /**
     * BULK GRANT ACCESS
     * Expiration date
     */
    jQuery('#rpwcm_bulk_grant_access_expiration').datepicker({
        dateFormat: 'yy-mm-dd',
    });

});
