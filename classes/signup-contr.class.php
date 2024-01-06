<?php

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
            // redirection must be done in a different way!!!
            // header("location: ../index.php?error=emptyinput");
            // exit();

            // instead of redirecting and exit, return error message.
            // return ["error" => "empty Input, pls fill out all fields!"];


            echo json_encode(["error" => "empty Input, please fill out all fields!"]);
            exit(); // Terminate script execution
        }
        parent::setUser($this->uid, $this->pwd, $this->email);
        return ["message" => "All Inputs set."];
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
}