<?php
ini_set('display_errors', '0'); // Turn off error displaying
error_reporting(E_ALL); // Report all errors

class Login extends DbH
{
    protected function getUser($uid, $pwd)
    {
        $sql = "SELECT users_pwd FROM users WHERE users_uid = ?;";
        $stmt = parent::connect()->prepare($sql);
        $stmt->execute([$uid]);

        $pwdHashed = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $checkPwd = password_verify($pwd, $pwdHashed[0]["users_pwd"]);

        if (!$checkPwd) {
            echo json_encode(["error" => "wrong password!"]);
            exit();
        } else if ($checkPwd) {
            $sql = "SELECT * FROM users WHERE users_uid = ? AND users_pwd = ?;";
            $stmt = parent::connect()->prepare($sql);
            $stmt->execute([$uid, $pwdHashed[0]["users_pwd"]]);

            $user = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Log the $user variable
            $logFile = "login.log";
            file_put_contents($logFile, "User data: " . print_r($user, true) . "\n", FILE_APPEND);

            return $user;
        }
    }
}