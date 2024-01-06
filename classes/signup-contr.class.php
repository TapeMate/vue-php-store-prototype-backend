<?php
ini_set('display_errors', '0'); // Turn off error displaying
error_reporting(E_ALL); // Report all errors

class SignupContr extends Signup
{
    private $uid;
    private $pwd;
    private $pwdRepeat;
    private $email;

    public function __construct($uid, $pwd, $pwdRepeat, $email)
    {
        $this->uid = $uid;
        $this->pwd = $pwd;
        $this->pwdRepeat = $pwdRepeat;
        $this->email = $email;
    }

    public function signupUser()
    {
        if ($this->emptyInput() == false) {
            // echo error message to frontend
            // exit rest of the script
            echo json_encode(["error" => "empty Input, please fill out all fields!"]);
            exit();
        }

        if ($this->invalidUid() == false) {
            echo json_encode(["error" => "Invalid Username taken!"]);
            exit();
        }

        if ($this->invalidEmail() == false) {
            echo json_encode(["error" => "Invalid Email Adress!"]);
            exit();
        }

        if ($this->pwdMatch() == false) {
            echo json_encode(["error" => "Passwords do not match!"]);
            exit();
        }

        if ($this->uidTaken() == false) {
            echo json_encode(["error" => "Username or EMail allready registered!"]);
            exit();
        }

        parent::setUser($this->uid, $this->pwd, $this->email);
        return ["message" => "Signup successful!"];
    }

    private function emptyInput()
    {
        $result = null;
        if (empty($this->uid) || empty($this->pwd) || empty($this->pwdRepeat) || empty($this->email)) {
            $result = false;
        } else {
            $result = true;
        }
        return $result;
    }

    private function invalidUid()
    {
        $result = null;
        if (!preg_match("/^[a-zA-Z0-9]*$/", $this->uid)) {
            $result = false;
        } else {
            $result = true;
        }
        return $result;
    }

    private function invalidEmail()
    {
        $result = null;
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $result = false;
        } else {
            $result = true;
        }
        return $result;
    }

    private function pwdMatch()
    {
        $result = null;
        if ($this->pwd !== $this->pwdRepeat) {
            $result = false;
        } else {
            $result = true;
        }
        return $result;
    }

    private function uidTaken()
    {
        $result = null;
        if (!parent::checkUser($this->uid, $this->email)) {
            $result = false;
        } else {
            $result = true;
        }
        return $result;
    }
}