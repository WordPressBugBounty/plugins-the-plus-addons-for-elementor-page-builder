<?php

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

wp_register_ability('tpae/tpae-webgl', [
    'label'       => __('WebGL', 'tpebl'),
    'description' => __('Adds the The Plus "WebGL" widget (tp-webgl) to an Elementor container.', 'tpebl'),
    'category'    => 'tpae',
    'input_schema' => [
        'type'       => 'object',
        'properties' => [
            'post_id'   => ['type' => 'integer', 'description' => 'Elementor page/post ID'],
            'parent_id' => ['type' => 'string',  'description' => 'Target Elementor container ID'],
            'position'  => ['type' => 'integer', 'description' => 'Insert position. Use -1 to append.', 'default' => -1],
            'enable_all_continer' => [
                'type'        => 'string',
                'enum'        => ['yes', 'no'],
                'description' => 'Enable WebGL effect on the entire page',
            ],
            'effect_type' => [
                'type'        => 'string',
                'enum'        => ['pixel', 'liquid', 'particle', 'explode'],
                'description' => 'WebGL visual effect type: pixel distortion, liquid warp, particle field, or explosion reveal',
            ],
            'pixel_size' => [
                'type'        => 'integer',
                'description' => 'Pixel distortion block size (5–150, default 40)',
            ],
            'liquid_intensity' => [
                'type'        => 'number',
                'description' => 'Liquid warp intensity (0–1, default 0.35)',
            ],
            'particle_count' => [
                'type'        => 'integer',
                'description' => 'Number of particles for particle effect (100–3000, default 1200)',
            ],
            'particle_size' => [
                'type'        => 'integer',
                'description' => 'Particle size in pixels (1–10, default 2)',
            ],
            'interaction_type' => [
                'type'        => 'string',
                'enum'        => ['mouse', 'scroll'],
                'description' => 'Effect trigger: mouse hover or scroll-based (GSAP)',
            ],
                    'settings' => ['type' => 'object', 'description' => 'Raw Elementor/The Plus control settings to merge into the widget at creation time. Use control keys from sprout/get-theplus-widget-schema.'],
        ],
        'required' => ['post_id', 'parent_id', 'effect_type'],
        'additionalProperties' => false,
    ],
    'output_schema' => [
        'type'       => 'object',
        'properties' => [
            'element_id'  => ['type' => 'string'],
            'widget_type' => ['type' => 'string'],
            'post_id'     => ['type' => 'integer'],
        ],
    ],
    'execute_callback'    => 'tpae_mcp_add_theplus_webgl_ability',
    'permission_callback' => 'tpae_mcp_add_theplus_webgl_permission',
    'meta' => [
        'show_in_rest' => true,
        'mcp'          => ['public' => true, 'type' => 'tool'],
    ],
]);

function tpae_mcp_add_theplus_webgl_permission(?array $input = null): bool
{
    if (!current_user_can('edit_posts')) {
        return false;
    }

    $post_id = absint($input['post_id'] ?? 0);
    if ($post_id > 0 && !current_user_can('edit_post', $post_id)) {
        return false;
    }

    return true;
}

function tpae_mcp_add_theplus_webgl_ability(array $input)
{
    if (!tpae_mcp_has_elementor()) {
        return new WP_Error('elementor_missing', __('Elementor must be active.', 'tpebl'));
    }

    $widget_type = 'tp-webgl';
    if (!tpae_mcp_has_registered_widget($widget_type)) {
        return new WP_Error('widget_missing', __('The Plus WebGL widget is not registered on this site.', 'tpebl'));
    }

    $post_id   = absint($input['post_id'] ?? 0);
    $parent_id = sanitize_text_field((string) ($input['parent_id'] ?? ''));
    $position  = intval($input['position'] ?? -1);

    if ($post_id <= 0 || $parent_id === '') {
        return new WP_Error('missing_params', __('post_id and parent_id are required.', 'tpebl'));
    }

    if (empty($input['effect_type'])) {
        return new WP_Error('missing_effect_type', __('effect_type is required.', 'tpebl'));
    }

    $post = get_post($post_id);
    if (!$post instanceof WP_Post) {
        return new WP_Error('invalid_post', __('Target post was not found.', 'tpebl'));
    }

    $page_data = tpae_mcp_get_elementor_page_data($post_id);
    if (is_wp_error($page_data)) {
        return $page_data;
    }

    $settings = [];

    if (!empty($input['enable_all_continer'])) {
        $settings['enable_all_continer'] = sanitize_key((string) $input['enable_all_continer']);
    }

    if (!empty($input['effect_type'])) {
        $settings['effect_type'] = sanitize_key((string) $input['effect_type']);
    }

    if (isset($input['pixel_size'])) {
        $settings['pixel_size'] = ['size' => max(5, min(150, intval($input['pixel_size']))), 'unit' => 'px'];
    }

    if (isset($input['liquid_intensity'])) {
        $settings['liquid_intensity'] = ['size' => max(0.0, min(1.0, (float) $input['liquid_intensity'])), 'unit' => 'px'];
    }

    if (isset($input['particle_count'])) {
        $settings['particle_count'] = ['size' => max(100, min(3000, intval($input['particle_count']))), 'unit' => 'px'];
    }

    if (isset($input['particle_size'])) {
        $settings['particle_size'] = ['size' => max(1, min(10, intval($input['particle_size']))), 'unit' => 'px'];
    }

    if (!empty($input['interaction_type'])) {
        $settings['interaction_type'] = sanitize_key((string) $input['interaction_type']);
    }

    $settings = tpae_mcp_merge_widget_settings($settings, $input['settings'] ?? []);


    $widget = [
        'id'         => tpae_mcp_generate_elementor_element_id(),
        'elType'     => 'widget',
        'widgetType' => $widget_type,
        'isInner'    => false,
        'settings'   => $settings,
        'elements'   => [],
    ];

    if (!tpae_mcp_insert_elementor_element($page_data, $parent_id, $widget, $position)) {
        return new WP_Error('parent_not_found', __('Parent container not found.', 'tpebl'));
    }

    $save_result = tpae_mcp_save_elementor_page_data($post_id, $page_data);
    if (is_wp_error($save_result)) {
        return $save_result;
    }

    return [
        'element_id'  => $widget['id'],
        'widget_type' => $widget_type,
        'post_id'     => $post_id,
    ];
}
