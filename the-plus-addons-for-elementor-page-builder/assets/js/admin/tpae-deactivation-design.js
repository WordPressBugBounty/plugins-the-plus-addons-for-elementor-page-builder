(function ($) {
    'use strict';
    const assetUrl = new URL('../../svg/navbox/tpae-logomark.svg', document.currentScript.src).href;
    const { __ } = wp.i18n;
    $(function () {
        const $modal = $('.posi-deact-p-the-plus-addons-for-elementor-page-builder');
        if (!$modal.length) return;
        $modal.addClass('tpae-ds');
        const $dialog = $modal.find('.posi-deact-dialog').addClass('nx-modal');
        $dialog.find('h3').addClass('nx-modal__title');
        $dialog.find('.posi-deact-icon img').attr({src: assetUrl, width: 30, height: 30});
        $dialog.find('.posi-deact-cards label').addClass('nx-radio-card');
        $dialog.find('textarea').addClass('nx-input nx-textarea');
        $dialog.find('.posi-deact-submit').addClass('nx-btn nx-btn--primary');
        $dialog.find('.posi-deact-skip').addClass('nx-btn nx-btn--plain nx-btn--sm');
        const $contact = $dialog.find('.posi-deact-opt').addClass('nx-check');
        $contact.children('span').addClass('nx-check__label');
        $contact.find('input').after('<span class="nx-check__box" aria-hidden="true"><svg class="nx-check__icon" viewBox="0 0 16 16" fill="none"><path class="nx-check__tick" d="M4 8.2 6.9 11 12 5.4" pathLength="1" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" /></svg></span>');
        let returnFocus;
        $(document).on('click.tpaeSurveyDesign', 'span.deactivate a[href*="the-plus-addons-for-elementor-page-builder"]', function () { returnFocus = this; });
        $('<button>', {type: 'button', class: 'nx-iconbtn nx-iconbtn--sm tpae-deact-close', 'aria-label': __('Close', 'tpebl')})
            .html('<svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" /></svg>')
            .on('click', function () { $modal.hide(); returnFocus?.focus(); })
            .appendTo($dialog);
    });
})(jQuery);
