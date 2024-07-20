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
        <div class="app__body-main">
            <div class="whitelist">
                <div class="tab-menu tab-menu--rounded">
					<?php include_once SAFEZONE_PLUGIN_PATH . 'admin/components/safezone-tab-menu.php'; ?>
                </div>
                <div class="whitelist-panel">
                    <div class="whitelist-panel__heading">
                        <div class="whitelist-panel__heading-icon">
                            <svg class="icon">
                                <use xlink:href="<?php echo esc_url( SAFEZONE_PLUGIN_URL . 'admin/images/icons.svg#admin-site' ); ?>"></use>
                            </svg>
                        </div>
                        <div class="whitelist-panel__heading-content">
                            <div class="whitelist-panel__heading-title">Allowed IP</div>
                            <div class="whitelist-panel__heading-text">Add the IP addresses you allow to pass through
                                the firewall.
                            </div>
                        </div>
                    </div>
                    <div class="whitelist-panel__form">
                        <div class="whitelist-panel__form-title">IP Address</div>
                        <div class="whitelist-panel__form-entry">
                            <div class="whitelist-panel__form-input">
                                <label class="input-group">
                                  <span class="input-group-icon">
                                    <svg class="icon">
                                      <use xlink:href="<?php echo esc_url( SAFEZONE_PLUGIN_URL . 'admin/images/icons.svg#admin-site-alt2' ); ?>"></use>
                                    </svg>
                                  </span>
                                    <input type="text" class="form-control whitelist_ip" aria-label="search">
                                </label>
                                <div class="whitelist-panel__form-help">
                                    <svg class="icon">
                                        <use xlink:href="<?php echo esc_url( SAFEZONE_PLUGIN_URL . 'admin/images/icons.svg#info' ); ?>"></use>
                                    </svg>
                                    IPv4 and IPv6 are acceptable.
                                </div>
                            </div>
                            <button class="btn btn-blue-40 add_whitelist" type="button">Add to Whitelist</button>
                        </div>
                    </div>
                </div>

                <div class="table">
                    <div class="table-actions">
                        <div class="table-actions__container" style="justify-content: flex-end;">
                            <button class="btn btn-error-500">
                                Delete All
                            </button>
                        </div>
                    </div>
                    <div class="table-container">
                        <table id="whitelistTable" class="table-main">
                            <thead class="table-head">
                            <tr>
                                <th><label for="whitelist_select_all"><input type="checkbox" class="form-check-input"
                                                                             id="whitelist_select_all"></label></th>
                                <th>IP Address</th>
                                <th>Country</th>
                                <th>Hostname</th>
                                <th>IP Version</th>
                                <th>Timezone</th>
                                <th>Location</th>
                                <th>Date Added</th>
                            </tr>
                            </thead>
                            <tbody class="table-body"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="app__body-sidebar whitelist_widget">
            <div class="sidebar">
                <div class="sz-card">
                    <div class="sz-card-order">
                        <div class="sz-card-order__heading">
                            <div class="sz-card-order__heading-title">Most recently added IPs</div>
                            <div class="sz-card-order__heading-text">Based on the entire year</div>
                        </div>
                        <div class="sz-card-order__list" id="last_items"></div>
                    </div>
                </div>
                <div class="sz-card sz-card-info">
                    <div class="sz-card-info__main">
                        <div class="sz-card-info__value">
                            <span class="sz-card-info__value-text" id="total_whitelist">0</span>
                            <span>
                                <svg class="icon">
                                  <use xlink:href="<?php echo esc_url( SAFEZONE_PLUGIN_URL . 'admin/images/icons.svg#admin-network' ); ?>"></use>
                                </svg>
                             </span>
                        </div>
                        <div class="sz-card-info__content">
                            <div class="sz-card-info__content-title">Allowed IPs</div>
                            <div class="sz-card-info__content-text">Last update: <span id="last_date">-</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>