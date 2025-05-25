<?php
/**
 * Plugin Name: JWT API Plugin
 * Description: Provides JWT authentication endpoints for headless Vue frontend.
 * Version: 1.0
 * Author: Stefan Minev
 */

add_action('rest_api_init', function () {
    register_rest_route('jwt-api/v1', '/login', [
        'methods' => 'POST',
        'callback' => 'jwt_api_login',
        'permission_callback' => '__return_true'
    ]);

    register_rest_route('jwt-api/v1', '/posts', [
        'methods' => 'GET',
        'callback' => 'jwt_api_get_posts',
        'permission_callback' => 'jwt_api_authenticate'
    ]);

    register_rest_route('jwt-api/v1', '/posts/(?P<id>\d+)', [
        'methods' => 'GET',
        'callback' => 'jwt_api_get_single_post',
        'permission_callback' => 'jwt_api_authenticate'
    ]);

    register_rest_route('jwt-api/v1', '/posts/(?P<id>\d+)', [
        'methods' => 'DELETE',
        'callback' => 'jwt_api_delete_post',
        'permission_callback' => 'jwt_api_authenticate'
    ]);
});

function jwt_api_login($request) {
    $creds = $request->get_json_params();
    $user = wp_authenticate($creds['username'], $creds['password']);

    if (is_wp_error($user)) {
        return new WP_Error('invalid_credentials', 'Invalid username or password', ['status' => 403]);
    }

    $token = bin2hex(random_bytes(32));
    update_user_meta($user->ID, '_jwt_token', $token);
    update_user_meta($user->ID, '_jwt_token_expiration', time() + 3600); // 1 hour expiry

    return [
    'token' => $token,
    'role' => $user->roles[0] ?? 'subscriber'
	];

}

function jwt_api_authenticate($request) {
    $auth = $request->get_header('authorization');

    if (!$auth || !preg_match('/Bearer\s(\S+)/', $auth, $matches)) {
        return new WP_Error('unauthorized', 'Missing or malformed token', ['status' => 401]);
    }

    $token = $matches[1];

    $users = get_users([
        'meta_key' => '_jwt_token',
        'meta_value' => $token,
        'number' => 1,
        'count_total' => false,
        'fields' => ['ID']
    ]);

    if (empty($users)) {
        return new WP_Error('unauthorized', 'Invalid token', ['status' => 401]);
    }

    $user_id = $users[0]->ID;
    $expires = get_user_meta($user_id, '_jwt_token_expiration', true);

    if (time() > (int)$expires) {
        delete_user_meta($user_id, '_jwt_token');
        delete_user_meta($user_id, '_jwt_token_expiration');
        return new WP_Error('unauthorized', 'Token expired', ['status' => 401]);
    }

    wp_set_current_user($user_id);
    return true;
}

function jwt_api_get_posts($request) {
    $posts = get_posts([
        'post_type' => 'post',
        'post_status' => 'publish',
        'numberposts' => -1
    ]);

    return array_map(function ($post) {
        return [
            'id' => $post->ID,
            'title' => get_the_title($post),
            'excerpt' => get_the_excerpt($post),
            'content' => apply_filters('the_content', $post->post_content),
            'date' => get_the_date('c', $post),
            'author' => get_the_author_meta('display_name', $post->post_author),
            'thumbnail' => get_the_post_thumbnail_url($post, 'medium')
        ];
    }, $posts);
}

function jwt_api_get_single_post($request) {
    $id = $request['id'];
    $post = get_post($id);

    if (!$post || $post->post_type !== 'post') {
        return new WP_Error('not_found', 'Post not found', ['status' => 404]);
    }

    return [
        'id' => $post->ID,
        'title' => get_the_title($post),
        'excerpt' => get_the_excerpt($post),
        'content' => apply_filters('the_content', $post->post_content),
        'date' => get_the_date('c', $post),
        'author' => get_the_author_meta('display_name', $post->post_author),
        'thumbnail' => get_the_post_thumbnail_url($post, 'large')
    ];
}

function jwt_api_delete_post($request) {
    $id = $request['id'];
    $post = get_post($id);

    if (!$post || $post->post_type !== 'post') {
        return new WP_Error('not_found', 'Post not found', ['status' => 404]);
    }

    if (!current_user_can('delete_post', $id)) {
        return new WP_Error('forbidden', 'You are not allowed to delete this post', ['status' => 403]);
    }

    wp_delete_post($id, true);
    return ['success' => true, 'message' => 'Post deleted'];
}
