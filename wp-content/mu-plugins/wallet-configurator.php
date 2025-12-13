<?php
/**
 * Wallet configurator server-side handling.
 */

if (!defined('ABSPATH')) {
    exit;
}


// Bootscore replaces WooCommerce's POST add-to-cart handler with an AJAX endpoint.
// To avoid double-processing or fighting the theme, we rely on that AJAX flow from JS
// instead of re-attaching the core handler here.



const WALLET_CONFIGURATOR_PRICING = [
    'base' => 185,
    'ostrich_premium' => 20,
    'lining_all_premium' => 20,
];

function wallet_configurator_allowed_options() {
    $leather_collections = [
        'buttero' => [
            'label' => 'Buttero',
            'swatches' => [
                ['label' => 'Dark Brown', 'color' => '#8b5a2b'],
                ['label' => 'Tan', 'color' => '#c28e5a'],
                ['label' => 'Chocolate', 'color' => '#3b2a1a'],
                ['label' => 'Black', 'color' => '#000000'],
                ['label' => 'Oxblood', 'color' => '#4a2f2f'],
                ['label' => 'Walnut', 'color' => '#6b4f3a'],
                ['label' => 'Sand', 'color' => '#d2a679'],
                ['label' => 'Navy', 'color' => '#3a4f6b'],
                ['label' => 'Olive', 'color' => '#374331'],
                ['label' => 'Burgundy', 'color' => '#7b3f61'],
                ['label' => 'Yellow', 'color' => '#ffdd33'],
                ['label' => 'Pink', 'color' => '#ffc0cb'],
                ['label' => 'Blue', 'color' => '#102f6b'],
                ['label' => 'Green', 'color' => '#2f5a32'],
                ['label' => 'Orange', 'color' => '#f4801f'],
                ['label' => 'Foresta', 'color' => '#37523d'],
            ],
        ],
        'badalassi' => [
            'label' => 'Badalassi Carlo Wax',
            'swatches' => [
                ['label' => 'Cognac', 'color' => '#b36a3c'],
                ['label' => 'Grigio', 'color' => '#7b7b7b'],
                ['label' => 'Napoli', 'color' => '#d8a23f'],
                ['label' => 'Oliva', 'color' => '#566d3b'],
                ['label' => 'Olmo', 'color' => '#8b5a2b'],
                ['label' => 'Ortensia', 'color' => '#2f4f9f'],
                ['label' => 'Papavero', 'color' => '#b22222'],
                ['label' => 'Prugna', 'color' => '#70304a'],
                ['label' => 'Tabacco', 'color' => '#7b4b26'],
            ],
        ],
    ];

    $ostrich_swatches = [
        '#2f3983' => 'Blue Ostrich',
        '#955f3a' => 'Dark Brown Ostrich',
        '#645e5c' => 'Dark Grey Ostrich',
        '#77854d' => 'Green Ostrich',
        '#b3693c' => 'Light Brown Ostrich',
        '#6e7170' => 'Light Grey Ostrich',
        '#c05a27' => 'Medium Brown Ostrich',
        '#c34d30' => 'Red Brown Ostrich',
        '#e1980b' => 'Yellow Ostrich',
    ];

    $stitching_colors = [
        '#ffffff' => 'White',
        '#f5e6c8' => 'Cream',
        '#d2a679' => 'Beige',
        '#000000' => 'Black',
        '#8b5a2b' => 'Brown',
        '#c28e5a' => 'Tan',
        '#ffdd33' => 'Yellow',
        '#3a4f6b' => 'Navy',
        '#7b3f61' => 'Burgundy',
        '#374331' => 'Olive',
    ];

    $lining_leathers = [
        '#0c0c0c' => 'Lambskin Black',
        '#2c5ea5' => 'Lambskin Blue',
        '#1e2f4f' => 'Lambskin Navy',
        '#5a3a24' => 'Lambskin Dark Brown',
        '#b27a4f' => 'Lambskin Light Brown',
        '#5b1b1f' => 'Lambskin Oxblood',
        '#b0202f' => 'Lambskin Red',
        '#d86f1f' => 'Lambskin Orange',
        '#d8b023' => 'Lambskin Yellow',
        '#1f3f2f' => 'Lambskin Dark Green',
        '#2f6b3c' => 'Lambskin Green',
    ];

    $edge_paint_colours = [
        '#000000' => 'Black',
        '#ffffff' => 'White',
        '#f5e6c8' => 'Cream',
        '#c28e5a' => 'Tan',
        '#8b5a2b' => 'Dark Brown',
        '#3b2a1a' => 'Chocolate',
        '#6b4f3a' => 'Walnut',
        '#d2a679' => 'Sand',
        '#3a4f6b' => 'Navy',
        '#7b3f61' => 'Burgundy',
        '#374331' => 'Olive',
        '#2f5a32' => 'Forest Green',
        '#1f4aa5' => 'Royal Blue',
        '#b22222' => 'Red',
        '#f4801f' => 'Orange',
        '#ffdd33' => 'Yellow',
        '#ffc0cb' => 'Pink',
        '#6a0dad' => 'Purple',
        '#7b7b7b' => 'Grey',
        '#2f8886' => 'Teal',
    ];

    $metal_corners = [
        'none' => 'No metal corners',
        'brass' => 'Brass',
        'silver' => 'Silver',
        'gunmetal-grey' => 'Gunmetal Grey',
        'black' => 'Black',
        'gold' => 'Gold',
        'rose-gold' => 'Rose Gold',
    ];

    return [
        'leather_collections' => $leather_collections,
        'ostrich_swatches' => $ostrich_swatches,
        'stitching_colors' => $stitching_colors,
        'lining_leathers' => $lining_leathers,
        'lining_coverage' => [
            'body' => 'Add lining to the main body',
            'all' => 'Use a liner for all the pockets and the main body',
        ],
        'debossing' => [
            'no' => 'No debossing',
            'atelier' => 'Deboss Atelier D. James into the wallet',
        ],
        'edge_styles' => [
            'burnished' => 'Burnished',
            'painted' => 'Painted',
        ],
        'edge_paint_choice' => [
            'lining' => 'Same colour as lining leather',
            'custom' => 'Pick a colour',
        ],
        'edge_paint_colours' => $edge_paint_colours,
        'metal_corners' => $metal_corners,
    ];
}

function wallet_configurator_upload_reference_photo($file) {
    if (empty($file) || !is_array($file) || empty($file['tmp_name'])) {
        return null;
    }

    if (!empty($file['error'])) {
        return null;
    }

    require_once ABSPATH . 'wp-admin/includes/file.php';

    $overrides = [
        'test_form' => false,
        'mimes' => [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
        ],
    ];

    $uploaded = wp_handle_upload($file, $overrides);

    if (!empty($uploaded['error'])) {
        return null;
    }

    return $uploaded;
}

function wallet_configurator_validate_choice($value, $allowed, $default) {
    $clean = sanitize_key($value);
    return in_array($clean, $allowed, true) ? $clean : $default;
}

function wallet_configurator_validate_swatch($value, $allowed_swatches, $default) {
    $clean = sanitize_text_field($value);
    if (is_array($allowed_swatches)) {
        if (array_key_exists($clean, $allowed_swatches)) {
            return $clean;
        }
        foreach ($allowed_swatches as $swatch) {
            if (is_array($swatch) && isset($swatch['color']) && $swatch['color'] === $clean) {
                return $clean;
            }
        }
    }

    return $default;
}

function wallet_configurator_normalize_options($raw_options) {
    if (!is_array($raw_options)) {
        return null;
    }

    $allowed = wallet_configurator_allowed_options();
    $collection = wallet_configurator_validate_choice(
        $raw_options['leather_collection'] ?? '',
        array_keys($allowed['leather_collections']),
        array_key_first($allowed['leather_collections'])
    );

    $collection_swatches = $allowed['leather_collections'][$collection]['swatches'];
    $default_collection_colour = $collection_swatches[0]['color'];
    $bottom_ostrich = !empty($raw_options['bottom_ostrich']);

    $normalized = [
        'leather_collection' => $collection,
        'outer_leather' => wallet_configurator_validate_swatch($raw_options['outer_leather'] ?? '', $collection_swatches, $default_collection_colour),
        'top_pocket' => wallet_configurator_validate_swatch($raw_options['top_pocket'] ?? '', $collection_swatches, $default_collection_colour),
        'bottom_ostrich' => $bottom_ostrich,
    ];

    $bottom_swatches = $bottom_ostrich ? $allowed['ostrich_swatches'] : $collection_swatches;
    $bottom_default = $bottom_ostrich ? array_key_first($allowed['ostrich_swatches']) : $default_collection_colour;
    $normalized['bottom_pocket'] = wallet_configurator_validate_swatch(
        $raw_options['bottom_pocket'] ?? '',
        $bottom_swatches,
        $bottom_default
    );

    $stitching_allowed = $allowed['stitching_colors'];
    $stitching_default = array_key_first($stitching_allowed);
    $normalized['stitching'] = wallet_configurator_validate_swatch($raw_options['stitching'] ?? '', $stitching_allowed, $stitching_default);
    $normalized['stitching_secondary'] = wallet_configurator_validate_swatch($raw_options['stitching_secondary'] ?? '', $stitching_allowed, $stitching_default);

    $lining_allowed = $allowed['lining_leathers'];
    $lining_default = array_key_first($lining_allowed);
    $normalized['lining'] = wallet_configurator_validate_swatch($raw_options['lining'] ?? '', $lining_allowed, $lining_default);

    $normalized['lining_coverage'] = wallet_configurator_validate_choice(
        $raw_options['lining_coverage'] ?? '',
        array_keys($allowed['lining_coverage']),
        'body'
    );

    $normalized['debossing'] = wallet_configurator_validate_choice(
        $raw_options['debossing'] ?? '',
        array_keys($allowed['debossing']),
        'no'
    );

    $normalized['edge_style'] = wallet_configurator_validate_choice(
        $raw_options['edge_style'] ?? '',
        array_keys($allowed['edge_styles']),
        'burnished'
    );

    $normalized['edge_paint_choice'] = wallet_configurator_validate_choice(
        $raw_options['edge_paint_choice'] ?? '',
        array_keys($allowed['edge_paint_choice']),
        'lining'
    );

    $normalized['edge_colour'] = '';
    if ('painted' === $normalized['edge_style'] && 'custom' === $normalized['edge_paint_choice']) {
        $normalized['edge_colour'] = wallet_configurator_validate_swatch(
            $raw_options['edge_colour'] ?? '',
            $allowed['edge_paint_colours'],
            array_key_first($allowed['edge_paint_colours'])
        );
    }

    $normalized['metal_corners'] = wallet_configurator_validate_choice(
        $raw_options['metal_corners'] ?? '',
        array_keys($allowed['metal_corners']),
        'none'
    );

    $normalized['additional_notes'] = sanitize_textarea_field($raw_options['additional_notes'] ?? '');
    $normalized['reference_photo'] = '';

    return $normalized;
}

function wallet_configurator_calculate_price($options) {
    if (empty($options) || !is_array($options)) {
        return null;
    }

    $total = WALLET_CONFIGURATOR_PRICING['base'];

    if (!empty($options['bottom_ostrich'])) {
        $total += WALLET_CONFIGURATOR_PRICING['ostrich_premium'];
    }

    if (!empty($options['lining_coverage']) && 'all' === $options['lining_coverage']) {
        $total += WALLET_CONFIGURATOR_PRICING['lining_all_premium'];
    }

    return $total;
}

function wallet_configurator_lookup_label_by_color($color, $swatches) {
    if (array_key_exists($color, $swatches)) {
        return $swatches[$color];
    }

    foreach ($swatches as $swatch) {
        if (is_array($swatch) && isset($swatch['color'], $swatch['label']) && $swatch['color'] === $color) {
            return $swatch['label'];
        }
    }

    return $color;
}

function wallet_configurator_build_display_values($options) {
    $allowed = wallet_configurator_allowed_options();
    $collection_swatches = $allowed['leather_collections'][$options['leather_collection']]['swatches'] ?? [];
    $ostrich_swatches = $allowed['ostrich_swatches'];

    $display = [
        'Leather collection' => $allowed['leather_collections'][$options['leather_collection']]['label'] ?? $options['leather_collection'],
        'Outer leather' => wallet_configurator_lookup_label_by_color($options['outer_leather'], $collection_swatches),
        'Top pocket' => wallet_configurator_lookup_label_by_color($options['top_pocket'], $collection_swatches),
        'Bottom pocket' => wallet_configurator_lookup_label_by_color(
            $options['bottom_pocket'],
            !empty($options['bottom_ostrich']) ? $ostrich_swatches : $collection_swatches
        ),
        'Ostrich bottom pocket' => !empty($options['bottom_ostrich']) ? 'Yes' : 'No',
        'Stitching' => wallet_configurator_lookup_label_by_color($options['stitching'], $allowed['stitching_colors']),
        'Secondary stitching' => wallet_configurator_lookup_label_by_color($options['stitching_secondary'], $allowed['stitching_colors']),
        'Lining' => wallet_configurator_lookup_label_by_color($options['lining'], $allowed['lining_leathers']),
        'Lining coverage' => $allowed['lining_coverage'][$options['lining_coverage']] ?? $options['lining_coverage'],
        'Debossing' => $allowed['debossing'][$options['debossing']] ?? $options['debossing'],
        'Edge style' => $allowed['edge_styles'][$options['edge_style']] ?? $options['edge_style'],
    ];

    if ('painted' === $options['edge_style']) {
        $edge_value = 'Matches lining leather';
        if ('custom' === $options['edge_paint_choice']) {
            $edge_value = wallet_configurator_lookup_label_by_color($options['edge_colour'], $allowed['edge_paint_colours']);
        }
        $display['Edge colour'] = $edge_value;
    }

    $display['Metal corners'] = $allowed['metal_corners'][$options['metal_corners']] ?? $options['metal_corners'];

    if (!empty($options['additional_notes'])) {
        $display['Additional notes'] = $options['additional_notes'];
    }

    if (!empty($options['reference_photo'])) {
        $display['Reference photo'] = $options['reference_photo'];
    }

    return $display;
}

add_filter('woocommerce_add_cart_item_data', function ($cart_item_data, $product_id, $variation_id) {
    if (empty($_POST['wallet_options_json'])) {
        return $cart_item_data;
    }

    $nonce = $_POST['wallet_configurator_nonce'] ?? '';
    if (empty($nonce) || !wp_verify_nonce(sanitize_text_field(wp_unslash($nonce)), 'wallet_configurator_add_to_cart')) {
        wc_add_notice(__('We could not verify your wallet configuration. Please try again.', 'wallet-configurator'), 'error');
        return $cart_item_data;
    }

    $raw_json = wp_unslash($_POST['wallet_options_json']);
    $decoded = json_decode($raw_json, true);
    $normalized = wallet_configurator_normalize_options($decoded);

    $reference_upload = wallet_configurator_upload_reference_photo($_FILES['reference_photo'] ?? null);
    if (!empty($reference_upload['url'])) {
        $normalized['reference_photo'] = esc_url_raw($reference_upload['url']);
    }

    if (empty($normalized)) {
        wc_add_notice(__('Your wallet configuration could not be processed. Please try again.', 'wallet-configurator'), 'error');
        return $cart_item_data;
    }

    $cart_item_data['wallet_options'] = [
        'raw' => $normalized,
        'display' => wallet_configurator_build_display_values($normalized),
    ];
    $cart_item_data['wallet_unique_key'] = md5(wp_json_encode($normalized));
    $cart_item_data['unique_key'] = $cart_item_data['wallet_unique_key'];

    return $cart_item_data;
}, 10, 3);

add_action('woocommerce_before_calculate_totals', function ($cart) {
    if (is_admin() && !defined('DOING_AJAX')) {
        return;
    }

    foreach ($cart->get_cart() as $cart_item) {
        if (empty($cart_item['wallet_options']['raw'])) {
            continue;
        }

        $price = wallet_configurator_calculate_price($cart_item['wallet_options']['raw']);
        if (null !== $price) {
            $cart_item['data']->set_price((float) $price);
        }
    }
});

add_filter('woocommerce_get_item_data', function ($item_data, $cart_item) {
    if (empty($cart_item['wallet_options']['display']) || !is_array($cart_item['wallet_options']['display'])) {
        return $item_data;
    }

    foreach ($cart_item['wallet_options']['display'] as $label => $value) {
        $item_data[] = [
            'name' => wc_clean(wp_kses_post($label)),
            'value' => wc_clean(wp_kses_post($value)),
            'display' => wc_clean(wp_kses_post($value)),
        ];
    }

    return $item_data;
}, 10, 2);

add_action('woocommerce_checkout_create_order_line_item', function ($item, $cart_item_key, $values, $order) {
    if (empty($values['wallet_options']) || empty($values['wallet_options']['raw'])) {
        return;
    }

    $raw_options = $values['wallet_options']['raw'];
    $display_options = $values['wallet_options']['display'] ?? wallet_configurator_build_display_values($raw_options);

    $item->add_meta_data('_wallet_config_options', $raw_options, true);

    foreach ($display_options as $label => $value) {
        $item->add_meta_data($label, $value, true);
    }
});
