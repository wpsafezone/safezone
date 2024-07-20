<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<div class="sz-card sz-card-info">
    <div class="sz-card-info__main">
        <div class="sz-card-info__value">
            <span class="sz-card-info__value-text">8</span>
            <span>
                <svg class="icon">
                  <use xlink:href="<?php echo esc_url( SAFEZONE_PLUGIN_URL . '/admin/images/icons.svg#lock' ); ?>"></use>
                </svg>
            </span>
        </div>
        <div class="sz-card-info__content">
            <div class="sz-card-info__content-title">Blocked activities</div>
            <div class="sz-card-info__content-text">Last update: 23:45</div>
        </div>
    </div>
    <div class="sz-card-info__actions">
        <select class="form-select form-select-sm" aria-label="info select">
            <option value="1" selected="selected">Today</option>
            <option value="2">Today</option>
        </select>
    </div>
</div>

<div class="sz-card sz-card-info">
    <div class="sz-card-info__main">
        <div class="sz-card-info__value">
            <span class="sz-card-info__value-text">4</span>
            <span>
                <svg class="icon">
                <use xlink:href="<?php echo esc_url( SAFEZONE_PLUGIN_URL . '/admin/images/icons.svg#lock' ); ?>"></use>
            </svg>
          </span>
        </div>
        <div class="sz-card-info__content">
            <div class="sz-card-info__content-title">Blocked attemps</div>
            <div class="sz-card-info__content-text">Last update: 23:45</div>
        </div>
    </div>
    <div class="sz-card-info__actions">
        <select class="form-select form-select-sm" aria-label="info select">
            <option value="1" selected="selected">Today</option>
            <option value="2">Today</option>
        </select>
    </div>
</div>