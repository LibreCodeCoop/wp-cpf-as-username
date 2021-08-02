<?php
/**
 * CPF as username
 *
 * @package   wp-cpf-as-username
 * @author    Vitor Mattos <vitor@php.rio>
 * @license   GPL-2.0+
 * @link      http://github.com/vitormattos
 * @copyright 2021 Vitor Mattos
 *
 * @wordpress-plugin
 * Plugin Name:       CPF as username
 * Plugin URI:        https://github.com/librecodecoop/wp-cpf-as-username
 * Description:       CPF as username
 * Version:           1.0.0
 * Author:            Vitor Mattos
 * Author URI:        http://github.com/vitormattos
 * Text Domain:       wp-cpf-as-username
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/librecodecoop/wp-cpf-as-username
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function wpmu_validate_user_signup_callbak(array $result = []) {
	if (!empty($result['errors']->errors['user_name'])) {
		$invalidUsernameMessage = __( 'Usernames can only contain lowercase letters (a-z) and numbers.' );
		foreach ($result['errors']->errors['user_name'] as $key => $value) {
			if ($value === $invalidUsernameMessage) {
				unset($result['errors']->errors['user_name'][$key]);
				if (empty($result['errors']->errors['user_name'])) {
					unset($result['errors']->errors['user_name']);
				}
			}
		}
	}

	$orig_username = $result['user_name'];
	$user_name = preg_replace( '/\s+/', '', sanitize_user( $result['user_name'], true ) );

	if ( $user_name != $orig_username || !preg_match( '/[0-9]{3}\.[0-9]{3}\.[0-9]{3}-[0-9]{2}/', $user_name ) ) {
		$result['errors']->add( 'user_name', __( 'Nome de usuário precisa ter uma máscara de CPF válida. Ex: 111.111.111-11.' ) );
		$user_name = $orig_username;
	}

	return $result;
}

add_action( 'wpmu_validate_user_signup', 'wpmu_validate_user_signup_callbak');