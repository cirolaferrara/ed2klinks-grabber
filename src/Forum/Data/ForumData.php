<?php


namespace Ed2kLinksGrabber\Forum\Data;


final class ForumData
{
    const LOGIN_URL = 'ucp.php?mode=login';
    const SID_URL = 'phpbb3_mzl11_sid';

    /** @var string */
    public $url;

    /** @var string */
    public $username;

    /** @var string */
    public $password;

    /** @var string */
    public $sid;

    /** @var array */
    public $cookies;
}