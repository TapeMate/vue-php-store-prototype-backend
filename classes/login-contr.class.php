<?php

class LoginContr extends Login
{
    private $uid;
    private $pwd;

    public function __construct($uid, $pwd)
    {
        $this->uid = $uid;
        $this->pwd = $pwd;
    }

    // error handling
    public function loginUser()
    {
        if ($this->emptyInput() == false) {
            echo json_encode(["error" => "empty Input, please fill out all fields!"]);
            exit();
        }

        parent::getUser($this->uid, $this->pwd);
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