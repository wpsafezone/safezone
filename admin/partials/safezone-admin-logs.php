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
                                <input id="logs_search_input" type="text" class="form-control"
                                       placeholder="Search User or Category name" aria-label="search">
                            </label>
                            <select class="actions-select" aria-label="actives" id="logs_category_filter">
                                <option value="" selected="selected">All</option>
								<?php
								foreach ( LOG_STATES as $value ) {
									echo '<option value="' . esc_attr( $value["slug"] ) . '">' . esc_html( $value["name"] ) . '</option>';
								}
								?>
                            </select>
                            <button type="button" class="btn btn-icon btn-blue-5 ms-sm-auto export-button" style="display:none;">
                                <svg class="icon">
                                    <use xlink:href="<?php echo esc_url( SAFEZONE_PLUGIN_URL . 'admin/images/icons.svg#download' ); ?>"></use>
                                </svg>
                                Export
                            </button>
                        </div>
                    </div>
                </div>

                <div class="table">
                    <div class="table-container">
                        <table id="logsTable" class="table-main">
                            <thead class="table-head">
                            <tr>
                                <th><label for="logs_select_all"><input type="checkbox" class="form-check-input" id="logs_select_all"></label></th>
                                <th>Username</th>
                                <th>Category</th>
                                <th>Activity</th>
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