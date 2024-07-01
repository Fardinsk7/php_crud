<?php
include "connection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_POST['user_id'];

    
    try {
        // Delete from experience table
        $sql = "DELETE FROM experience WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param('i', $user_id);
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }

        // Delete from users table
        $sql = "DELETE FROM users WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param('i', $user_id);
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }

        // Commit transaction
        $conn->commit();
        echo "success";
    } catch (Exception $e) {
        // Rollback transaction
        $conn->rollback();
        error_log("Error deleting user: " . $e->getMessage());
        echo "error: " . $e->getMessage();
    }

    $stmt->close();
}

$conn->close();
?>
