/*section column link*/
jQuery(document).ready(function( $ ) {
	$(document).on('click','[data-tp-sc-link]',function() {
		/*Get Attributes*/
		var tp_sc_link = $(this).data('tp-sc-link'),
			tp_sc_link_external = $(this).data('tp-sc-link-external');
		/*Get Attributes*/

		/*length*/
		if ($(this).find('[data-tp-sc-link-add]').length == 0) {
			/* Build the anchor as a node, never by pasting the URL into HTML:
			   the attribute value is entity-decoded and could close the quote. */
			var addlink = document.createElement('a');
			addlink.href = String(tp_sc_link || '');

			/* Only follow ordinary links, never javascript: or data: URLs. */
			if (['http:', 'https:', 'mailto:', 'tel:'].indexOf(addlink.protocol) === -1) {
				return;
			}

			addlink.setAttribute('data-tp-sc-link-add', '');
			addlink.style.display = 'none';

			/*Open in new window*/
			if(tp_sc_link_external == 'on') {
				addlink.target = '_blank';
			}
			/*Open in new window*/

			this.appendChild(addlink);
		}
		/*length*/
		
		var firelink = $(this).find('[data-tp-sc-link-add]');
		firelink[0].click();	
	});
});
