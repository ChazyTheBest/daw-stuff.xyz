<?php

namespace framework;

final class UserSession
{
    private bool $isLoggedIn;
    private static ?UserSession $instance = null;

    /**
     * AuthSystem constructor.
     * @param array $session
     */
    public function __construct(array $session)
    {
        self::$instance = $this;
        $this->session($session);
        $this->isLoggedIn = isset($_SESSION['user_id'], $_SESSION['IS_LOGGED_IN']) && $_SESSION['IS_LOGGED_IN'] === TRUE;
        $this->enhanceHttpSecurity();
    }

    // check authentication
    public function isLoggedIn(): bool
    {
        return $this->isLoggedIn;
    }

    private function session(array $session): void
    {
        // Set the cookies params
        session_set_cookie_params
        ([
            'lifetime'  => $session['lifetime'],
            'path'      => '/',
            'secure'    => $session['secure'],
            'httponly'  => TRUE,
            'samesite'  => 'strict'
        ]);

        // Set the session name
        session_name($session['name']);
        session_start();                                    // Start the PHP session

        $time = time();

        if ( !isset($_SESSION['CREATED']) )
            $_SESSION['CREATED'] = $time;

        else if ( ($time - $_SESSION['CREATED']) > 1800 )
        {
            // session started more than 30 minutes ago
            session_regenerate_id(TRUE);    // regenerate the session ID and delete the old one
            $_SESSION['CREATED'] = $time;                  // update creation time

            if ( $session['logout_time'] > 0 && ($time - $_SESSION['CREATED']) > $session['logout_time'] )
                $_SESSION['IS_LOGGED_IN'] = $this->isLoggedIn = FALSE;
        }
    }

    private function enhanceHttpSecurity(): void
    {
        // remove exposure of PHP version (at least where possible)
        header_remove('X-Powered-By');

        // if the user is signed in
        if ($this->isLoggedIn)
        {
            // prevent clickjacking
            header('X-Frame-Options: sameorigin');
            // prevent content sniffing (MIME sniffing)
            header('X-Content-Type-Options: nosniff');

            // disable caching of potentially sensitive data
            header('Cache-Control: no-store, no-cache, must-revalidate', true);
            header('Expires: Thu, 19 Nov 1981 00:00:00 GMT', true);
            header('Pragma: no-cache', true);
        }
    }

    public static function &getInstance(): UserSession
    {
        return self::$instance;
    }
}
