<?php
session_start();
include("connection.php");

// Check if user is logged in
if (!isset($_SESSION['whatsapp'])) {
    header("Location: login.php"); // Redirect if not logged in
    exit();
}

// Fetch user data based on WhatsApp number from session
$whatsapp = $_SESSION['whatsapp'];
$query = "SELECT * FROM registrations WHERE whatsapp = '$whatsapp'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $user_data = mysqli_fetch_assoc($result);
    // Extract user data
    $name = $user_data['name'];
    $whatsapp = $user_data['whatsapp'];
    $dob = $user_data['dob'];
    $city = $user_data['city'];
    $firstEvent = $user_data['firstEvent'];
    $firstPartner = $user_data['firstPartner'];
    $secondEvent = $user_data['secondEvent'];
    $secondPartner = $user_data['secondPartner'];
    $tshirtSize = $user_data['tshirtSize'];
    $shortsSize = $user_data['shortsSize'];
    $foodPreference = $user_data['foodPreference'];
    $accommodation = $user_data['accommodation'];
    $paymentReference = $user_data['paymentReference'];
} else {
    echo "Error: Unable to fetch user data.";
    // You can handle this error case based on your application's logic
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="zz.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="user-info">
        <h1>Welcome, <?php echo $name; ?>!</h1>
        <p>Your details:</p>
        <p>WhatsApp Number: <?php echo $whatsapp; ?></p>
        <p>Date of Birth: <?php echo $dob; ?></p>
        <p>City: <?php echo $city; ?></p>
        <p>First Event: <?php echo $firstEvent; ?></p>
        <p>First Event Partner: <?php echo $firstPartner; ?></p>
        <p>Second Event: <?php echo $secondEvent; ?></p>
        <p>Second Event Partner: <?php echo $secondPartner; ?></p>
        <p>T-Shirt Size: <?php echo $tshirtSize; ?></p>
        <p>Shorts Size: <?php echo $shortsSize; ?></p>
        <p>Food Preference: <?php echo $foodPreference; ?></p>
        <p>Accommodation: <?php echo $accommodation; ?></p>
        <p>Payment Reference: <?php echo $paymentReference; ?></p>

        <div class="buttons">
            <button type="submit" onclick="location.href='update.php'">Update Details</button>
            <button type="submit" onclick="location.href='index.html'">Logout</button>
        </div>
    </div>
</body>
</html>
