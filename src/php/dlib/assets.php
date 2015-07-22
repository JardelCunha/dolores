<?php
/**
 * This file is generated by the deploy script. We don't need it in the
 * development version.
 */
$generated_assets_path = __DIR__ . '/generated_assets.php';
if (file_exists($generated_assets_path)) {
  require_once($generated_assets_path);
}

class DoloresAssets {
  public static function get_theme_uri($file) {
    if (function_exists('dolores_assets_get_theme_uri')) {
      return dolores_assets_get_theme_uri($file);
    } else {
      return get_template_directory_uri() . '/' . $file;
    }
  }

  public static function print_style() {
    if (function_exists('dolores_assets_print_style')) {
      return dolores_assets_print_style();
    } else {
      ?>
      <link rel="stylesheet" type="text/css"
          href="<?php bloginfo('stylesheet_url'); ?>" />
      <?php
    }
  }

  public static function print_script() {
    $src = DoloresAssets::get_theme_uri('script.js');
    echo '<script async type="text/javascript" src="' . $src . '"></script>';
  }

  public static function get_static_uri($file) {
    return DoloresAssets::get_theme_uri('static/' . $file);
  }

  public static function get_image_uri($file) {
    return DoloresAssets::get_static_uri('images/' . $file);
  }
};