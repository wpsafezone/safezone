<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<div class="sz-card">
    <div class="sz-card-scan">
        <div class="sz-card-scan__main">
            <div class="sz-card-scan__icon">

                <svg class="icon">
                    <use xlink:href="<?php echo esc_url( SAFEZONE_PLUGIN_URL . 'admin/images/icons.svg#shield-alt' ); ?>"></use>
                </svg>

            </div>
            <div class="sz-card-scan__content">
                <div class="sz-card-scan__content-title">Everything looks great!</div>
                <div class="sz-card-scan__content-text">Everything was perfect at your last scan.</div>
            </div>
            <div class="sz-card-scan__actions">
                <a href="<?php echo esc_url(admin_url( 'admin.php?page=safezone-malware' )); ?>"
                   class="btn btn-blue-40 btn-icon">
                    Scan Now

                    <svg class="icon">
                        <use xlink:href="<?php echo esc_url( SAFEZONE_PLUGIN_URL . 'admin/images/icons.svg#loading' ); ?>"></use>
                    </svg>

                </a>
                <span>Last scan: <b>08:52 AM</b></span>
            </div>
        </div>
    </div>
</div>