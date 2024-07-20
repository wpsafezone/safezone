<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<div class="tab-menu__main">
    <div class="tab-menu__list">
		<?php
		foreach ( $this->menu as $item ) {
			$isActive = $item['is_active'] ? 'active' : '';
			echo '<a href="' . esc_url($item['path']) . '" class="tab-menu__item ' . esc_attr($isActive) . '">' . esc_html($item['name']) . '</a>';
		}
		?>
    </div>
    <a href="/" class="tab-menu__logo" aria-label="route home">
        <img draggable="false" src="<?php echo esc_url( SAFEZONE_PLUGIN_URL . 'admin/images/logo.svg' ); ?>" alt="logo">
    </a>
</div>