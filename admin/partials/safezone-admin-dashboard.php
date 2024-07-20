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
            <div class="home">
                <div class="tab-menu tab-menu--shadow">
					<?php include_once SAFEZONE_PLUGIN_PATH . 'admin/components/safezone-tab-menu.php'; ?>
                    <div class="tab-menu__bottom">
                        <div class="home__cards">
                            <div class="sz-card sz-card-info">
                                <div class="sz-card-info__main">
                                    <div class="sz-card-info__value">
                                        <span class="sz-card-info__value-text"
                                              id="firewall_count"><?php echo esc_html( $this->blocked_activities_count ); ?></span>
                                        <span>
                                          <svg class="icon">
                                            <use xlink:href="<?php echo esc_url( SAFEZONE_PLUGIN_URL . 'admin/images/icons.svg#lock' ); ?>"></use>
                                          </svg>
                                        </span>
                                    </div>
                                    <div class="sz-card-info__content">
                                        <div class="sz-card-info__content-title">Blocked activities</div>
                                        <div class="sz-card-info__content-text">Last update:</div>
                                    </div>
                                </div>
                                <div class="sz-card-info__actions">
                                    <select class="form-select form-select-sm counter_select" aria-label="info select"
                                            data-type="firewall">
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
                                        <span class="sz-card-info__value-text"
                                              id="anti_spam_count"><?php echo esc_html( $this->blocked_spams_count ); ?></span>
                                        <span>
                                          <svg class="icon">
                                            <use xlink:href="<?php echo esc_url( SAFEZONE_PLUGIN_URL . '/admin/images/icons.svg#lock' ); ?>"></use>
                                          </svg>
                                        </span>
                                    </div>
                                    <div class="sz-card-info__content">
                                        <div class="sz-card-info__content-title">Blocked spams</div>
                                        <div class="sz-card-info__content-text">Last update:</div>
                                    </div>
                                </div>
                                <div class="sz-card-info__actions">
                                    <select class="form-select form-select-sm counter_select" aria-label="info select"
                                            data-type="anti_spam">
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
                                        <span class="sz-card-info__value-text"><?php echo esc_html( $this->malware_score ); ?><span
                                                    class="fw-light">/5</span></span>
                                        <span>
                                          <svg class="icon">
                                            <use xlink:href="<?php echo esc_url( SAFEZONE_PLUGIN_URL . 'admin/images/icons.svg#yes-alt' ); ?>"></use>
                                          </svg>
                                        </span>
                                    </div>
                                    <div class="sz-card-info__content">
                                        <div class="sz-card-info__content-title">Scan report</div>
                                        <div class="sz-card-info__content-text">Last
                                            scan: <?php echo esc_html( $this->last_malware_scan ); ?></div>
                                    </div>
                                </div>

                                <div class="sz-card-info__actions">
                                    <a href="<?php echo esc_url( admin_url( 'admin.php?page=safezone-malware' ) ); ?>">
                                        <span class="badge badge--blue" title="See Results">
                                          <span class="badge__text">See Results</span>
                                        </span>
                                    </a>
                                </div>
                            </div>
                            <div class="sz-card sz-card-info">
                                <div class="sz-card-info__main">
                                    <div class="sz-card-info__value">
                                        <span class="sz-card-info__value-text"><?php echo esc_html( $this->pending_update_count ); ?></span>
                                        <span>
                                          <svg class="icon">
                                            <use xlink:href="<?php echo esc_url( SAFEZONE_PLUGIN_URL . 'admin/images/icons.svg#update' ); ?>"></use>
                                          </svg>
                                        </span>
                                    </div>
                                    <div class="sz-card-info__content">
                                        <div class="sz-card-info__content-title">Pending update</div>
                                        <div class="sz-card-info__content-text">New update is available</div>
                                    </div>
                                </div>
                                <div class="sz-card-info__actions">
                                    <a href="<?php echo esc_url( admin_url( 'update-core.php' ) ); ?>">
                                        <span class="badge badge--blue" title="Update">
                                          <span class="badge__text">Update</span>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="home__chart">
                    <div class="home__chart-container"></div>
                </div>
				<?php include_once SAFEZONE_PLUGIN_PATH . 'admin/components/safezone-footer-bottom-bar.php'; ?>
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