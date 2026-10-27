<?php

if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Base_Data_Control;

class Tpae_Need_Help_Control extends Base_Data_Control {

	public function get_type() {
		return 'tpae_need_help';
	}

	public function content_template() {
		?>
		<#
			var defaults = [
				{ label: '<?php echo esc_js( __( 'Read Docs', 'tpebl' ) ); ?>',       url: 'https://theplusaddons.com/docs/?utm_source=wpbackend&utm_medium=elementoreditor&utm_campaign=widget' },
				{ label: '<?php echo esc_js( __( 'Watch Video', 'tpebl' ) ); ?>',     url: 'https://www.youtube.com/@posimyth?sub_confirmation=1' },
				{ label: '<?php echo esc_js( __( 'Request Feature', 'tpebl' ) ); ?>', url: 'https://roadmap.theplusaddons.com/' },
				{ label: '<?php echo esc_js( __( 'Plugin Roadmap', 'tpebl' ) ); ?>',  url: 'https://roadmap.theplusaddons.com/roadmap' }
			];

			var buttons = [];
			for (var i = 0; i < 4; i++) {
				if (data.controlValue && data.controlValue[i] && (data.controlValue[i].label || data.controlValue[i].url)) {
					buttons[i] = {
						label: data.controlValue[i].label || defaults[i].label,
						url: data.controlValue[i].url || defaults[i].url
					};
				} else {
					buttons[i] = defaults[i];
				}
			}
		#>
		<?php

		echo '<div class="tpae-need-help">';
			echo '<div class="tpae-need-help-req-buttons">';
			?>
				<# for (var i = 0; i < buttons.length; i++) { #>
					<# if (buttons[i]) { #>
						<a class="tp-docs-link-container" href="{{ buttons[i].url }}" target="_blank" rel="noopener noreferrer">
							<div class="tp-docs-link">{{ buttons[i].label }}</div>
						</a>
					<# } #>
				<# } #>
			<?php
			echo '</div>';

			echo '<a class="tpae-ask-ai" href="https://theplusaddons.com/widget-recipes/?utm_source=wpbackend&utm_medium=elementoreditor&utm_campaign=widgetrecipes" target="_blank" rel="noopener noreferrer">';
				echo '<span>' . esc_html__('Widget Recipes', 'tpebl') . '</span>';
				echo '<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M7.5 3.75H4.5A1.5 1.5 0 0 0 3 5.25v8.25A1.5 1.5 0 0 0 4.5 15h8.25a1.5 1.5 0 0 0 1.5-1.5V10.5M10.5 3H15v4.5M15 3L8.25 9.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';
			echo '</a>';
		echo '</div>';
	}
}
