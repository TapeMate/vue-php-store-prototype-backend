<?php
class Login extends Dbh
{
    protected function getUser($uid, $pwd)
    {
        $sql = "SELECT user_pwd FROM users WHERE user_uid = ?;";
        $stmt = parent::connect()->prepare($sql);
        $stmt->execute([$uid]);

        if ($stmt->rowCount() == 0) {
            return ["error" => "User not found"];
        }

        $pwdHashed = $stmt->fetch(PDO::FETCH_ASSOC);
        $checkPwd = password_verify($pwd, $pwdHashed["user_pwd"]);

        if (!$checkPwd) {
            return ["error" => "Wrong password"];
        }

        // Fetch complete user data
        $sql = "SELECT user_id, user_uid, user_email FROM users WHERE user_uid = ?;";
        $stmt = parent::connect()->prepare($sql);
        $stmt->execute([$uid]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return ["error" => "User not found"];
        }

        return ["success" => true, "user" => $user];
    }
}