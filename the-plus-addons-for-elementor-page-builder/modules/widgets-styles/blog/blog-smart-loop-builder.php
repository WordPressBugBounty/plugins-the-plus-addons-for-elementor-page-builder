<?php

$template = !empty( $settings['content_html'] ) ? $settings['content_html'] : '';
$template = str_replace('{{tpae_title}}', get_the_title(), $template);
$template = str_replace('{{tpae_excerpt}}', get_the_excerpt(), $template);
$template = str_replace('{{tpae_description}}', get_the_content(), $template);
$template = str_replace('{{tpae_image}}', get_the_post_thumbnail(), $template);


$template = str_replace('{{tpae_permalink}}', get_permalink(), $template);
$template = str_replace('{{tpae_image_url}}', get_the_post_thumbnail_url(), $template);
$template = str_replace('{{tpae_auther_meta}}', get_the_author_meta('display_name'), $template);
$template = str_replace('{{tpae_auther_logo}}', get_custom_logo(), $template);

$f_created_date = get_the_date('Y-m-d');
$f_created_time = get_the_date('H:i:s');
$template = str_replace('{{tpae_date_created}}', $f_created_date, $template);
$template = str_replace('{{tpae_time_created}}', $f_created_time, $template);

echo $template;