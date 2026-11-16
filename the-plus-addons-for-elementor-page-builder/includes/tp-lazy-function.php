<?php
/**
 * TP Pro LazyLoad Images
 *
 * @link       https://posimyth.com/
 * @since      5.6.7
 *
 * @package    the-plus-addons-for-elementor-page-builder
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Tp_LazyLoad_Images
 *
 * @since   5.6.7
 **/
class Tp_LazyLoad_Images {

	/**
	 * Updates the attributes of <img> tags in the provided content.
	 *
	 * @since 5.6.7
	 *
	 * @param string $img_content   The HTML content containing <img> tags.
	 * @param string $old_attr_name The name of the attribute to replace.
	 * @param string $new_attr_name The new attribute name to set.
	 */
	public function tp_change_attribute_form_images( $img_content, $old_attr_name, $new_attr_name ) {
		$tagname = 'img';

		if ( ! preg_match_all( '/<' . $tagname . ' [^>]+>/', $img_content, $matches ) ) {
			return $img_content;
		}

		$sel_images = array();
		foreach ( $matches[0] as $image ) {
			$sel_images[] = $image;
		}

		foreach ( $sel_images as $image ) {
			$img_content = str_replace( $image, $this->tp_rename_attribute_for_image( $image, $old_attr_name, $new_attr_name ), $img_content );
		}

		return $img_content;
	}

	/**
	 * Renames an attribute for a specific <img> tag.
	 *
	 * @param string $image          The <img> tag as a string from which the attribute needs to be renamed.
	 * @param string $old_attr_name  The name of the attribute to replace.
	 * @param string $new_attr_name  The new attribute name to set.
	 */
	public function tp_rename_attribute_for_image( $image, $old_attr_name, $new_attr_name ) {
		$tag = 'img';

		$old_attr_value = ltrim(
			rtrim(
				trim(
					preg_replace(
						'/(\\<' .
						$tag .
						'[^>]+)(\\s?' .
						$old_attr_name .
						'\\="[^"]+"\\s?)([^>]+)(>)/',
						'${2}',
						$image
					)
				),
				'"'
			),
			$old_attr_name . '="'
		);

		$removed = $this->tp_remove_attribute_from_images( $image, $old_attr_name );

		$image = $this->tp_add_attribute_to_image( $removed, $new_attr_name, $old_attr_value );

		return $image;
	}

	/**
	 * Removes a specific attribute from all <img> tags in the provided HTML content.
	 *
	 * @param string $img_content The HTML content containing <img> tags.
	 * @param string $attribute   The name of the attribute to remove from <img> tags.
	 */
	public function tp_remove_attribute_from_images( $img_content, $attribute ) {
		$tagname = 'img';

		if ( ! preg_match_all( '/<' . $tagname . ' [^>]+>/', $img_content, $matches ) ) {
			return $img_content;
		}

		$select_images = array();

		foreach ( $matches[0] as $image ) {
			$select_images[] = $image;
		}

		foreach ( $select_images as $image ) {
			$img_content = str_replace( $image, $this->tp_remove_attribute_from_single_image( $image, $attribute ), $img_content );
		}

		return $img_content;
	}

	/**
	 * Removes a specific attribute from a single <img> tag.
	 *
	 * @param string $image     The <img> tag as a string from which the attribute needs to be removed.
	 * @param string $attribute The name of the attribute to remove from the <img> tag.
	 */
	public function tp_remove_attribute_from_single_image( $image, $attribute ) {
		$tagname = 'img';

		return preg_replace( '/(\\<' . $tagname . '[^>]+)(\\s?' . $attribute . '\\="[^"]+"\\s?)([^>]+)(>)/', '${1}${3}${4}', $image );
	}

	/**
	 * Adds a new attribute with a specified value to an <img> tag, replacing any existing attribute with the same name.
	 *
	 * @param string $image      The <img> tag as a string to which the attribute needs to be added.
	 * @param string $attr_name  The name of the attribute to add or replace.
	 * @param string $attr_value The value of the attribute to set.
	 */
	public function tp_add_attribute_to_image( $image, $attr_name, $attr_value ) {
		$tagname     = 'img';
		$update_attr = sprintf( ' %s="%s"', esc_attr( $attr_name ), esc_attr( $attr_value ) );

		$val = preg_replace(
			'/<' . $tagname . ' ([^>]+?)[\\/ ]*>/',
			'<' . $tagname . ' $1' . $update_attr . ' />',
			$this->tp_remove_attribute_from_images( $image, $attr_name )
		);

		return $val;
	}
}

if ( ! function_exists( 'tp_getAspectRatio' ) ) {
	function tp_getAspectRatio( int $width, int $height ) {
		$aspact_ratio = $width / $height;   // tp_getAspectRatio($image_src[1], $image_src[2]);
		$target_width = $target_height = min( 10, max( $width, $height ) );

		if ( $aspact_ratio < 1 ) {
			$target_width = $target_height * $aspact_ratio;
		} else {
			$target_height = $target_width / $aspact_ratio;
		}

		return array( $target_width, $target_height );
	}
}

if ( ! function_exists( 'tp_has_lazyload' ) ) {
	function tp_has_lazyload() {
		$theplus_options = get_option( 'theplus_api_connection_data' );
		$lazyopt         = ! empty( $theplus_options['plus_lazyload_opt'] ) ? $theplus_options['plus_lazyload_opt'] : '';

		if ( 'enable' === $lazyopt ) {
			return true;
		} else {
			return false;
		}
	}
}

if ( ! function_exists( 'tp_lazyload_type' ) ) {
	function tp_lazyload_type() {
		$theplus_options = get_option( 'theplus_api_connection_data' );
		$lazyopt         = ( ! empty( $theplus_options['plus_lazyload_opt'] ) ) ? $theplus_options['plus_lazyload_opt'] : '';
		$lazyanimopt     = ( ! empty( $theplus_options['plus_lazyload_opt_anim'] ) ) ? $theplus_options['plus_lazyload_opt_anim'] : '';

		if ( 'enable' === $lazyopt && ! empty( $lazyanimopt ) ) {
			return $lazyanimopt;
			// return 'dbl-circle';  // fade | dbl-circle | circle | blur-img | skeleton
		}
	}
}

if ( ! function_exists( 'tp_lazyload_state' ) ) {
	/**
	 * Lazy Load state held in a `theplus_api_connection_data` value: the animation type
	 * when it is enabled, `off` otherwise.
	 *
	 * @since 6.6.1
	 *
	 * @param mixed $options The option value.
	 */
	function tp_lazyload_state( $options ) {
		if ( ! is_array( $options ) || empty( $options['plus_lazyload_opt'] ) || 'enable' !== $options['plus_lazyload_opt'] ) {
			return 'off';
		}

		return ! empty( $options['plus_lazyload_opt_anim'] ) ? $options['plus_lazyload_opt_anim'] : '';
	}
}

if ( ! function_exists( 'tp_lazyload_flush_on_change' ) ) {
	/**
	 * Flush the caches that hold lazy load output when the setting changes.
	 *
	 * Two caches outlive the setting. The per-post bundle only carries the lazy load
	 * script if it was built while lazy load was on, and saving the setting never marked
	 * it stale, so turning lazy load on served `data-src` images with no script to load
	 * them. Elementor's element cache keeps the rendered widget HTML (up to its TTL), so
	 * turning lazy load off kept serving `data-src` images after the bundle dropped the
	 * script, and the images never appeared. Both are flushed here, together with the
	 * page caches that hold the same HTML.
	 *
	 * Hooked to the option update rather than the dashboard save, so every writer of
	 * the option is covered. Only a real change of the lazy load state flushes.
	 *
	 * @since 6.6.1
	 *
	 * @param mixed $old_value The previous option value.
	 * @param mixed $value     The new option value.
	 */
	function tp_lazyload_flush_on_change( $old_value, $value ) {
		if ( tp_lazyload_state( $old_value ) === tp_lazyload_state( $value ) ) {
			return;
		}

		// Mark every per-post bundle stale; each one is rebuilt on its next view.
		update_option( 'tp_save_update_at', strtotime( 'now' ), false );

		if ( function_exists( 'l_theplus_library' ) ) {
			l_theplus_library()->remove_backend_dir_files();
		}

		// Clears the element cache and `_elementor_css`, which also gives the rebuilt bundles a new `ver`.
		if ( class_exists( '\Elementor\Plugin' ) && ! empty( \Elementor\Plugin::$instance->files_manager ) ) {
			\Elementor\Plugin::$instance->files_manager->clear_cache();
		}

		if ( function_exists( 'l_theplus_generator' ) ) {
			l_theplus_generator()->tp_third_patry_cache();
		}
	}

	add_action( 'update_option_theplus_api_connection_data', 'tp_lazyload_flush_on_change', 10, 2 );
	add_action(
		'add_option_theplus_api_connection_data',
		function ( $option, $value ) {
			tp_lazyload_flush_on_change( array(), $value );
		},
		10,
		2
	);
}

if ( ! function_exists( 'tp_lazyload_blur_placeholder' ) ) {
	/**
	 * Tiny inline placeholder for the blur-img lazy load type.
	 *
	 * The placeholder was requested as a ~10 px size, but WordPress only serves
	 * registered sizes, so it fell back to `medium` (300x200, about 12 KB) and cost a
	 * request per image. This builds a 16 px copy once, from the `medium` file when there
	 * is one, and keeps it as a data URI in post meta. An empty string means the image
	 * could not be resized (SVG, missing file), and the caller keeps its old placeholder.
	 *
	 * @since 6.6.1
	 *
	 * @param int $id Attachment ID.
	 */
	function tp_lazyload_blur_placeholder( $id ) {
		$cached = get_post_meta( $id, '_tp_lazy_blur', true );

		if ( '' !== $cached ) {
			return 'none' === $cached ? '' : $cached;
		}

		$placeholder = '';
		$source      = '';
		$medium      = image_get_intermediate_size( $id, 'medium' );
		$uploads     = wp_get_upload_dir();

		if ( ! empty( $medium['path'] ) && file_exists( trailingslashit( $uploads['basedir'] ) . $medium['path'] ) ) {
			$source = trailingslashit( $uploads['basedir'] ) . $medium['path'];
		} elseif ( get_attached_file( $id ) && file_exists( get_attached_file( $id ) ) ) {
			$source = get_attached_file( $id );
		}

		$editor = $source ? wp_get_image_editor( $source ) : null;

		if ( $editor && ! is_wp_error( $editor ) && ! is_wp_error( $editor->resize( 16, 16, false ) ) ) {
			$mime = 'image/png' === get_post_mime_type( $id ) ? 'image/png' : 'image/jpeg';
			$dir  = get_temp_dir();
			$tmp  = $dir . wp_unique_filename( $dir, 'tp-lazy-blur-' . $id . ( 'image/png' === $mime ? '.png' : '.jpg' ) );

			$editor->set_quality( 40 );
			$saved = $editor->save( $tmp, $mime );

			if ( ! is_wp_error( $saved ) && ! empty( $saved['path'] ) && file_exists( $saved['path'] ) ) {
				$placeholder = 'data:' . $mime . ';base64,' . base64_encode( file_get_contents( $saved['path'] ) );
				wp_delete_file( $saved['path'] );
			}

			if ( file_exists( $tmp ) ) {
				wp_delete_file( $tmp );
			}
		}

		update_post_meta( $id, '_tp_lazy_blur', '' !== $placeholder ? $placeholder : 'none' );

		return $placeholder;
	}

	// Rebuild the placeholder when the image changes.
	add_filter(
		'wp_update_attachment_metadata',
		function ( $data, $id ) {
			delete_post_meta( $id, '_tp_lazy_blur' );
			return $data;
		},
		10,
		2
	);
}

if ( ! function_exists( 'tp_get_image_rander' ) ) {
	function tp_get_image_rander( $id = '', $size = 'full', $attr = array(), $posttype = 'attachment' ) {
		if ( empty( $id ) ) {
			return '';
		}

		if ( ! empty( $posttype ) && 'post' === $posttype ) {
			$get_post = get_post( $id );

			if ( ! $get_post ) {
				return '';
			}

			$id = get_post_thumbnail_id( $get_post );
		}

		if ( ! wp_get_attachment_image_src( $id ) ) {
			return '';
		}

		$output = '';

		if ( tp_has_lazyload() ) {
			$lazy_type            = tp_lazyload_type();
			$attr['data-tp-lazy'] = $lazy_type;

			if ( 'dbl-circle' === $lazy_type || 'circle' === $lazy_type || 'skeleton' === $lazy_type ) {
				$output .= '<span class="tp-loader-' . esc_attr( $lazy_type ) . '"></span>';
			} elseif ( 'blur-img' === $lazy_type ) {
				$image_src   = wp_get_attachment_image_src( $id, $size );
				$placeholder = tp_lazyload_blur_placeholder( $id );

				if ( '' !== $placeholder && ! empty( $image_src[1] ) && ! empty( $image_src[2] ) ) {
					/*
					 * Once decoded, a placeholder sizes the image by its own shape, not by the width
					 * and height attributes, so a 16 px copy of a 3:2 image shifted a square thumbnail.
					 * An SVG of the output size, cropping the copy the way WordPress crops, keeps the
					 * box exact until the full image loads.
					 */
					$attr['src'] = 'data:image/svg+xml;base64,' . base64_encode(
						sprintf(
							'<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="%1$d" height="%2$d" viewBox="0 0 %1$d %2$d"><image width="%1$d" height="%2$d" preserveAspectRatio="xMidYMid slice" xlink:href="%3$s"/></svg>',
							(int) $image_src[1],
							(int) $image_src[2],
							$placeholder
						)
					);
				} elseif ( ! empty( $image_src[1] ) && ! empty( $image_src[2] ) ) {
					$aspact_ratio = tp_getAspectRatio( $image_src[1], $image_src[2] );

					$attr['src'] = wp_get_attachment_image_url( $id, $aspact_ratio );
				}

				$attr['data-src'] = wp_get_attachment_image_url( $id, $size );

			}

			$attr['class'] = ( isset( $attr['class'] ) ? $attr['class'] : '' ) . ' tp-lazyload';
		}

		$get_image = wp_get_attachment_image( $id, $size, false, $attr );

		$check_srcset = strpos( $get_image, 'srcset' ) !== false;

		if ( tp_has_lazyload() ) {
			$lazy_type = tp_lazyload_type();

			/**
			 * The fallback copy must not carry `tp-lazyload`: the lazy load CSS hides that
			 * class, which left the image invisible with JavaScript off. blur-img holds only
			 * its placeholder in `src`, so the copy takes the full image from `data-src`.
			 *
			 * @since 6.6.1
			 */
			$noscript_image = preg_replace( '/\sdata-tp-lazy="[^"]*"/', '', str_replace( ' tp-lazyload', '', $get_image ) );

			if ( 'blur-img' === $lazy_type && preg_match( '/\sdata-src="([^"]*)"/', $noscript_image, $full_src ) ) {
				$noscript_image = str_replace( $full_src[0], '', $noscript_image );

				if ( preg_match( '/\ssrc="[^"]*"/', $noscript_image, $placeholder_src ) ) {
					$noscript_image = str_replace( $placeholder_src[0], ' src="' . esc_attr( $full_src[1] ) . '"', $noscript_image );
				}
			}

			$output .= '<noscript>' . $noscript_image . '</noscript>';

			$lazyloadImage = new Tp_LazyLoad_Images();
			if ( 'blur-img' != $lazy_type ) {
				$get_image = $lazyloadImage->tp_change_attribute_form_images( $get_image, 'src', 'data-src' );
			}

			if ( $check_srcset ) {
				$get_image = $lazyloadImage->tp_change_attribute_form_images( $get_image, 'srcset', 'data-srcset' );
			}

			/**
			 * An image without `src` has no natural size, and in some layouts (Post Featured
			 * Image's flex link) `max-width` then does not hold it: it rendered at its width
			 * attribute and shrank when the file loaded. A transparent SVG of the same size
			 * gives it the final box from the start.
			 *
			 * @since 6.6.1
			 */
			if ( 'blur-img' !== $lazy_type && ! preg_match( '/\ssrc="/', $get_image )
				&& preg_match( '/\swidth="(\d+)"/', $get_image, $img_width ) && preg_match( '/\sheight="(\d+)"/', $get_image, $img_height ) ) {

				$size_svg  = sprintf( '<svg xmlns="http://www.w3.org/2000/svg" width="%1$d" height="%2$d" viewBox="0 0 %1$d %2$d"></svg>', $img_width[1], $img_height[1] );
				$get_image = preg_replace( '/^<img\s/', '<img src="data:image/svg+xml;base64,' . base64_encode( $size_svg ) . '" ', $get_image, 1 );
			}
		}

		$output = $get_image . $output;

		return $output;
	}
}

if ( ! function_exists( 'tp_lazyload_above_the_fold' ) ) {
	/**
	 * Load the images WordPress treats as above the fold right away.
	 *
	 * Lazy loading the first images delays the largest paint: the browser cannot start
	 * them until the lazy load script runs, and the `fetchpriority="high"` on the hero
	 * image has no `src` to act on. WordPress, or Elementor's Optimized Image Loading
	 * when it replaces WordPress' logic, leaves `loading="lazy"` off the images it
	 * expects in the first viewport, so a `tp-lazyload` image without it gets
	 * its real `src` and `srcset` back. It keeps its classes, marked `tp-lazy-loaded`
	 * so the lazy load CSS shows it and hides its loader, and `data-ll-status` so the
	 * lazy load script leaves it alone.
	 *
	 * This runs on the page content on every request, after Elementor's element
	 * cache, so a cached widget still gets the right treatment for its position.
	 *
	 * @since 6.6.1
	 *
	 * @param string $filtered_image The image tag.
	 * @param string $context        The filter context.
	 */
	function tp_lazyload_above_the_fold( $filtered_image, $context ) {
		if ( false === strpos( $filtered_image, 'tp-lazyload' ) || false !== strpos( $filtered_image, 'data-ll-status' ) ) {
			return $filtered_image;
		}

		// Elementor's Optimized Image Loading turns WordPress' lazy loading off and decides itself.
		$position_known = wp_lazy_loading_enabled( 'img', $context )
			|| ( class_exists( '\Elementor\Modules\ImageLoadingOptimization\Module' ) && '1' === get_option( 'elementor_optimized_image_loading', '1' ) );

		// Without either of them, or without the size they decide on, there is no position to go by.
		if ( ! $position_known || ! preg_match( '/\swidth="\d/', $filtered_image ) || ! preg_match( '/\sheight="\d/', $filtered_image ) ) {
			return $filtered_image;
		}

		if ( preg_match( '/\sloading="lazy"/', $filtered_image ) ) {
			// The blur-img placeholder is inline, so deferring it only leaves an empty box.
			if ( preg_match( '/\ssrc="data:/', $filtered_image ) ) {
				return preg_replace( '/\sloading="lazy"/', '', $filtered_image, 1 );
			}

			return $filtered_image;
		}

		if ( ! preg_match( '/\sdata-src="([^"]*)"/', $filtered_image, $full_src ) ) {
			return $filtered_image;
		}

		$image = str_replace( $full_src[0], '', $filtered_image );

		// blur-img keeps a placeholder in `src`.
		if ( preg_match( '/\ssrc="[^"]*"/', $image, $placeholder_src ) ) {
			$image = str_replace( $placeholder_src[0], '', $image );
		}

		$image = str_replace( ' data-srcset="', ' srcset="', $image );
		$image = preg_replace( '/\sclass="([^"]*)\btp-lazyload\b/', ' class="$1tp-lazyload tp-lazy-loaded', $image, 1 );

		if ( 0 !== strpos( $image, '<img ' ) ) {
			return $filtered_image;
		}

		return '<img src="' . esc_attr( $full_src[1] ) . '" data-ll-status="loaded" ' . substr( $image, 5 );
	}

	// After Elementor's Optimized Image Loading, which adds its attributes on this filter at 10.
	add_filter( 'wp_content_img_tag', 'tp_lazyload_above_the_fold', 20, 2 );
}

if ( ! function_exists( 'tp_lazyload_noscript_style' ) ) {
	/**
	 * With JavaScript off, a lazy image never gets its `src` and renders as an empty box
	 * next to its `<noscript>` copy, and its loader never stops. Hide both there.
	 *
	 * @since 6.6.1
	 */
	function tp_lazyload_noscript_style() {
		if ( tp_has_lazyload() ) {
			echo '<noscript><style>img.tp-lazyload,img.tp-lazyload+[class^="tp-loader-"]{display:none!important}</style></noscript>' . "\n";
		}
	}

	add_action( 'wp_head', 'tp_lazyload_noscript_style' );
}

function tp_bg_lazyLoad( $option = array(), $option2 = array() ) {

	if ( tp_has_lazyload() && isset( $option ) && ! empty( $option ) && isset( $option['url'] ) && ! empty( $option['url'] ) ) {
		return ' lazy-background';
	}

	if ( tp_has_lazyload() && isset( $option2 ) && ! empty( $option2 ) && isset( $option2['url'] ) && ! empty( $option2['url'] ) ) {
		return ' lazy-background';
	}

	return '';
}
