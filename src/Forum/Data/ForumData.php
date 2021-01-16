<?php


namespace Ed2kLinksGrabber\Forum\Data;


final class ForumData
{
    const LOGIN_URL = 'ucp.php?mode=login';
    const SID_URL = 'phpbb3_mzl11_sid';

    /** @var string */
    public string $url;

    /** @var string */
    public string $username;

    /** @var string */
    public string $password;

    /** @var string */
    public string $sid;

    /** @var array */
    public array $cookies;
}