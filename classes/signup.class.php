<?php
class Signup extends Dbh
{
    protected function setUser($uid, $pwd, $email)
    {
        $sql = "INSERT INTO users (user_uid, user_pwd, user_email) VALUES (?,?,?);";
        $stmt = parent::connect()->prepare($sql);

        // hashing the password first before sind it to db
        $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

        if (!$stmt->execute([$uid, $hashedPwd, $email])) {
            $sql = null;
            $stmt = null;
            echo json_encode(["error" => "stmt failed!"]);
            exit();
        }

        $sql = null;
        $stmt = null;
    }

    protected function checkUser($uid, $email)
    {
        $sql = "SELECT user_uid FROM users WHERE user_uid = ? OR user_email = ?;";
        $stmt = parent::connect()->prepare($sql);

        // check for execution
        if (!$stmt->execute([$uid, $email])) {
            $sql = null;
            $stmt = null;
            echo json_encode(["error" => "stmt failed!"]);
            exit();
        }

        // check for returned rows bigger zero
        $resultCheck = null;
        if ($stmt->rowCount() > 0) {
            $resultCheck = false;
        } else {
            $resultCheck = true;
        }

        return $resultCheck;
    }
}