<?php
/**
 * Modal for Safezone admin
 *
 * @package Safezone
 */

// If this file is called directly, abort.

if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<div class="splash-screen">
    <div class="splash-screen__icon">
        <svg class="icon">
            <use xlink:href="<?php echo esc_url( SAFEZONE_PLUGIN_URL . 'admin/images/icons.svg#loading-2' ); ?>"></use>
        </svg>
    </div>
</div>

<div class="modal fade" id="removeWhitelistModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered dialog">
        <div class="modal-container">
            <div class="dialog__icon dialog__icon--red">
                <svg class="icon">
                    <use xlink:href="<?php echo esc_url( SAFEZONE_PLUGIN_URL . 'admin/images/icons.svg#trash' ); ?>"></use>
                </svg>
            </div>
            <div class="dialog__content">
                <div class="dialog__content-title">Are you sure?</div>
                <div class="dialog__content-text">
                    Remove this IP from the whitelist?
                </div>
            </div>
            <div class="dialog__actions dialog__actions--row">
                <button class="btn btn-cancel btn-lg" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-lg btn-error-500 deleteWhiteList">Delete</button>
            </div>
            <button class="dialog__close" data-bs-dismiss="modal" aria-label="modal close">
                <svg class="icon">
                    <use xlink:href="<?php echo esc_url( SAFEZONE_PLUGIN_URL . 'admin/images/icons.svg#no-alt' ); ?>"></use>
                </svg>
            </button>
        </div>
    </div>
</div>

<div class="modal fade" id="code-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-container">
            <div class="modal-heading">
                <div class="modal-heading__title">Code View</div>
                <div class="modal-heading__text">
                    Edit the code below to customize the file.
                </div>
            </div>
            <div class="modal-inner">
                <div class="code-block">
                    <div id="code-area"></div>
                </div>
                <div class="modal-actions modal-actions--row modal-actions--right">
                    <button class="btn btn-error-500 file-delete">
                        Remove File
                    </button>
                    <button class="btn btn-blue-50 file-update">
                        Save File
                    </button>
                </div>
            </div>
            <button class="modal-close codeModalClose">
                <svg class="icon">
                    <use xlink:href="<?php echo esc_url( SAFEZONE_PLUGIN_URL . 'admin/images/icons.svg#no-alt' ); ?>"></use>
                </svg>
            </button>
        </div>
    </div>
</div>

<div class="modal paywall fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="paywall-container">
            <div class="paywall-detail">
                <div class="paywall-detail__container">
                    <div class="paywall-detail__heading">
                        <div class="paywall-detail__top">You are buying</div>
                        <div class="paywall-detail__title">Safe Zone Pro</div>
                    </div>
                    <div class="paywall-detail__list">

                        <div class="paywall-detail__item">
                            <div class="paywall-detail__item-heading">
                                <div class="paywall-detail__item-icon">

                                    <svg class="icon">
                                        <use xlink:href="<?php echo esc_url( SAFEZONE_PLUGIN_URL . 'admin/images/icons.svg#yes-alt' ); ?>"></use>
                                    </svg>

                                </div>
                                <div class="paywall-detail__item-title">AI Security Engine</div>
                            </div>
                            <div class="paywall-detail__item-text">Powered by artificial intelligence (AI), Safe Zone offers advanced security features to keep your site safe from malware, hacking attempts, and other online threats.</div>
                        </div>

                        <div class="paywall-detail__item">
                            <div class="paywall-detail__item-heading">
                                <div class="paywall-detail__item-icon">

                                    <svg class="icon">
                                        <use xlink:href="<?php echo esc_url( SAFEZONE_PLUGIN_URL . 'admin/images/icons.svg#yes-alt' ); ?>"></use>
                                    </svg>

                                </div>
                                <div class="paywall-detail__item-title">Live Firewall</div>
                            </div>
                            <div class="paywall-detail__item-text">Fortify your defenses with our Live Firewall, actively safeguarding your website from evolving threats.</div>
                        </div>

                        <div class="paywall-detail__item">
                            <div class="paywall-detail__item-heading">
                                <div class="paywall-detail__item-icon">
                                    <svg class="icon">
                                        <use xlink:href="<?php echo esc_url( SAFEZONE_PLUGIN_URL . 'admin/images/icons.svg#yes-alt' ); ?>"></use>
                                    </svg>
                                </div>
                                <div class="paywall-detail__item-title">Malware Scanner Pro</div>
                            </div>
                            <div class="paywall-detail__item-text">Detect and eliminate threats with our state-of-the-art Malware Scanner, keeping your WordPress site secure.</div>
                        </div>

                        <div class="paywall-detail__item">
                            <a href="https://wpsafezone.com/features" target="_blank">Learn More</a>
                        </div>

                    </div>
                    <div class="paywall-detail__payment">
                        <div class="paywall-detail__payment-text">Total due</div>
                        <div class="paywall-detail__payment-price">
                            <strong id="choosePrice"><?php echo esc_html($this->packages[0]['currency']); ?><?php echo esc_html($this->packages[0]['price']); ?></strong>/<span id="chooseInterval"><?php echo esc_html($this->packages[0]['billing_period']); ?></span>
                        </div>
                    </div>
                </div>
                <div class="paywall-detail__effect">
                    <img src="<?php echo esc_url( SAFEZONE_PLUGIN_URL . 'admin/images/paywall-effect.webp' ); ?>" alt="effect">
                </div>
            </div>
            <div class="paywall-main">
                <div class="paywall-heading">
                    <div class="paywall-heading__title">Upgrade to Safe Zone Pro</div>
                    <div class="paywall-heading__description">Enjoy advanced protection and peace of mind with WP
                        Safe
                        Zone PRO. Keep your website safe from all threats effortlessly.
                    </div>
                </div>
                <div class="paywall-form">
                    <div class="paywall-ratios">
						<?php foreach ( $this->packages as $key => $value ) : ?>
                            <label class="paywall-ratios__item form-check">
                                <input <?php echo $value['is_primary'] ? 'checked="checked"' : ''; ?>
                                        class="form-check-input" type="radio" name="ratio-paymentMethod"
                                        value="<?php echo esc_attr($value['id']); ?>"
                                        data-price="<?php echo esc_attr($value['currency'] . $value['price']); ?>"
                                        data-interval="<?php echo esc_attr($value['billing_period']); ?>">
                                <span class="paywall-ratios__item-content">
                                <span class="paywall-ratios__item-title"><?php echo esc_html($value['name']); ?></span>
                                <span class="paywall-ratios__item-text">Pay <?php echo esc_html($value['currency']); ?><?php echo esc_html($value['price']); ?> per <?php echo esc_html($value['billing_period']); ?></span>
                              </span>
                            </label>
						<?php endforeach; ?>
                    </div>
                    <div class="paywall-inputs row">
                        <div class="col-lg-6">
                            <div class="d-flex flex-column">
                                <label for="paywall-name" class="form-label">Your name</label>
                                <input type="email" class="form-control form-control-lg" id="subscribe_firstname"
                                       placeholder="Steve">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="d-flex flex-column">
                                <label for="paywall-surname" class="form-label">Your surname</label>
                                <input type="email" class="form-control form-control-lg" id="subscribe_lastname"
                                       placeholder="Jobs">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex flex-column">
                                <label for="paywall-email" class="form-label">Email address</label>
                                <input type="email" class="form-control form-control-lg" id="subscribe_email"
                                       placeholder="example@domain.com">
                            </div>
                        </div>
                    </div>
                    <div class="paywall-actions">
                        <button class="btn btn-lg btn-blue-50 w-100 subscribe" type="button">Proceed to Payment
                        </button>
                        <button class="btn btn-cancel btn-lg w-100 paymentModalClose" type="button">Close</button>
                    </div>
                </div>
                <button class="paywall-main__close paymentModalClose">
                    <svg class="icon">
                        <use xlink:href="<?php echo esc_url(SAFEZONE_PLUGIN_URL . 'admin/images/icons.svg#no-alt'); ?>"></use>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="cancelSubscription" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered dialog">
        <div class="dialog__container">
            <div class="dialog__icon dialog__icon--red">
                <svg class="icon">
                    <use xlink:href="<?php echo esc_url(SAFEZONE_PLUGIN_URL . 'admin/images/icons.svg#remove'); ?>"></use>
                </svg>
            </div>
            <div class="dialog__content">
                <div class="dialog__content-title">License Cancellation</div>
                <div class="dialog__content-text">
                    Are you sure you want to cancel your WP Safe Zone license? <strong>This action can’t be undone.</strong>
                </div>
            </div>
            <div class="dialog__actions dialog__actions--column">
                <a href="/" class="btn btn-lg btn-error-500">Cancel My License</a>
                <button class="btn btn-cancel btn-lg cancelSubscriptionClose">Close</button>
            </div>
            <button class="dialog__close cancelSubscriptionClose">
                <svg class="icon">
                    <use xlink:href="<?php echo esc_url(SAFEZONE_PLUGIN_URL . 'admin/images/icons.svg#no-alt'); ?>"></use>
                </svg>
            </button>
        </div>
    </div>
</div>