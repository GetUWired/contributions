<?php
/**
 * Template Name: Password Protection
 *
 * Used to ask for password for password protected pages in WordPress via the Page Visibility plugin
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Page_Visibility
 */


wp_enqueue_style('password-protection-style', plugin_dir_url( __FILE__ ) . '../css/template-password-protection.css', [], '1.0.0');
get_header(); 
?>

<main class="password-protection-page">
    <section class="password-wrapper">
        <p>This page is password protected. If you have the password, please enter it below:</p>

        <form id="pv_password_protection_form" action="" method="POST" autocomplete="false">
            <label for="pv_page_password">Page Password:</label>
            <input type="password" name="pv_page_password" id="pv_page_password" />
            <input type="submit" name="pv_submit" id="pv_submit" value="Submit" />
        </form>
    </section>
</main>

<?php
get_footer();