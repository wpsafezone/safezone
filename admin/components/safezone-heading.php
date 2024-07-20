<?php

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

?>

<div class="heading">
    <div class="heading__top">
        <span class="heading__title"><?php echo esc_html($this->plugin_name); ?></span>
        <span class="heading__version">v<?php echo esc_html($this->version); ?></span>
    </div>
    <div class="heading__protection heading__protection--<?php echo $this->protection_status['type']; ?>">
        <svg class="icon">
            <use xlink:href="<?php echo esc_url(SAFEZONE_PLUGIN_URL . '/admin/images/icons.svg#shield-alt'); ?>"></use>
        </svg>
        <span><?php echo $this->protection_status['message']; ?></span>
    </div>
</div>
