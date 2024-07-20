<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://brunos.digital
 * @since      1.0.0
 *
 * @package    Safezone
 * @subpackage Safezone/admin/partials
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<div class="app">
	<?php include_once SAFEZONE_PLUGIN_PATH . '/admin/components/safezone-heading.php'; ?>
    <div class="app__body">
        <div class="page">
            <div class="tab-menu">
				<?php include_once SAFEZONE_PLUGIN_PATH . '/admin/components/safezone-tab-menu.php'; ?>
            </div>
	        <?php if(!$this->is_pro): ?>
                <?php include_once SAFEZONE_PLUGIN_PATH . '/admin/components/safezone-get-pro.php'; ?>
                <div class="license-panel">
                    <div class="license-panel__heading">
                        <div class="license-panel__heading-icon">

                            <svg class="icon">
                                <use xlink:href="<?php echo esc_url( SAFEZONE_PLUGIN_URL . 'admin/images/icons.svg#admin-network' ); ?>"></use>
                            </svg>

                        </div>
                        <div class="license-panel__heading-content">
                            <div class="license-panel__heading-title">License Key</div>
                            <div class="license-panel__heading-text">If you have the key you can activate your Safe Zone Pro
                                license manually.
                            </div>
                        </div>
                    </div>

                    <div class="license-panel__form">
                        <div class="license-panel__form-title">License Key</div>
                        <div class="license-panel__form-entry">
                            <div class="license-panel__form-input">
                                <label class="input-group">
                                    <span class="input-group-icon">
                                      <svg class="icon">
                                        <use xlink:href="<?php echo esc_url( SAFEZONE_PLUGIN_URL . 'admin/images/icons.svg#admin-network' ); ?>"></use>
                                      </svg>
                                    </span>
                                    <input type="text" class="form-control license-input" value="<?php echo esc_attr(get_option('sz_license'));?>">
                                </label>
                                <div class="license-panel__form-help">
                                    <svg class="icon">
                                        <use xlink:href="<?php echo esc_url( SAFEZONE_PLUGIN_URL . 'admin/images/icons.svg#info' ); ?>"></use>
                                    </svg>
                                    Enter plugin’s license key to proceed.
                                </div>
                            </div>
                            <button class="btn btn-blue-40 add-license" type="button">Active License</button>
                        </div>
                    </div>

                </div>
                <?php else: ?>
                <div class="license-panel">
                    <div class="license-panel__heading">
                        <div class="license-panel__heading-icon">
                            <svg class="icon">
                                <use xlink:href="<?php echo esc_url( SAFEZONE_PLUGIN_URL . 'admin/images/icons.svg#admin-network' ); ?>"></use>
                            </svg>
                        </div>
                        <div class="license-panel__heading-content">
                            <div class="license-panel__heading-title">License</div>
                            <div class="license-panel__heading-text">View and manage your license details.</div>
                        </div>
                    </div>
                    <div class="license-panel__details">
                        <div class="license-panel__detail">
                            <div class="license-panel__detail-title">License Key</div>
                            <div class="license-panel__detail-value"><mark><?php echo esc_html($this->license_info['license_key']);?></mark></div>
                        </div>
                        <div class="license-panel__detail">
                            <div class="license-panel__detail-title">Product</div>
                            <div class="license-panel__detail-value"><?php echo esc_html($this->license_info['name']);?></div>
                        </div>
                        <div class="license-panel__detail">
                            <div class="license-panel__detail-title">Status</div>
                            <div class="license-panel__detail-value"><span class="active">Active</span></div>
                        </div>
                        <div class="license-panel__detail">
                            <div class="license-panel__detail-title">Start Date</div>
                            <div class="license-panel__detail-value"><?php echo esc_html($this->license_info['created_at']);?></div>
                        </div>
                        <div class="license-panel__detail">
                            <div class="license-panel__detail-title">Expiry Date</div>
                            <div class="license-panel__detail-value"><?php echo esc_html($this->license_info['expires_at']);?></div>
                        </div>
                        <div class="license-panel__detail">
                            <div class="license-panel__detail-title">Installs</div>
                            <div class="license-panel__detail-value">1 out of 1</div>
                        </div>
                    </div>
                    <?php if(!$this->license_info['cancel_at_period_end']) :?>
                    <button class="btn btn-gray-0 mt-3 ms-auto cancelSubscription">Cancel License</button>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>

</div>