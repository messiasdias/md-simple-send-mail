<?php
function md_simple_send_mail_options_view() {
    include(__DIR__. "/view.php") ;
}

function md_simple_send_mail_options_menu() {
    add_menu_page(
        'MD Simple Send Mail',
        'Simple Send Mail',
        'manage_options',
        'md-simple-send-mail',
        'md_simple_send_mail_options_view',
        'dashicons-email-alt',
        //100
    );

    add_submenu_page(
		'themes.php',
        'MD Simple Send Mail',
        'Simple Send Mail',
        'manage_options',
        'md-simple-send-mail-theme',
        'md_simple_send_mail_options_view',
	);
}

function  md_simple_send_mail_options_settings() {
	/*  DEFAULT CONFIG
        Servidor SMTP: smtp.gmail.com
        Usuário SMTP: seu Usuário Completo Gmail (e-mail), por exemplo: Seu_Email@gmail.com
        Senha SMTP: sua Senha Gmail.
        Porta SMTP: 587
        TLS/SSL: requeridos.
    */
	register_setting('md-simple-send-mail-group', 'smtp_host', [
		'default' => 'smtp.gmail.com',
		'type' => 'string',
		'description' => 'Servidor SMTP',
		'show_in_rest' => false
	]);
	register_setting('md-simple-send-mail-group', 'smtp_username', [
		'default' => 'seu_email@gmail.com',
		'type' => 'string',
		'description' => 'Usuário SMTP',
		'show_in_rest' => false,
		'sanitize_callback' => null,
	]);
	register_setting('md-simple-send-mail-group', 'smtp_password', [
		'default' => '*******',
		'type' => 'string',
		'description' => 'Senha SMTP',
		'show_in_rest' => false
	]);
	register_setting('md-simple-send-mail-group', 'smtp_port', [
		'default' => 587,
		'type' => 'integer',
		'description' => 'Porta SMTP',
		'show_in_rest' => false
	]);
	register_setting('md-simple-send-mail-group', 'smtp_protocol',
	[
		'default' => 'TLS',
		'type' => 'string',
		'description' => 'TLS ou SSL',
		'show_in_rest' => false
	]);
}

if (is_admin()){ 
	add_action('admin_menu', 'md_simple_send_mail_options_menu');
	add_action('admin_init', 'md_simple_send_mail_options_settings');
}