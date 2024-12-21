<?php
session_start();
include("connection.php");

// Check if user is logged in
if (!isset($_SESSION['whatsapp'])) {
    header("Location: login.php"); // Redirect if not logged in
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $name = $_POST['name'];
    $dob = $_POST['dob'];
    $city = $_POST['city'];
    $firstEvent = $_POST['firstEvent'];
    $firstPartner = $_POST['firstPartner'];
    // Add more fields as needed

    // Update query
    $whatsapp = $_SESSION['whatsapp'];
    $sql = "UPDATE registrations SET 
            name = '$name',
            dob = '$dob',
            city = '$city',
            firstEvent = '$firstEvent',
            firstPartner = '$firstPartner'
            WHERE whatsapp = '$whatsapp'";

    if (mysqli_query($conn, $sql)) {
        // Redirect to dashboard or confirmation page after successful update
        header("Location: z.php");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request.";
}
?>
