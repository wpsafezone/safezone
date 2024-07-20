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
	<?php include_once SAFEZONE_PLUGIN_PATH . 'admin/components/safezone-heading.php'; ?>
    <div class="app__body">
        <div class="app__body-main">
            <div class="page">
                <div class="tab-menu tab-menu--shadow">
					<?php include_once SAFEZONE_PLUGIN_PATH . 'admin/components/safezone-tab-menu.php'; ?>
                    <div class="tab-menu__bottom">
                        <div class="actions">
                            <label class="input-group">
                                <span class="input-group-icon">
                                  <svg class="icon">
                                    <use xlink:href="<?php echo esc_url( SAFEZONE_PLUGIN_URL . 'admin/images/icons.svg#search' ); ?>"></use>
                                  </svg>
                                </span>
                                <input type="text" class="form-control" id="firewall_search_input"
                                       placeholder="Search for IP" aria-label="search">
                            </label>
                        </div>
                    </div>
                </div>

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
        <div class="app__body-sidebar">
            <div class="sidebar">
				<?php include_once SAFEZONE_PLUGIN_PATH . 'admin/components/safezone-sidebar-upgrade.php'; ?>
				<?php include_once SAFEZONE_PLUGIN_PATH . 'admin/components/safezone-sidebar-documentation.php'; ?>
				<?php include_once SAFEZONE_PLUGIN_PATH . 'admin/components/safezone-sidebar-guidance.php'; ?>
            </div>
        </div>
    </div>
</div>