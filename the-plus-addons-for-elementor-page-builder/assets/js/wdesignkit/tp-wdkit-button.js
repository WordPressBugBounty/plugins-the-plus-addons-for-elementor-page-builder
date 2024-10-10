(function ($) {
    
    $("document").ready(function () {
        
        elementor.on("preview:loaded", function () {
            
            jQuery(document).on('click', ".tp-live-editor", function () {  
                var $this = this;

                window.tp_wdkit_editor = elementorCommon.dialogsManager.createWidget(
                    "lightbox",
                    {
                        id: "tp-wdkit-elementorp",
                        headerMessage: !1,
                        message: "",
                        hide: {
                            auto: !1,
                            onClick: !1,
                            onOutsideClick: false,
                            onOutsideContextMenu: !1,
                            onBackgroundClick: !0,
                        },
                        position: {
                            my: "center",
                            at: "center",
                        },
                        onShow: function () {

                            var dialogLightboxContent = $(".dialog-lightbox-message");

                            var ggg = [];

                            const requestOptions = {
                                method: "POST",
                                redirect: "follow"
                            };
                            
                            fetch("https://wdesignkit.com/api/wp/widget/preset?widget_slug&builder=elementor", requestOptions)
                                .then(response => response.json())
                                .then(result => {
                                    if (Array.isArray(result.data)) {
                                        ggg = result.data;
                                        console.log('Data:', ggg);
                            
                                        var html_tata = [];
                                        ggg.forEach(item => {
                                            html_tata += `<div class="product-card">
                                                <img src="${item.image_url}" alt="${item.title}" class="product-image">
                                                <div class="product-buttons">
                                                    <button class="add-to-cart" data-id="${item.id}">Download</button>
                                                </div>
                                            </div>`

                                            // container.innerHTML += html;
                                        });

                                        $("#tp-wdkit-wrap-button").html( html_tata );

                                        var clonedWrapElement = $("#tp-wdkit-wrap-button");
                                            clonedWrapElement = clonedWrapElement.clone(true).show()
                                            dialogLightboxContent.html(clonedWrapElement);
                                    }
                            });

                            dialogLightboxContent.on("click", ".tp-close-btn", function () {
                                window.tp_wdkit_editor.hide();
                            });


                            dialogLightboxContent.on("click", ".add-to-cart", function (e) {
                                var get_id = this.dataset.id;

                                var get_html = '';
                                var get_css = '';
                                ggg.forEach(element => {
                                    if( element.id == get_id ){
                                        get_html = element.html;
                                        get_css = element.css;
                                    }
                                });

                                var Perent = $this.closest('.elementor-control-liveeditor');

                                var get_html_editor = Perent.nextElementSibling.querySelector('.ace_editor');
                                var aceEditor_html = ace.edit(get_html_editor);
                                    aceEditor_html.setValue(get_html, -1);
                                    aceEditor_html.session.getSelection().clearSelection(); 
                                    // aceEditor_html.session._emit('change');
                                
                                var get_css_editor = Perent.nextElementSibling.nextElementSibling.querySelector('.ace_editor');
                                var aceEditor_css = ace.edit(get_css_editor);
                                    aceEditor_css.setValue(get_css, -1);
                                    aceEditor_css.session.getSelection().clearSelection(); 
                                    // aceEditor_css.session._emit('change');

                                // Perent.nextElementSibling.querySelector('textarea').value = get_html;
                                // Perent.nextElementSibling.nextElementSibling.querySelector('textarea').value = get_css;

                                // Perent.nextElementSibling.querySelector('iframe').contentWindow.location.reload();
                                // Perent.nextElementSibling.nextElementSibling.querySelector('iframe').contentWindow.location.reload();

                                window.tp_wdkit_editor.hide();

                            });
                        },
                        onHide: function () {
                            window.tp_wdkit_editor.destroy();
                        }
                    }
                );

                window.tp_wdkit_editor.getElements("header").remove();
                window.tp_wdkit_editor.show();

            });

         });
    });
})(jQuery);