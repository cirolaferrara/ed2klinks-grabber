<?php

namespace Ed2kLinksGrabber\Forum\Service;

use Ed2kLinksGrabber\Forum\Data\ForumData;
use Curl\Curl;

final class ForumAuth
{
    /**
     * Performs an authentication attempt.
     *
     * @param string $url
     * @param string $username
     * @param string $password
     * @return ForumData|null
     */
    public function authenticate(string $url, string $username, string $password): ?ForumData
    {
        // Get the sid
        $curl = new Curl();
        $curl->get($url);
        $sid = (string)$curl->getCookie(ForumData::SID_URL);
        $curl->close();

        // Try login
        $loginUrl = $url.'/'.ForumData::LOGIN_URL;
        $curl = new Curl();
        $curl->post($loginUrl, array(
            'username' => $username,
            'password' => $password,
            'sid' => $sid,
            'autologin' => 'on',
            'viewonline' => 'on',
            'login' => 'Login'
        ));

        $cookies = $curl->getResponseCookies();
        $curl->close();

        if(count($cookies) > 0) {
            // Success
            $parsedUrl = parse_url($url);
            $baseUrl = $parsedUrl['scheme'];
            $baseUrl .= '://';
            $baseUrl .= $parsedUrl['host'];

            // Map
            $forum = new ForumData();
            $forum->url = (string)$baseUrl;
            $forum->username = (string)$username;
            $forum->password = (string)$password;
            $forum->sid = $sid;
            $forum->cookies = $cookies;

            return $forum;
        }

        return null;
    }
}