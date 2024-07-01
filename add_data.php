<?php
include 'connection.php';
if($_SERVER["REQUEST_METHOD"]==="POST"){
    $name = $_POST["name"];
    $email = $_POST["email"];
    $gender = $_POST["gender"];
    $mobile = $_POST["mobile"];
    $company = (int)$_POST["company"][0];
    $years = (int)$_POST["years"][0];
    $months = (int)$_POST["months"][0];

    try{
        $sql = "SELECT mobile FROM users WHERE  mobile = '$mobile'";
        $result = $conn->query($sql);
        if(!$result){
            throw new Exception("Query Failed: ", $conn->error);
        }
        if($result->num_rows >0){
            echo "Mobil Number Already Exist";
            exit();
        }

        $sql = "SELECT email FROM users WHERE email ='$email'";
        $result = $conn->query($sql);
        if(!$result){
            throw new Exception("Query Failed: ", $conn->error);
        }
        if($result->num_rows >0){
            echo "Email Already Exist";
            exit();
        }

        $sql = "INSERT INTO users (name, email, mobile, gender) VALUES ('$name', '$email', '$mobile', '$gender')";
        if (!$conn->query($sql)) {
            throw new Exception("User Insert Failed: " . $conn->error);
        }

        // Get the user_id of the newly inserted user
        $user_id = $conn->insert_id;

        $sql = "INSERT INTO experience (user_id, num_companies, num_years, num_months) VALUES ('$user_id', '$company', '$years', '$months')";
        if (!$conn->query($sql)) {
            throw new Exception("Experience Insert Failed: " . $conn->error);
        }
        $conn->commit();
        echo "success";

    }catch(Exception $e){
        $conn->rollback();
        echo "Errror: ", $e;
    }
}

?>