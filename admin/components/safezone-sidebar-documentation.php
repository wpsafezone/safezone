<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<div class="sz-card">
    <div class="sz-card-documentations">
        <div class="sz-card-documentations__heading">
            <svg class="icon">
                <use xlink:href="<?php echo esc_url( SAFEZONE_PLUGIN_URL . '/admin/images/icons.svg#book-2' ); ?>"></use>
            </svg>
            Safe Zone Documentation
        </div>
        <div class="sz-card-documentations__list">
			<?php
			foreach ( DOCUMENTATION as $value ) {
				?>
                <a href="<?php echo esc_url($value['url']); ?>" target="_blank" class="sz-card-documentations__item">
                    <span class="sz-card-documentations__item-icon">

                      <svg class="icon">
                        <use xlink:href="<?php echo esc_url( SAFEZONE_PLUGIN_URL . '/admin/images/icons.svg#arrow-right-alt' ); ?>"></use>
                      </svg>

                    </span>
                    <span class="sz-card-documentations__item-text"><?php echo esc_html($value['name']); ?></span>
                </a>
			<?php } ?>
        </div>
    </div>
</div>