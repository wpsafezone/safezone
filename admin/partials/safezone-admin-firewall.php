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

if (!defined('WPINC')) {
    die;
}
?>

<?php if (!$this->is_pro): ?>
    <div class="app">
        <?php include_once SAFEZONE_PLUGIN_PATH . 'admin/components/safezone-heading.php'; ?>
        <div class="app__body">
            <div class="app__body-main">
                <div class="foundation">
                    <div class="foundation-panel free">
                        <div class="foundation-panel__heading">
                            <div class="foundation-panel__avatar">
                                <svg class="icon">
                                    <use xlink:href="<?php echo esc_url(SAFEZONE_PLUGIN_URL . 'admin/images/icons.svg#shield'); ?>"></use>
                                </svg>
                            </div>
                            <div class="foundation-panel__content">
                                <div class="foundation-panel__content-title">Firewall
                                    <button type="button" class="foundation-panel__content-badge paymentModal">PRO
                                    </button>
                                </div>
                                <div class="foundation-panel__content-text">Upgrade to <b>Pro</b> to activate this
                                    feature
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php include_once SAFEZONE_PLUGIN_PATH . '/admin/components/safezone-get-pro.php'; ?>
                </div>
            </div>
            <div class="app__body-sidebar">
                <div class="sidebar">
                    <?php include_once SAFEZONE_PLUGIN_PATH . '/admin/components/safezone-sidebar-upgrade.php'; ?>
                    <?php include_once SAFEZONE_PLUGIN_PATH . '/admin/components/safezone-sidebar-documentation.php'; ?>
                    <?php include_once SAFEZONE_PLUGIN_PATH . '/admin/components/safezone-sidebar-guidance.php'; ?>
                </div>
            </div>
        </div>
    </div>

<?php else: ?>
    <div class="app">
        <?php include_once SAFEZONE_PLUGIN_PATH . 'admin/components/safezone-heading.php'; ?>
        <div class="app__body">
            <div class="app__body-main" id="board">
                <div class="foundation">
                    <div class="foundation-panel">
                        <div class="foundation-panel__heading">
                            <div class="foundation-panel__avatar">
                                <svg class="icon">
                                    <use xlink:href="<?php echo esc_url(SAFEZONE_PLUGIN_URL . 'admin/images/icons.svg#shield'); ?>"></use>
                                </svg>
                            </div>
                            <div class="foundation-panel__content">
                                <div class="foundation-panel__content-title">
                                    Firewall enabled
                                </div>
                                <div class="foundation-panel__content-text">Your website is protected live</div>
                            </div>
                        </div>

                        <div class="foundation-panel__actions">
                            <div class="foundation-panel__actions-switch">
                                <label class="form-check form-switch">
                                    <input class="form-check-input update_option" type="checkbox" data-key="sz_firewall"
                                           role="switch" <?php echo get_option('sz_firewall') === "0" ? '' : 'checked="checked"' ?>>
                                    <span class="form-check-label">Firewall</span>
                                </label>
                                <a href="<?php echo esc_url(admin_url('admin.php?page=safezone-settings&tab=firewall')); ?>"
                                   class="btn btn-white btn-icon">
                                    Settings
                                    <svg class="icon">
                                        <use xlink:href="<?php echo esc_url(SAFEZONE_PLUGIN_URL . 'admin/images/icons.svg#admin-tools'); ?>"></use>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="foundation-cards">
                        <div class="sz-card sz-card-info">
                            <div class="sz-card-info__main">
                                <div class="sz-card-info__value">
                                    <span class="sz-card-info__value-text" id="bad_bots_count">0</span>
                                    <span>
                                  <svg class="icon">
                                    <use xlink:href="<?php echo esc_url(SAFEZONE_PLUGIN_URL . 'admin/images/icons.svg#admin-users'); ?>"></use>
                                  </svg>
                                </span>
                                </div>
                                <div class="sz-card-info__content">
                                    <div class="sz-card-info__content-title">Bad Bots</div>
                                    <div class="sz-card-info__content-text">Last update: <span id="last_bad_bots">-
                                    </div>
                                </div>
                            </div>
                            <div class="sz-card-info__actions">
                                <select class="form-select form-select-sm counter_select" aria-label="info select"
                                        data-type="bad_bots">
                                    <option value="all" selected="selected">All</option>
                                    <option value="today">Today</option>
                                    <option value="week">Week</option>
                                    <option value="month">Month</option>
                                </select>
                            </div>
                        </div>
                        <div class="sz-card sz-card-info">
                            <div class="sz-card-info__main">
                                <div class="sz-card-info__value">
                                    <span class="sz-card-info__value-text" id="login_protection_count">0</span>
                                    <span>
                              <svg class="icon">
                                <use xlink:href="<?php echo esc_url(SAFEZONE_PLUGIN_URL . 'admin/images/icons.svg#admin-network'); ?>"></use>
                              </svg>
                            </span>
                                </div>
                                <div class="sz-card-info__content">
                                    <div class="sz-card-info__content-title">Login Protection</div>
                                    <div class="sz-card-info__content-text">Last update: <span
                                                id="last_login_protection">-
                                    </div>
                                </div>
                            </div>
                            <div class="sz-card-info__actions">
                                <select class="form-select form-select-sm counter_select" aria-label="info select"
                                        data-type="login_protection">
                                    <option value="all" selected="selected">All</option>
                                    <option value="today">Today</option>
                                    <option value="week">Week</option>
                                    <option value="month">Month</option>
                                </select>
                            </div>
                        </div>
                        <div class="foundation-card">
                            <div class="foundation-card__main">
                                <div class="setting_status"></div>
                                <div class="foundation-card__content">
                                    <div class="foundation-card__content-title">Specific Settings</div>
                                    <a href="<?php echo esc_url(admin_url('admin.php?page=safezone-settings&tab=firewall')); ?>"
                                       class="foundation-card__content-link shadow-none">
                                        View All
                                        <svg class="icon">
                                            <use xlink:href="<?php echo esc_url(SAFEZONE_PLUGIN_URL . 'admin/images/icons.svg#arrow-right-alt2'); ?>"></use>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                            <div class="foundation-card__list live_settings_board" data-type="firewall"></div>
                        </div>
                    </div>
                    <div class="foundation-table">
                        <div class="table">
                            <div class="table-container">
                                <table id="firewallTable" class="table-main">
                                    <thead class="table-head">
                                    <tr>
                                        <th>IP Address</th>
                                        <th>Country</th>
                                        <th>Type</th>
                                        <th>Activity</th>
                                        <th>User Agent</th>
                                        <th>Created At</th>
                                    </tr>
                                    </thead>
                                    <tbody class="table-body"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="app__body-sidebar firewall_widget">
                <div class="sidebar">
                    <div class="sz-card">
                        <div class="sz-card-order">
                            <div class="sz-card-order__heading">
                                <div class="sz-card-order__heading-title">IPs with the most activity</div>
                                <div class="sz-card-order__heading-text">Based on last 24 hours</div>
                                <!-- Login Protection -->
                            </div>
                            <div class="sz-card-order__list sz-card-order__list--reverse" id="total_firewall"></div>
                        </div>
                    </div>
                    <?php include_once SAFEZONE_PLUGIN_PATH . 'admin/components/safezone-sidebar-upgrade.php'; ?>
                    <?php include_once SAFEZONE_PLUGIN_PATH . 'admin/components/safezone-sidebar-documentation.php'; ?>
                    <?php include_once SAFEZONE_PLUGIN_PATH . 'admin/components/safezone-sidebar-guidance.php'; ?>
                </div>
            </div>
        </div>
    </div>

<?php endif; ?>
