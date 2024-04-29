<?php
/**
 * PHP file to use when rendering the block type on the server to show on the front end.
 *
 * The following variables are exposed to the file:
 *     $attributes (array): The block attributes.
 *     $content (string): The block default content.
 *     $block (WP_Block): The block instance.
 *
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

// Generate unique id for aria-controls.
$unique_id          = wp_unique_id('p-');
$wrapper_attributes = get_block_wrapper_attributes();

?>

<div 
    data-wp-interactive="reading-mode"
    data-wp-class--isDark="state.isDarkMode"
    >
    <div
        <?php echo get_block_wrapper_attributes(); ?>
        data-wp-interactive="notification"
        data-wp-context='{ "isOpen": false }'
        data-wp-watch="callbacks.logIsOpen"
    >
        <div class="teaser">
            <div>
                <p><?php echo esc_html(__('Notification Block', 'bigbite')); ?></p>
            </div>
            <button
                data-wp-on--click="actions.toggle"
                data-wp-bind--aria-expanded="context.isOpen"
                data-wp-class--isOpen="context.isOpen"
                aria-controls="<?php echo esc_attr($unique_id); ?>"
            >
            <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M7 10L12 15L17 10" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            </button>
        </div>

        <div
                id="<?php echo esc_attr($unique_id); ?>"
                data-wp-bind--hidden="!context.isOpen"
                class="content"
            >
                <?php
                    echo wp_kses_post(html_entity_decode($content));
                ?>
            </div>
    </div>
</div>
