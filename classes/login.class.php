<?php
ini_set('display_errors', '0'); // Turn off error displaying
error_reporting(E_ALL); // Report all errors

class Login extends Dbh
{
    protected function getUser($uid, $pwd)
    {
        $sql = "SELECT users_pwd FROM users WHERE users_uid = ?;";
        $stmt = parent::connect()->prepare($sql);
        $stmt->execute([$uid]);

        if ($stmt->rowCount() == 0) {
            return ["error" => "User not found"];
        }

        $pwdHashed = $stmt->fetch(PDO::FETCH_ASSOC);
        $checkPwd = password_verify($pwd, $pwdHashed["users_pwd"]);

        if (!$checkPwd) {
            return ["error" => "Wrong password"];
        }

        // Fetch complete user data
        $sql = "SELECT * FROM users WHERE users_uid = ?;";
        $stmt = parent::connect()->prepare($sql);
        $stmt->execute([$uid]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return ["error" => "User not found"];
        }

        return ["user" => $user];
    }
}