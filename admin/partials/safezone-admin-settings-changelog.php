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
?>

<div class="app">
	<?php include_once SAFEZONE_PLUGIN_PATH . '/admin/components/safezone-heading.php'; ?>
    <div class="app__body">
        <div class="page">
            <div class="tab-menu">
				<?php include_once SAFEZONE_PLUGIN_PATH . '/admin/components/safezone-tab-menu.php'; ?>
            </div>
            <div class="changelog-banner">
                <div class="changelog-banner__container">
                    <div class="changelog-banner__icon">
                        <svg class="icon">
                            <use xlink:href="<?php echo esc_url( SAFEZONE_PLUGIN_URL . '/admin/images/icons.svg#update-alt' ); ?>"></use>
                        </svg>
                    </div>
                    <div class="changelog-banner__content">
                        <div class="changelog-banner__content-title">Changelog</div>
                        <div class="changelog-banner__content-text">See the latest updates and fixes</div>
                    </div>
                </div>
                <div class="changelog-banner__badge">
                  <span class="badge badge--blue" title="Current Version: 1.0.3">

                    <span class="badge__dot"></span>

                    <span class="badge__text">Current Version: <?php echo esc_html( $this->version ); ?></span>
                  </span>
                </div>
            </div>
            <div class="changelog-versions">
				<?php
				foreach ( $this->parse_changelog() as $entry ):
					?>
                    <div class="changelog-versions__item">
                        <div class="changelog-versions__item-heading">
                            <div class="changelog-versions__item-version"><?php echo esc_html( $entry['version'] ); ?></div>
                            <div class="changelog-versions__item-line"></div>
                            <div class="changelog-versions__item-date"><?php echo esc_html( $entry['date'] ); ?></div>
                        </div>
                        <ul class="changelog-versions__item-list">
							<?php foreach ( $entry['changes'] as $change ): ?>
                                <li><?php echo esc_html( $change ); ?></li>
							<?php endforeach; ?>
                        </ul>
                    </div>
				<?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
