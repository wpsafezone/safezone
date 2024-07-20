<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

?>


<?php if ( ! $this->is_pro ) : ?>

    <div class="sz-card">
        <div class="sz-card-upgrade">
            <div class="sz-card-upgrade__heading">
                <div class="sz-card-upgrade__title">
                    Secure Swiftly, Go <span>Safe Zone Pro</span>
                </div>
                <div class="sz-card-upgrade__icon">
                    <img draggable="false" src="<?php echo esc_url(SAFEZONE_PLUGIN_URL . 'admin/images/shield-plus.svg'); ?>"
                         alt="shield plus">
                </div>
            </div>
            <div class="sz-card-upgrade__list">
                <div class="sz-card-upgrade__feature">
                <span class="sz-card-upgrade__feature-icon">

                  <svg class="icon">
                    <use xlink:href="<?php echo esc_url(SAFEZONE_PLUGIN_URL . 'admin/images/icons.svg#saved'); ?>"></use>
                  </svg>

                </span>
                    <span class="sz-card-upgrade__feature-text">
                  DDoS Protection
                </span>
                </div>

                <div class="sz-card-upgrade__feature">
                <span class="sz-card-upgrade__feature-icon">
                  <svg class="icon">
                    <use xlink:href="<?php echo esc_url(SAFEZONE_PLUGIN_URL . 'admin/images/icons.svg#saved'); ?>"></use>
                  </svg>
                </span>
                    <span class="sz-card-upgrade__feature-text">
                  Enhanced Monitoring
                </span>
                </div>

                <div class="sz-card-upgrade__feature">
                <span class="sz-card-upgrade__feature-icon">

                  <svg class="icon">
                    <use xlink:href="<?php echo esc_url(SAFEZONE_PLUGIN_URL . 'admin/images/icons.svg#saved'); ?>"></use>
                  </svg>

                </span>
                    <span class="sz-card-upgrade__feature-text">
                  Advanced Firewall
                </span>
                </div>
            </div>
            <button type="button" class="btn btn-blue-40 paymentModal">Get Pro
                for <?php echo $this->packages ? esc_html($this->packages[0]['currency']) : ''; ?><?php echo $this->packages ? esc_html($this->packages[0]['price']) : ''; ?>
                / <?php echo $this->packages ? esc_html($this->packages[0]['billing_period']) : ''; ?></button>
        </div>
    </div>
<?php endif; ?>