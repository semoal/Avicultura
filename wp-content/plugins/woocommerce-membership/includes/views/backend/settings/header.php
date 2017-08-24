<?php

/**
 * View for Settings page header (tabs)
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

?>

<h2 class="rpwcm_tabs_container nav-tab-wrapper">
    <?php foreach ($this->settings as $tab_key => $tab): ?>
        <a class="nav-tab <?php echo ($tab_key == $current_tab ? 'nav-tab-active' : ''); ?>" href="?post_type=membership_plan&page=rpwcm_settings&tab=<?php echo $tab_key; ?>"><?php echo (!empty($tab['icon']) ? $tab['icon'] . '&nbsp;' : '') . $tab['title']; ?></a>
    <?php endforeach; ?>
</h2>
