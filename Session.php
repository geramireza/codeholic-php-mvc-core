<?php

namespace Codeholic\Phpmvc;

class Session
{
    public const FLASH_MESSAGES = 'flash_messages';
    public function __construct()
    {
        session_start();
        $flashMessages = $_SESSION[self::FLASH_MESSAGES] ?? [];
        foreach ($flashMessages as  &$flashMessage){
            $flashMessage['remove'] = true;
        }
        $_SESSION[self::FLASH_MESSAGES] = $flashMessages;
    }

    public function set(string $key, mixed $value)
    {
        $_SESSION[$key] = $value;
    }

    public function get(string $key)
    {
        return $_SESSION[$key] ?? null;
    }

    public function forget(string $key)
    {
        unset($_SESSION[$key]);
    }
    public function setFlash(string $key, string $message):void
    {
        $_SESSION[self::FLASH_MESSAGES][$key] = [
            'remove' => false,
            'value' => $message
        ];
    }

    public function getFlash(string $key)
    {
        return $_SESSION[self::FLASH_MESSAGES][$key]['value'] ?? null;
    }

    public function __destruct()
    {
        $flashMessages = $_SESSION[self::FLASH_MESSAGES] ?? [];
        foreach ($flashMessages as  $key => $flashMessage){
            if($flashMessage['remove']){
                unset($flashMessages[$key]);
            }
        }
        $_SESSION[self::FLASH_MESSAGES] = $flashMessages;

    }
}