<?php

/**
 * View for Users page custom bulk actions
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

?>

<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery('<option>').val('rpwcm_grant_access').text('<?php _e('Grant access', 'woocommerce-membership'); ?>').appendTo("select[name='action']");
    });
</script>
