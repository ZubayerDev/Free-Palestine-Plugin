<?php
/**
 * Plugin Name: Free Palestine
 * Plugin URI: https://www.nexgrowix.com/
 * Description: Show your support for Palestine by allowing admins to choose and display Free Palestine stickers or banners on your website. Easily customizable and impactful.
 * Version: 1.0
 * Author: NexGrowix
 * Author URI: https://www.nexgrowix.com/
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */


add_action('admin_enqueue_scripts', 'iss_admin_assets');
function iss_admin_assets($hook) {
    if ($hook == 'toplevel_page_image-sticker-settings') {
        wp_enqueue_style('iss-admin-style', plugins_url('css/sticker-style.css', __FILE__));
        wp_enqueue_script('iss-admin-script', plugins_url('js/admin-script.js', __FILE__), array('jquery'), '1.0', true);
    }
}

add_action('wp_enqueue_scripts', 'iss_frontend_assets');
function iss_frontend_assets() {
    if (is_front_page()) {
        wp_enqueue_style('iss-frontend-style', plugins_url('css/frontend.css', __FILE__));
        $animation = get_option('iss_selected_animation', 'float');
        if ($animation != 'none') {
            wp_add_inline_style('iss-frontend-style', iss_get_animation_css($animation));
        }
    }
}
function iss_get_animation_css($animation) {
    $css = '';
    switch ($animation) {
        case 'float':
            $css = '.iss-sticker { animation: float 3s ease-in-out infinite; }';
            break;
        case 'pulse':
            $css = '.iss-sticker { animation: pulse 2s infinite; }';
            break;
        case 'shake':
            $css = '.iss-sticker { animation: shake 0.5s infinite; }';
            break;
        case 'spin':
            $css = '.iss-sticker:hover { animation: spin 2s linear infinite; }';
            break;
    }
    return $css;
}

add_action('admin_menu', 'iss_add_admin_menu');
function iss_add_admin_menu() {
    add_menu_page(
        'Sticker Settings',
        'Palestine Flag',
        'manage_options',
        'image-sticker-settings',
        'iss_render_admin_page',
        'dashicons-format-image',
        80
    );
}
function iss_render_admin_page() {
    if (isset($_POST['iss_save_settings'])) {
        update_option('iss_selected_sticker', sanitize_text_field($_POST['selected_sticker']));
        update_option('iss_selected_animation', sanitize_text_field($_POST['selected_animation']));
        echo '<div class="notice notice-success"><p>Settings saved!</p></div>';
    }

    $stickers = array(
        'sticker1' => array('name' => 'Sticker 1', 'path' => plugins_url('images/sticker1.png', __FILE__)),
        'sticker2' => array('name' => 'Sticker 2', 'path' => plugins_url('images/sticker2.png', __FILE__)),
        'sticker3' => array('name' => 'Sticker 3', 'path' => plugins_url('images/sticker3.png', __FILE__)),
        'sticker4' => array('name' => 'Sticker 4', 'path' => plugins_url('images/sticker4.png', __FILE__)),
        'sticker5' => array('name' => 'Sticker 5', 'path' => plugins_url('images/sticker5.png', __FILE__)),
        'sticker6' => array('name' => 'Sticker 6', 'path' => plugins_url('images/sticker6.png', __FILE__)),
        'sticker7' => array('name' => 'Sticker 7', 'path' => plugins_url('images/sticker7.png', __FILE__)),
        'sticker8' => array('name' => 'Sticker 8', 'path' => plugins_url('images/sticker8.png', __FILE__))
    );

    $animations = array(
        'none' => 'No Animation',
        'float' => 'Floating',
        'pulse' => 'Pulse',
        'shake' => 'Shake',
        'spin' => 'Spin on Hover'
    );

    $selected_sticker = get_option('iss_selected_sticker', 'sticker1');
    $selected_animation = get_option('iss_selected_animation', 'float');
    ?>
    
    <div class="wrap iss-admin-wrap">
        <h1>Palestine Flag Selector</h1>
        
        <form method="post" class="iss-settings-form">
            <div class="iss-sticker-selector">
                <h2>Select Flag</h2>
                <div class="sticker-grid">
                    <?php foreach ($stickers as $key => $sticker): ?>
                        <div class="sticker-item <?php echo $key == $selected_sticker ? 'selected' : ''; ?>" 
                             data-sticker-id="<?php echo $key; ?>">
                            <img src="<?php echo $sticker['path']; ?>" alt="<?php echo $sticker['name']; ?>">
                            <div class="checkmark">✓</div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <input type="hidden" name="selected_sticker" id="selected_sticker" value="<?php echo $selected_sticker; ?>">
            </div>

            <div class="iss-animation-selector">
                <h2>Select Animation</h2>
                <select name="selected_animation" id="selected_animation">
                    <?php foreach ($animations as $key => $name): ?>
                        <option value="<?php echo $key; ?>" <?php selected($selected_animation, $key); ?>>
                            <?php echo $name; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <?php submit_button('Save Change', 'primary', 'iss_save_settings'); ?>
        </form>
    </div>
    <?php
}


add_action('wp_footer', 'iss_display_sticker');
function iss_display_sticker() {
    if (!is_front_page()) return;

    $selected_sticker = get_option('iss_selected_sticker');
    $selected_animation = get_option('iss_selected_animation', 'float');
    
    if (empty($selected_sticker)) return;

    $sticker_images = array(
    'sticker1' => plugins_url('images/sticker1.png', __FILE__),
    'sticker2' => plugins_url('images/sticker2.png', __FILE__),
    'sticker3' => plugins_url('images/sticker3.png', __FILE__),
    'sticker4' => plugins_url('images/sticker4.png', __FILE__),
    'sticker5' => plugins_url('images/sticker5.png', __FILE__),
    'sticker6' => plugins_url('images/sticker6.png', __FILE__),
    'sticker7' => plugins_url('images/sticker7.png', __FILE__),
    'sticker8' => plugins_url('images/sticker8.png', __FILE__)
    );

    if (isset($sticker_images[$selected_sticker])) {
        echo '<div class="iss-sticker ' . esc_attr($selected_animation) . '">';
        echo '<img src="' . esc_url($sticker_images[$selected_sticker]) . '" alt="Sticker">';
        echo '</div>';
    }
}