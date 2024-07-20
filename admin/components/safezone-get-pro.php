<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

?>


<div class="get-pro">
    <div class="get-pro__content">
        <div class="get-pro__heading">
            <div class="get-pro__heading-icon">
                <img draggable="false"
                     src="<?php echo esc_url( SAFEZONE_PLUGIN_URL . 'admin/images/safezone.svg' ); ?>"
                     alt="shield plus">
            </div>
            <div class="get-pro__heading-content">
                <div class="get-pro__heading-title">Go Safe Zone Pro</div>
                <div class="get-pro__heading-text">Upgrade your plugin to unlock all features.</div>
            </div>
        </div>
        <div class="get-pro__list">

            <div class="get-pro__list-item">
                          <span>
                            <svg class="icon">
                              <use xlink:href="<?php echo esc_url( SAFEZONE_PLUGIN_URL . 'admin/images/icons.svg#saved' ); ?>"></use>
                            </svg>
                          </span>
                DDoS Protection
            </div>

            <div class="get-pro__list-item">
                          <span>
                            <svg class="icon">
                              <use xlink:href="<?php echo esc_url( SAFEZONE_PLUGIN_URL . 'admin/images/icons.svg#saved' ); ?>"></use>
                            </svg>
                          </span>
                Enhanced Monitoring
            </div>

            <div class="get-pro__list-item">
                          <span>
                            <svg class="icon">
                              <use xlink:href="<?php echo esc_url( SAFEZONE_PLUGIN_URL . 'admin/images/icons.svg#saved' ); ?>"></use>
                            </svg>
                          </span>
                Advanced Firewall
            </div>

        </div>
    </div>
    <div class="get-pro__license">
        <button type="button" class="btn btn-blue-40 btn-icon paymentModal">
            Upgrade to Pro
            <svg class="icon">
                <use xlink:href="<?php echo esc_url( SAFEZONE_PLUGIN_URL . 'admin/images/icons.svg#arrow-right-alt2' ); ?>"></use>
            </svg>
        </button>
        <div class="get-pro__license-text">One-year license for
            <b>$<?php echo esc_html( $this->packages[0]['price'] ); ?></b></div>
    </div>
    <div class="get-pro__effect">
        <img draggable="false"
             src="<?php echo esc_url( SAFEZONE_PLUGIN_URL . 'admin/images/circle-effect.svg' ); ?>"
             alt="effect">
    </div>
</div>