<?php
require_once(__DIR__ . '/../../vendor/autoload.php');

require_once(__DIR__ . '/../users.php');

require_once(__DIR__ . '/DoloresBaseAPI.class.php');

class DoloresSigninAPI extends DoloresBaseAPI {
  function post($request) {
    if ($request['type'] == 'facebook') {
      $user = $this->signinViaFacebook($request);
    } else if ($request['type'] == 'google') {
      $user = $this->signinViaGoogle($request);
    } else {
      $this->_error('Tipo de autenticação não suportado.');
    }

    if ($user !== null) {
      wp_set_current_user($user->ID, $user->user_login);
      wp_set_auth_cookie($user->ID);
      do_action('wp_login', $user->user_login);
      return array('action' => 'refresh');
    }

    return array('action' => 'signup', 'data' => $this->signupData);
  }

  function signinViaFacebook($request) {
    $fb = new Facebook\Facebook(array(
      'app_id' => FACEBOOK_APP_ID,
      'app_secret' => FACEBOOK_APP_SECRET,
      'default_graph_version' => 'v2.4'
    ));

    $oAuth2Client = $fb->getOAuth2Client();
    $accessToken = new Facebook\Authentication\AccessToken($request['token']);

    try {
      $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
    } catch (Facebook\Exceptions\Exception $e) {
      $this->_error('Erro na autenticação com Facebook.');
    }

    $fields = 'id,name,email,picture.type(large)';
    try {
      $response = $fb->get('/me?fields=' . $fields, $accessToken);
      $fbUser = $response->getGraphUser();
    } catch (Facebook\Exceptions\Exception $e) {
      $this->_error('Erro ao solicitar informações para o Facebook.');
    }

    $this->signupData = array(
      'name' => $fbUser['name'],
      'email' => $fbUser['email'],
      'picture' => $fbUser['picture']['url']
    );

    return DoloresUsers::getUserByFacebookID($fbUser['id']);
  }

  function signinViaGoogle($request) {
    $client = new Google_Client();
    $client->setClientId(GOOGLE_CLIENT_ID);
    $client->setClientSecret(GOOGLE_CLIENT_SECRET);
    $client->setRedirectUri('http://seacidadefossenossa.com.br');

    $client->authenticate($request['code']);

    $ticket = $client->verifyIdToken();
    if (!$ticket) {
      $this->_error('Erro na autenticação com Google.');
    }

    $data = $ticket->getAttributes();
    $googleId = $data['payload']['sub'];

    $plus = new Google_Service_Plus($client);
    $me = $plus->people->get(
      'me',
      array('fields' => 'displayName,emails/value,image/url')
    );

    $this->signupData = array(
      'name' => $me['displayName'],
      'email' => $me['emails'][0]['value'],
      'picture' => str_replace('sz=50', 'sz=300', $me['image']['url']),
    );

    return DoloresUsers::getUserByGoogleID($googleId);
  }
};
