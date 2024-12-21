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
    <title>Update User Details</title>
    <link rel="stylesheet" href="zz.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="user-info">
        <h1>Update Your Details</h1>
        <form action="update_process.php" method="POST">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo $name; ?>" required>
            </div>
            <div class="form-group">
                <label for="dob">Date of Birth:</label>
                <input type="date" id="dob" name="dob" value="<?php echo $dob; ?>" required>
            </div>
            <div class="form-group">
                <label for="city">City:</label>
                <input type="text" id="city" name="city" value="<?php echo $city; ?>">
            </div>
            <div class="form-group">
                <label>Your First Event:</label>
                <div class="radio-group">
                    <input type="radio" id="eventOpen" name="firstEvent" value="Open" <?php if ($firstEvent == 'Open') echo 'checked'; ?>>
                    <label for="eventOpen">Open</label>
                    <input type="radio" id="event90plus" name="firstEvent" value="90+" <?php if ($firstEvent == '90+') echo 'checked'; ?>>
                    <label for="event90plus">90+</label>
                    <input type="radio" id="event105plus" name="firstEvent" value="105+" <?php if ($firstEvent == '105+') echo 'checked'; ?>>
                    <label for="event105plus">105+</label>
                    <input type="radio" id="event120plus" name="firstEvent" value="120+" <?php if ($firstEvent == '120+') echo 'checked'; ?>>
                    <label for="event120plus">120+</label>
                </div>
            </div>
            <div class="form-group">
                <label for="firstPartner">Name of First Event Partner:</label>
                <select id="firstPartner" name="firstPartner" required>
                    <option value="not_registered" <?php if ($firstPartner == 'not_registered') echo 'selected'; ?>>Partner not registered yet</option>
                    <option value="zyx" <?php if ($firstPartner == 'zyx') echo 'selected'; ?>>ZYX</option>
                </select>
            </div>
            <div class="form-group">
                <label>Your Second Event:</label>
                <div class="radio-group">
                    <input type="radio" id="event2Open" name="secondEvent" value="Open" <?php if ($secondEvent == 'Open') echo 'checked'; ?>>
                    <label for="event2Open">Open</label>
                    <input type="radio" id="event290plus" name="secondEvent" value="90+" <?php if ($secondEvent == '90+') echo 'checked'; ?>>
                    <label for="event290plus">90+</label>
                    <input type="radio" id="event2105plus" name="secondEvent" value="105+" <?php if ($secondEvent == '105+') echo 'checked'; ?>>
                    <label for="event2105plus">105+</label>
                    <input type="radio" id="event2120plus" name="secondEvent" value="120+" <?php if ($secondEvent == '120+') echo 'checked'; ?>>
                    <label for="event2120plus">120+</label>
                </div>
            </div>
            <div class="form-group">
                <label for="secondPartner">Name of Second Event Partner:</label>
                <select id="secondPartner" name="secondPartner" required>
                    <option value="not_registered" <?php if ($secondPartner == 'not_registered') echo 'selected'; ?>>Partner not registered yet</option>
                    <option value="zyx" <?php if ($secondPartner == 'zyx') echo 'selected'; ?>>ZYX</option>
                </select>
            </div>
            <div class="form-group">
                <label for="tshirtSize">T-Shirt Size:</label>
                <input type="text" id="tshirtSize" name="tshirtSize" value="<?php echo $tshirtSize; ?>">
            </div>
            <div class="form-group">
                <label for="shortsSize">Shorts Size:</label>
                <input type="text" id="shortsSize" name="shortsSize" value="<?php echo $shortsSize; ?>">
            </div>
            <div class="form-group">
                <label for="foodPreference">Food Preference:</label>
                <input type="text" id="foodPreference" name="foodPreference" value="<?php echo $foodPreference; ?>">
            </div>
            <div class="form-group">
                <label for="accommodation">Accommodation:</label>
                <input type="text" id="accommodation" name="accommodation" value="<?php echo $accommodation; ?>">
            </div>
            <div class="form-group">
                <label for="paymentReference">Payment Reference:</label>
                <input type="text" id="paymentReference" name="paymentReference" value="<?php echo $paymentReference; ?>">
            </div>

            <button type="submit" name="submit">Update</button>
        </form>
    </div>
</body>
</html>
