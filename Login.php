<?php
session_start();
include("connection.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $whatsapp = $_POST['whatsapp'];
    $dob = $_POST['dob'];

    if (!empty($whatsapp) && !empty($dob)) {
        $query = "SELECT * FROM registrations WHERE whatsapp='$whatsapp' AND dob='$dob' LIMIT 1";
        $result = mysqli_query($conn, $query);

        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                $user_data = mysqli_fetch_assoc($result);
                // Assuming you want to store some user data in the session
                $_SESSION['user_id'] = $user_data['id'];
                $_SESSION['whatsapp'] = $user_data['whatsapp'];
                
                header("Location: z.php");
                die;
            } else {
                echo "<script type='text/javascript'>alert('Wrong number or date of birth');</script>";
            }
        } else {
            echo "<script type='text/javascript'>alert('Error executing query');</script>";
        }
    } else {
        echo "<script type='text/javascript'>alert('Please enter both WhatsApp number and date of birth');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
   
</head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="login.css">
<body>
    <form action="" method="POST">
        <label for="whatsapp">WhatsApp Number:</label>
        <input type="text" id="whatsapp" name="whatsapp" required>
        <label for="dob">Date of Birth:</label>
        <input type="date" id="dob" name="dob" required>
        <button type="submit">Login</button>
    </form>
</body>
</html>