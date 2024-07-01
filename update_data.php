<?php
include 'connection.php';

if($_SERVER["REQUEST_METHOD"] === "POST"){
    $user_id = (int)$_POST["user_id"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $gender = $_POST["gender"];
    $mobile = $_POST["mobile"];
    $company = (int)$_POST["company"][0];
    $years = (int)$_POST["years"][0];
    $months = (int)$_POST["months"][0];

    try{
        $sql = "SELECT user_id FROM users WHERE mobile ='$mobile' AND user_id != '$user_id'";
        $result = $conn->query($sql);
        if(!$result){
            throw new Exception("Query Failed: ", $conn->error);
        }
        if($result->num_rows >0){
            echo "Mobil Number Already Exist";
            exit();
        }

        $sql = "SELECT user_id FROM users WHERE email ='$email' AND user_id != '$user_id'";
        $result = $conn->query($sql);
        if(!$result){
            throw new Exception("Query Failed: ", $conn->error);
        }
        if($result->num_rows >0){
            echo "Email Already Exist";
            exit();
        }


        $sql = "UPDATE users SET name=?, email=?, mobile=?, gender=? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        if(!$stmt){
            throw new Exception("Prepare Failed:". $conn->error);
        }
        $stmt->bind_param("ssssi",$name,$email,$mobile,$gender,$user_id);
        if(!$stmt->execute()){
            throw new Exception("Execution Failed: ". $conn->error);
        }

        $sql = "UPDATE experience SET num_companies=?, num_years=?, num_months=? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        if(!$stmt){
            throw new Exception("Prepare Failed:". $conn->error);
        }
        $stmt->bind_param("iiii",$company,$years,$months,$user_id);
        if(!$stmt->execute()){
            throw new Exception("Execution Failed: ". $conn->error);
        }
        $conn->commit();
        echo "success";

    }
    catch(Exception $e){
        $conn->rollback()
;        echo "Error: ". $e;
    }
    $stmt->close();
}
$conn->close();


?>