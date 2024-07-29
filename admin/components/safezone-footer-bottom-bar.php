<?php

if (!defined('WPINC')) {
    die;
}

$firewall = get_option('sz_firewall');
$anti_spam = get_option('sz_anti_spam');

?>

<div class="bottom-bar">
    <div class="bottom-bar__list">
        <div class="bottom-bar__item">
            <div class="bottom-bar__item-content">
                <span class="bottom-bar__item-text">
                  Cloud Protection:
                </span>
                <?php if(!$this->is_pro):?>
                <span class="bottom-bar__item-badge">
                    pro
                </span>
                <?php else:;?>
                <span
                   class="form-check form-switch form-switch-sm form-check-reverse">
                  <span class="form-check-label">
                    Active
                  </span>
                    <input disabled="disabled" class="form-check-input" type="checkbox" aria-label="state" role="switch" checked="checked">
                </span>
                <?php endif;?>
            </div>
        </div>
        <div class="bottom-bar__item">
            <div class="bottom-bar__item-content">
                <span class="bottom-bar__item-text">
                  Firewall:
                </span>
                <?php if(!$this->is_pro):?>
                <span class="bottom-bar__item-badge">
                    pro
                </span>
                <?php else:;?>
                <div class="form-check form-switch form-switch-sm form-check-reverse">
                    <span class="form-check-label"><?php echo $firewall === "0" ? 'Disabled' : 'Active'?></span>
                    <input class="form-check-input protection_change" aria-label="state" data-type="sz_firewall" type="checkbox" role="switch" <?php echo $firewall === "0" ? '' : 'checked="true"'?>>
                </div>
                <?php endif;?>
            </div>
        </div>
        <div class="bottom-bar__item">
            <div class="bottom-bar__item-content">
                <span class="bottom-bar__item-text">
                  Anti-Spam Engine:
                </span>
                <div class="form-check form-switch form-switch-sm form-check-reverse">
                    <span class="form-check-label"><?php echo $anti_spam === "0" ? 'Disabled' : 'Active'?></span>
                    <input class="form-check-input protection_change" aria-label="state" data-type="sz_anti_spam" type="checkbox" role="switch" <?php echo $anti_spam === "0" ? '' : 'checked="true"'?>>
                </div>
            </div>
        </div>
    </div>
    <?php if(!$this->is_pro):?>
    <span class="bottom-bar__version">
        Free Version
    </span>
    <?php else:?>
    <span class="bottom-bar__version">
        Pro Version
    </span>
    <?php endif;?>
</div>
