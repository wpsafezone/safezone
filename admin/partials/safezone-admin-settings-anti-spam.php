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
        <div class="app__body-main">
            <div class="settings">
				<?php include_once SAFEZONE_PLUGIN_PATH . '/admin/components/safezone-tab-menu.php'; ?>
                <div class="settings-main">
                    <div class="settings-heading">
                        <div class="settings-heading__icon">

                            <svg class="icon">
                                <use xlink:href="<?php echo esc_url( SAFEZONE_PLUGIN_URL . '/admin/images/icons.svg#email-close' ); ?>"></use>
                            </svg>

                        </div>
                        <div class="settings-heading__content">
                            <div class="settings-heading__title">Anti-Spam Settings</div>
                            <div class="settings-heading__text">Edit anti-spam settings and authorization</div>
                        </div>
                    </div>

                    <div class="settings-form">
                        <div class="settings-form__list">
							<?php
							foreach ( SAFEZONE_SETTINGS as $value ) {
								if ( $value['group'] === 'anti-spam' && $value['key'] !== 'sz_anti_spam' ) {
									?>
                                    <div class="settings-form__item">
                                        <div class="settings-form__item-content">
                                            <div class="settings-form__item-title">
												<?php echo esc_html($value['title']); ?>
                                                <?php if($this->is_pro === false): ?>
												<?php echo $value['is_pro'] ? esc_html('<span class="settings-form__item-badge">pro</span>') : '' ?>
                                                <?php endif; ?>
                                            </div>
                                            <div class="settings-form__item-description">
                                                <?php echo esc_html($value['description']); ?>
                                                <?php if($value['doc_url'] !== ""): ?>
                                                    <a href="<?php echo esc_url($value['doc_url']); ?>" target="_blank">(Read on Docs)</a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <label class="form-switch">
                                            <input <?php echo $value['is_pro'] ? 'disabled="disabled"' : ''; ?>
                                                    class="form-check-input update_option"
                                                    data-key="<?php echo esc_attr($value['key']); ?>"
                                                    type="<?php echo esc_attr($value['type']); ?>"
                                                    role="switch" <?php echo get_option( $value['key'] ) === "0" ? '' : 'checked="checked"' ?>>
                                        </label>
                                    </div>
								<?php }
							} ?>
                        </div>
                    </div>

                </div>

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