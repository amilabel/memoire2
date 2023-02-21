<?php
session_start();
    $server = "localhost";
    $username = "root";
    $password = "";
    $dbname = "cbir";
    $user_id = uniqid();
    $conn = mysqli_connect($server, $username, $password, $dbname);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    if (isset($_POST['submit'])) {
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['Email'];
        $phone_number = $_POST['phone_number'];
        $password = $_POST['password'];
        $sql = "INSERT INTO user ( user_id	,first_name, last_name,Email, phone_number, `password`)
        VALUES (Null, '$first_name', ' $last_name', '$email','$phone_number', '$password')";

        if (mysqli_query($conn, $sql)) {
            $_SESSION['logged_in'] = true;
            header('Location:http://localhost/CBIR/image.html');

            
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }
    mysqli_close($conn);?>
