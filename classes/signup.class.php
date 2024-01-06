<?php

class Signup extends Dbh
{
    protected function setUser($uid, $pwd, $email)
    {
        $sql = "INSERT INTO users (users_uid, users_pwd, users_email) VALUES (?,?,?);";
        $stmt = parent::connect()->prepare($sql);

        // hashing the password first before sind it to db
        $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

        if (!$stmt->execute([$uid, $hashedPwd, $email])) {
            $sql = null;
            $stmt = null;
            header("location: ../index.php?error=stmtfailed");
            exit();
        }

        $sql = null;
        $stmt = null;
    }
}