<?php
require_once(__DIR__ . '/DoloresBaseAPI.class.php');

require_once(__DIR__ . '/../posts.php');

class DoloresPostAPI extends DoloresBaseAPI {
  function post($request) {
    $post = DoloresPosts::add_new_post(
      $request['title'],
      $request['text'],
      $request['cat'],
      $request['tags']
    );

    if (is_array($post) && array_key_exists('error', $post)) {
      $this->_error($post['error']);
    }

    return $post;
  }
};