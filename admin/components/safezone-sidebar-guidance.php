<?php

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

?>

<div class="sz-card">
    <div class="sz-card-guidance">
        <div class="sz-card-guidance__content">
            <div class="sz-card-guidance__content-title">Do you have a question?</div>
            <div class="sz-card-guidance__content-text">You can always contact our support line</div>
        </div>
        <div class="sz-card-guidance__actions">
            <a target="_blank" href="https://wpsafezone.com/faq" class="btn btn-gray-2 text-nowrap">See F.A.Q</a>
            <a target="_blank" href="https://support.wpsafezone.com" class="btn btn-white btn-icon w-100">
                Get Support
                <svg class="icon">
                    <use xlink:href="<?php echo esc_url(SAFEZONE_PLUGIN_URL . '/admin/images/icons.svg#arrow-right-alt'); ?>"></use>
                </svg>
            </a>
        </div>
    </div>
</div>