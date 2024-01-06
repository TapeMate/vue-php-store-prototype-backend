<?php
ini_set('display_errors', '0'); // Turn off error displaying
error_reporting(E_ALL); // Report all errors

class LoginContr extends Login
{
    private $uid;
    private $pwd;

    public function __construct($uid, $pwd)
    {
        $this->uid = $uid;
        $this->pwd = $pwd;
    }

    public function loginUser()
    {
        if ($this->emptyInput() == false) {
            echo json_encode(["error" => "input empty!"]);
            exit();
        }

        return parent::getUser($this->uid, $this->pwd);
    }

    private function emptyInput()
    {
        $result = null;
        if (empty($this->uid) || empty($this->pwd)) {
            $result = false;
        } else {
            $result = true;
        }
        return $result;
    }
}