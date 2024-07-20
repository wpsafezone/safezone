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

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<div class="app">
	<?php include_once SAFEZONE_PLUGIN_PATH . 'admin/components/safezone-heading.php'; ?>
    <div class="app__body">
        <div class="app__body-main" id="board">
            <div class="foundation">
                <div class="foundation-panel">
                    <div class="foundation-panel__heading">
                        <div class="foundation-panel__avatar">
                            <svg class="icon">
                                <use xlink:href="<?php echo esc_url( SAFEZONE_PLUGIN_URL . 'admin/images/icons.svg#email-close' ); ?>"></use>
                            </svg>
                        </div>
                        <div class="foundation-panel__content">
                            <div class="foundation-panel__content-title">
                                Anti-Spam Engine
                            </div>
                            <div class="foundation-panel__content-text">Your website is protected live</div>
                        </div>
                    </div>
                    <div class="foundation-panel__actions">
                        <div class="foundation-panel__actions-switch">
                            <label class="form-check form-switch">
                                <input class="form-check-input update_option" type="checkbox" data-key="sz_anti_spam"
                                       role="switch" <?php echo get_option( 'sz_anti_spam' ) === "0" ? '' : 'checked="checked"' ?>>
                                <span class="form-check-label">Anti-Spam Protection</span>
                            </label>
                            <a href="<?php echo esc_url( admin_url( 'admin.php?page=safezone-settings&tab=anti-spam' ) ); ?>"
                               class="btn btn-white btn-icon">
                                Settings
                                <svg class="icon">
                                    <use xlink:href="<?php echo esc_url( SAFEZONE_PLUGIN_URL . 'admin/images/icons.svg#admin-tools' ); ?>"></use>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="foundation-cards">
                    <div class="sz-card sz-card-info">
                        <div class="sz-card-info__main">
                            <div class="sz-card-info__value">
                                <span class="sz-card-info__value-text" id="blocked_spam_count">0</span>
                                <span>
                                  <svg class="icon">
                                    <use xlink:href="<?php echo esc_url( SAFEZONE_PLUGIN_URL . 'admin/images/icons.svg#welcome-comments' ); ?>"></use>
                                  </svg>
                                </span>
                            </div>
                            <div class="sz-card-info__content">
                                <div class="sz-card-info__content-title">Blocked Spams</div>
                                <div class="sz-card-info__content-text">Last update: <span id="blocked_spam_last_date">Never</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sz-card sz-card-info">
                        <div class="sz-card-info__main">
                            <div class="sz-card-info__value">
                                <span class="sz-card-info__value-text" id="blocked_ip_count">0</span>
                                <span>
                                  <svg class="icon">
                                    <use xlink:href="<?php echo esc_url( SAFEZONE_PLUGIN_URL . 'admin/images/icons.svg#lock' ); ?>"></use>
                                  </svg>
                                </span>
                            </div>
                            <div class="sz-card-info__content">
                                <div class="sz-card-info__content-title">Blocked IPs</div>
                                <div class="sz-card-info__content-text">Last update: <span id="blocked_ip_last_date">Never</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="foundation-card">
                        <div class="foundation-card__main">
                            <div class="setting_status"></div>
                            <div class="foundation-card__content">
                                <div class="foundation-card__content-title">Specific Settings</div>
                                <a href="<?php echo esc_url( admin_url( 'admin.php?page=safezone-settings&tab=anti-spam' ) ); ?>"
                                   class="foundation-card__content-link shadow-none">
                                    View All
                                    <svg class="icon">
                                        <use xlink:href="<?php echo esc_url( SAFEZONE_PLUGIN_URL . 'admin/images/icons.svg#arrow-right-alt2' ); ?>"></use>
                                    </svg>
                                </a>
                            </div>
                        </div>
                        <div class="foundation-card__list live_settings_board" data-type="anti-spam"></div>
                    </div>
                </div>
                <div class="foundation-table">
                    <div class="table">
                        <div class="table-container">
                            <table id="anti_spamTable" class="table-main">
                                <thead class="table-head">
                                <tr>
                                    <th>IP Address</th>
                                    <th>Activity</th>
                                    <th>Country</th>
                                    <th>Type</th>
                                    <th>User Agent</th>
                                    <th>Blocked Date</th>
                                </tr>
                                </thead>
                                <tbody class="table-body"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="app__body-sidebar">
            <div class="sidebar">
				<?php include_once SAFEZONE_PLUGIN_PATH . 'admin/components/safezone-sidebar-scan.php'; ?>
				<?php include_once SAFEZONE_PLUGIN_PATH . 'admin/components/safezone-sidebar-upgrade.php'; ?>
				<?php include_once SAFEZONE_PLUGIN_PATH . 'admin/components/safezone-sidebar-documentation.php'; ?>
				<?php include_once SAFEZONE_PLUGIN_PATH . 'admin/components/safezone-sidebar-guidance.php'; ?>
            </div>
        </div>
    </div>
</div>