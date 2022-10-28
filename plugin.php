<?php

/**
 * Plugin Name: Admin Notifications
 * Plugin URI: https://github.com/imPBH/project_challenge_php
 * Description: Send information to a webhook when a new comment or a new post is posted.
 * Version: 0.1
 * Author: Alexis Provo
 * Author URI: https://github.com/imPBH
 **/

require "Sender/Sender.php";
require "Services/IService.php";
require "Services/Discord.php";
require "Services/Slack.php";
require "Services/Telegram.php";

class AdminNotificationsPlugin
{
    private $sender;

    public function __construct()
    {
        $this->sender = new Sender();
        // Hook into the admin menu
        add_action('admin_menu', array($this, 'create_plugin_settings_page'));
        add_action('admin_init', array($this, 'setup_sections'));
        add_action('admin_init', array($this, 'setup_fields'));
        add_action('comment_post', array($this->sender, 'NewComment'), 10, 1);
        add_action('transition_comment_status', array($this->sender, 'CommentStatusUpdate'), 10, 3);
    }

    public function create_plugin_settings_page()
    {
        // Add the menu item and page
        $page_title = 'Settings Page';
        $menu_title = 'Admin Notifications Plugin';
        $capability = 'manage_options';
        $slug = 'webhook_fields';
        $callback = array($this, 'plugin_settings_page_content');
        $icon = 'dashicons-admin-plugins';
        $position = 100;

        add_submenu_page('options-general.php', $page_title, $menu_title, $capability, $slug, $callback);
    }

    public function plugin_settings_page_content()
    { ?>
        <div class="wrap">
            <h2>Webhook Settings Page</h2>
            <form method="post" action="options.php">
                <?php
                settings_fields('webhook_fields');
                do_settings_sections('webhook_fields');
                submit_button();
                ?>
            </form>
        </div> <?php
    }

    public function setup_sections()
    {
        add_settings_section('discord_section', 'Discord', array($this, 'section_callback'), 'webhook_fields');
        add_settings_section('telegram_section', 'Telegram', array($this, 'section_callback'), 'webhook_fields');
        add_settings_section('slack_section', 'Slack', array($this, 'section_callback'), 'webhook_fields');
    }

    public function section_callback($arguments)
    {
        switch ($arguments['id']) {
            case 'discord_section':
                echo 'Discord settings';
                break;
            case 'telegram_section':
                echo 'Telegram settings';
                break;
            case 'slack_section':
                echo 'Slack settings';
                break;
        }
    }

    public function setup_fields()
    {
        $fields = array(
            array(
                'uid' => 'discord_url',
                'label' => 'Discord Webhook URL',
                'section' => 'discord_section',
                'type' => 'text',
                'options' => false,
                'placeholder' => 'https://discord.com/api/webhooks/...',
            ),
            array(
                'uid' => 'telegram_bot_key',
                'label' => 'Telegram Bot Key',
                'section' => 'telegram_section',
                'type' => 'text',
                'options' => false,
                'placeholder' => '123456789...',
            ),
            array(
                'uid' => 'telegram_channel_id',
                'label' => 'Telegram Channel ID',
                'section' => 'telegram_section',
                'type' => 'text',
                'options' => false,
                'placeholder' => '123456789...',
            ),
            array(
                'uid' => 'slack_url',
                'label' => 'Slack Webhook URL',
                'section' => 'slack_section',
                'type' => 'text',
                'options' => false,
                'placeholder' => 'https://hooks.slack.com/services/...',
            )
        );
        foreach ($fields as $field) {
            add_settings_field($field['uid'], $field['label'], array($this, 'field_callback'), 'webhook_fields', $field['section'], $field);
            register_setting('webhook_fields', $field['uid']);
        }
    }

    public function field_callback($arguments)
    {
        $value = get_option($arguments['uid']); // Get the current value, if there is one
        if (!$value) { // If no value exists
            $value = $arguments['default']; // Set to our default
        }

        printf('<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />', $arguments['uid'], $arguments['type'], $arguments['placeholder'], $value);
    }
}

new AdminNotificationsPlugin();
?>