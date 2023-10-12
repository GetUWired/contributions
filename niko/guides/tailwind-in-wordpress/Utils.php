<?php

namespace ThemeName\lib;

class Utils
{
  private array $manifest = [];

  public function __construct()
  {
    $this->manifest = $this->get_manifest();
  }

  /**
   * Enqueue a script bundled by Vite
   *
   * @param string $handle
   * @param string $path
   * @param array  $dependencies
   * @param string $version
   * @param bool   $in_footer
   */
  public function enqueue_bundled_script(string $handle, string $path, array $dependencies = [], string $version = '1.0.0', bool $in_footer = true): void
  {
    $manifest_entry = $this->manifest[$path];

    if (isset($manifest_entry)) {
      $bundled_path = $manifest_entry['file'];
    } else {
      return;
    }

    if (isset($manifest_entry['css'])) {
      $css_paths = $manifest_entry['css'];

      foreach ($css_paths as $index => $css_path) {
        $complete_css_path = THEME_NAME_THEME_URL . "/dist/$css_path";
        wp_enqueue_style("$handle-css-$index", $complete_css_path, [], $version, 'all');
      }
    }

    $complete_path = THEME_NAME_THEME_URL . "/dist/$bundled_path";

    wp_enqueue_script($handle, $complete_path, $dependencies, $version, $in_footer);
  }

  /**
   * Enqueue a style bundled by Vite
   *
   * @param  string $handle
   * @param  string $path
   * @param  array  $dependencies
   * @param  string $version
   * @param  string $media
   * @return void
   */
  public function enqueue_bundled_style(string $handle, string $path, array $dependencies = [], string $version = '1.0.0', string $media = 'all'): void
  {
    $manifest_entry = $this->manifest[$path];

    if (isset($manifest_entry)) {
      $bundled_path = $manifest_entry['file'];
    } else {
      return;
    }

    $complete_path = THEME_NAME_THEME_URL . "/dist/$bundled_path";

    wp_enqueue_style($handle, $complete_path, $dependencies, $version, $media);
  }

  /**
   * Get the Vite manifest file to access bundled assets
   *
   * @return array
   */
  public function get_manifest(): array
  {
    if (!file_exists('wp-content/themes/theme-name/dist/manifest.json')) {
      return [];
    }

    $manifest_file = file_get_contents('wp-content/themes/theme-name/dist/manifest.json');

    if (!$manifest_file) {
      return [];
    }

    return json_decode($manifest_file, true);
  }
}
