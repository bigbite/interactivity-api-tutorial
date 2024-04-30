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
$unique_id          = wp_unique_id('tog-');
$wrapper_attributes = get_block_wrapper_attributes();
?>

<div 
    <?php echo $wrapper_attributes; ?>
    data-wp-interactive="reading-mode"
    data-wp-class--isDark="state.isDarkMode"
    >
    <div class="mode-button">
        <input class="mode-button-input" type="checkbox" id="switch<?php echo $unique_id; ?>" name="mode">
        <label data-wp-on--click="actions.setMode" for="switch<?php echo $unique_id; ?>">Toggle</label>
    </div>
</div>
