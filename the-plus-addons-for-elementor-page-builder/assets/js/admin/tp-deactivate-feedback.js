/*! TPAE Free - v5.3.3*/
(function ($) {
  'use strict';

  const { __ } = wp.i18n;
  const submit_txt = __("Submit & Deactivate", "tpebl");
  const skip_txt = __("Skip & Deactivate", "tpebl");

  var TheplusAdminDialog = {
    cacheElements: function cacheElements() {
      this.cache = {
        $deactivateLink: $('#the-list').find('[data-slug="the-plus-addons-for-elementor-page-builder"] span.deactivate a'),
        $dialogHeader: $('#tp-feedback-dialog-header'),
        $dialogForm: $('#tp-feedback-dialog-form')
      };
    },
    bindEvents: function bindEvents() {
      var self = this;
          self.cache.$deactivateLink.on('click', function (event) {
            event.preventDefault();
            self.getModal().show();
          });
    },
    deactivate: function deactivate() {
      location.href = this.cache.$deactivateLink.attr('href');
    },
    initModal: function initModal() {
      var self = this, modal;

          self.getModal = function () {
            if (!modal) {
              modal = elementorCommon.dialogsManager.createWidget('lightbox', {
                id: 'tp-deactivate-feedback-modal',
                headerMessage: self.cache.$dialogHeader,
                message: self.cache.$dialogForm,
                hide: {
                  onButtonClick: false
                },
                position: {
                  my: 'center',
                  at: 'center'
                },
                onReady: function onReady() {
                  DialogsManager.getWidgetType('lightbox').prototype.onReady.apply(this, arguments);
                  this.addButton({
                    name: 'submit',
                    text: submit_txt,
                    callback: self.sendFeedback.bind(self)
                  });
                  this.addButton({
                    name: 'skip',
                    text: skip_txt,
                    callback: self.skipFeedback.bind(self)
                  });
                  $('#tp-feedback-close-button').on('click', function() {
                    $('#tp-deactivate-feedback-modal').hide();
                  });
                },
                onShow: function onShow() {
                  var $dialogModal = $('#tp-deactivate-feedback-modal'),
                      $textareaWrapper = $dialogModal.find('#tp-other-reason-textarea-wrapper');
                  $textareaWrapper.hide();

                  $dialogModal.find('.tp-feedback-option').off('click').on('click', function () {

                    var associatedInputId = $(this).attr('for');
                    var $radio = $('#' + associatedInputId);
                    $radio.prop('checked', true);
                    $textareaWrapper.show();
                  });
                }
              });
            }

            return modal;
          };
    },
    sendFeedback: function sendFeedback() {
      var self = this,
          formData = self.cache.$dialogForm.serialize();

      var urlEncodedString = formData;
      var queryString = decodeURIComponent(urlEncodedString);
      var formData = new URLSearchParams(queryString);

      var reason_key = formData.get('reason_key');
        if( !reason_key ){
          return;
        }

      var screenWidth = window.innerWidth,
          screenHeight = window.innerHeight,
          resolutions = (screenWidth + ' x ' + screenHeight);

      self.getModal().getElements('submit').text('').addClass('tp-loading');

      jQuery.ajax({
        url: theplus_ajax_url,
        type: "post",
        data: {
          action: 'tp_deactivate_rateus_notice',
          reason_key: reason_key,
          nonce: formData.get('nonce'),
          reason_desc: formData.get('reason_tp_other'),
          resolutions: resolutions,
        },
        beforeSend: function () {
        },
        success: function (response) {
          location.href = $('#the-list').find('[data-slug="the-plus-addons-for-elementor-page-builder"] span.deactivate a').attr('href')
        },
        error: function(xhr, status, error) {
          location.href = $('#the-list').find('[data-slug="the-plus-addons-for-elementor-page-builder"] span.deactivate a').attr('href')
        }
      });
    },
    skipFeedback: function skipFeedback() {
      var self = this,
          formData = self.cache.$dialogForm.serialize();

      var urlEncodedString = formData;
      var queryString = decodeURIComponent(urlEncodedString);
      var formData = new URLSearchParams(queryString);

      var screenWidth = window.innerWidth,
          screenHeight = window.innerHeight,
          resolutions = (screenWidth + ' x ' + screenHeight);

          jQuery.ajax({
          url: theplus_ajax_url,
          type: "post",
          data: {
            action: 'tp_deactivate_rateus_notice',
            nonce: formData.get('nonce'),
            reason_key: 'skip',
            reason_desc: 'skip',
            resolutions: resolutions,
          },
          beforeSend: function () {
          },
          success: function (response) {
            location.href = $('#the-list').find('[data-slug="the-plus-addons-for-elementor-page-builder"] span.deactivate a').attr('href')
          },
          error: function(xhr, status, error) {
            location.href = $('#the-list').find('[data-slug="the-plus-addons-for-elementor-page-builder"] span.deactivate a').attr('href')
          }
        });
    },
    init: function init() {
      this.initModal();
      this.cacheElements();
      this.bindEvents();
    }
  };

  $(function () {
    TheplusAdminDialog.init();
  });

})(jQuery);
