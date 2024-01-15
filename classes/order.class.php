<?php

class Order extends Dbh
{
    public function createOrder($uid)
    {
        $pdo = parent::connect();
        $sql = "INSERT INTO orders (users_user_id) VALUES (?);";
        $stmt = $pdo->prepare($sql);

        if (!$stmt->execute([$uid])) {
            error_log("Error in createOrder: " . join(", ", $pdo->errorInfo()));
            return false;
        }

        return $pdo->lastInsertId();
    }
}