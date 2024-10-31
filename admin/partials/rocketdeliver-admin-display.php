<?php

/**
 * Provide a admin area view for the plugin
 * Forked from Cloudimage WP Plugin
 * Modified to include the RocketDeliver Dashboard and removed options for blurhash, lazy loading and responsive js
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://rocketdeliver.in
 * @since      1.0.0
 *
 * @package    RocketDeliver
 * @subpackage RocketDeliver/admin/partials
 */
?>

<?php
// Grab all options
$options = get_option($this->plugin_name);


?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div id="rocketdeliver-plugin-container">
    <div class="rocketdeliver-lower">
        <div class="rocketdeliver-box">
            <div class="content-container">
                <div class="top_part">
                    <div class="small-cloud-image">
                    </div>
                    <div class="cloud-image">
                    </div>
                    <div class="a_logo" style="max-width:800px;">
                        <a target="_blank" href="http://rocketdeliver.in/" style="text-decoration:none">
                            <h2 style="font-size: 64px;"><img src=" <?php echo plugin_dir_url(__FILE__); ?>../images/logo.png" alt="rocketdeliver logo" style="max-width: 64px; vertical-align: middle"> RocketDeliver</h2>
                        </a>
                    </div>
		    <p style="color: #fff; font-size:20px;">Delivering your page, faster</p>
                </div>


                <div class="intro_text">
                    <p class="big_p">
                        <?php esc_attr_e('RocketDeliver uses the best of compression and web technologies to ensure a fast website for every user no matter the device they use.',  'rocketdeliver') ?>
                    </p>
                        <p class="big_p">
                            <?php esc_attr_e('To start using RocketDeliver you will need to sign up for a RocketDeliver account and register your domain on the ', 'rocketdeliver'); ?>
                            <a href="https://dashboard.rocketdeliver.in/" target="_blank"><?php esc_attr_e('Rocketdeliver dashboard', 'rocketdeliver'); ?></a>
                            <?php esc_attr_e('. Sign up for the free trial, no card required, and get a faster web page in seconds.', 'rocketdeliver'); ?></p>
                        <p class="big_p">
                            <a href="https://rocketdeliver.in/" target="_blank"><?php esc_attr_e('Register for the free trial', 'rocketdeliver'); ?></a>
                        </p>
			<p class=="big_p">
				To undo all changes made by this plugin, simply deactivate and your site will function exactly as it did before. We recommend that you check javascript heavy pages are functioning well after activating the plugin.
			</p>
                </div>
            </div>
        </div>
    </div>
</div>

