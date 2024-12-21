<?php
session_start();
include("connection.php"); // Ensure this points to your correct database connection file

// Generate a new CSRF token if not already set
if (empty($_SESSION['form_token'])) {
    $_SESSION['form_token'] = bin2hex(random_bytes(32));
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    if (isset($_POST['form_token']) && $_POST['form_token'] === $_SESSION['form_token']) {
        unset($_SESSION['form_token']); // Invalidate the token

        // Collect and sanitize input data
        $name = htmlspecialchars(trim($_POST['name']));
        $whatsapp = htmlspecialchars(trim($_POST['whatsapp']));
        $dob = htmlspecialchars(trim($_POST['dob']));
        $city = htmlspecialchars(trim($_POST['city']));
        $firstEvent = htmlspecialchars(trim($_POST['firstEvent']));
        $firstPartner = htmlspecialchars(trim($_POST['firstPartner']));
        $secondEvent = htmlspecialchars(trim($_POST['secondEvent']));
        $secondPartner = htmlspecialchars(trim($_POST['secondPartner']));
        $tshirtSize = htmlspecialchars(trim($_POST['tshirtSize']));
        $shortsSize = htmlspecialchars(trim($_POST['shortsSize']));
        $foodPreference = htmlspecialchars(trim($_POST['foodPreference']));
        $accommodation = htmlspecialchars(trim($_POST['accommodation']));
        $paymentReference = htmlspecialchars(trim($_POST['paymentReference']));

        // Validate required fields
        if (!empty($name) && !empty($whatsapp) && !empty($dob) && !empty($firstPartner) && !empty($tshirtSize) && !empty($shortsSize) && !empty($foodPreference) && !empty($accommodation) && !empty($paymentReference)) {

            // Validate specific fields
            if (!preg_match("/^[0-9]{10}$/", $whatsapp)) {
                echo "Invalid WhatsApp number format.";
                exit;
            }

            $date_regex = "/^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[0-2])\/([0-9]{4})$/";
            if (!preg_match($date_regex, $dob)) {
                echo "Invalid date format.";
                exit;
            }

            // Use prepared statements to prevent SQL injection
            $stmt = $conn->prepare("INSERT INTO registrations (name, whatsapp, dob, city, firstEvent, firstPartner, secondEvent, secondPartner, tshirtSize, shortsSize, foodPreference, accommodation, paymentReference) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssssssssss", $name, $whatsapp, $dob, $city, $firstEvent, $firstPartner, $secondEvent, $secondPartner, $tshirtSize, $shortsSize, $foodPreference, $accommodation, $paymentReference);

            if ($stmt->execute()) {
                // Mark both participants as paired
                if ($firstPartner !== 'not_registered') {
                    $update_stmt = $conn->prepare("UPDATE registrations SET is_paired = TRUE WHERE name = ? OR name = ?");
                    $update_stmt->bind_param("ss", $name, $firstPartner);
                    $update_stmt->execute();
                    $update_stmt->close();
                }

                header("Location: success.html");
                exit;
            } else {
                error_log("Database Error: " . $stmt->error); // Log the error
                echo "There was an issue processing your registration. Please try again later.";
            }
        } else {
            echo "Please fill all the required fields";
        }
    } else {
        echo "Invalid form submission.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tennis Tournament Registration</title>
    <link rel="stylesheet" href="b.css?v=<?php echo time(); ?>">
    <script src="a.js"></script>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h1>Event Registration Form</h1>
            <h2>Nissan All India Open Seniors Tennis Tournament 2024</h2>
            <p>8th - 9th May 2024, Dehradun</p>
            <form id="registrationForm" action="" method="POST">
                <input type="hidden" name="form_token" value="<?php echo $_SESSION['form_token']; ?>">
                <div class="form-group">
                    <label for="name">1. Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="whatsapp">2. WhatsApp Number:</label>
                    <input type="text" id="whatsapp" name="whatsapp" required>
                </div>
                <div class="form-group">
                    <label for="dob">3. Your Date of Birth (dd/mm/yyyy):</label>
                    <input type="date" id="dob" name="dob" required>
                </div>
                <div class="form-group">
                    <label for="city">4. City:</label>
                    <input type="text" id="city" name="city">
                </div>
                <div class="form-group">
                    <label>5. Your First Event:</label>
                    <div class="radio-group">
                        <input type="radio" id="eventA" name="firstEvent" value="Open" checked>
                        <label for="eventA">Open</label>
                        <input type="radio" id="eventB" name="firstEvent" value="90+">
                        <label for="eventB">90+</label>
                        <input type="radio" id="eventC" name="firstEvent" value="105+">
                        <label for="eventC">105+</label>
                        <input type="radio" id="eventD" name="firstEvent" value="120+">
                        <label for="eventD">120+</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="firstPartner">6. Name of First Event Partner:</label>
                    <select id="firstPartner" name="firstPartner" required>
                        <option value="not_registered">Partner not registered yet</option>
                        <?php
                        $sql = "SELECT name FROM registrations WHERE is_paired = FALSE";
                        $result = $conn->query($sql);
                        if ($result) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value=\"" . htmlspecialchars($row["name"]) . "\">" . htmlspecialchars($row["name"]) . "</option>";
                            }
                        } else {
                            echo "<option value='error'>Error fetching partners</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>7. Your Second Event (Optional):</label>
                    <div class="radio-group">
                        <input type="radio" id="eventE" name="secondEvent" value="Open">
                        <label for="eventE">Open</label>
                        <input type="radio" id="eventF" name="secondEvent" value="90+">
                        <label for="eventF">90+</label>
                        <input type="radio" id="eventG" name="secondEvent" value="105+">
                        <label for="eventG">105+</label>
                        <input type="radio" id="eventH" name="secondEvent" value="120+">
                        <label for="eventH">120+</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="secondPartner">8. Name of Second Event Partner:</label>
                    <select id="secondPartner" name="secondPartner">
                        <option value="not_registered">Partner not registered yet</option>
                        <?php
                        $sql = "SELECT name FROM registrations WHERE is_paired = FALSE";
                        $result = $conn->query($sql);
                        if ($result) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value=\"" . htmlspecialchars($row["name"]) . "\">" . htmlspecialchars($row["name"]) . "</option>";
                            }
                        } else {
                            echo "<option value='error'>Error fetching partners</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="tshirtSize">9. Your T-shirt Size:</label>
                    <select id="tshirtSize" name="tshirtSize" required>
                        <option value="Small">Small</option>
                        <option value="Medium">Medium</option>
                        <option value="Large">Large</option>
                        <option value="XL">XL</option>
                        <option value="XXL">XXL</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="shortsSize">10. Your Shorts Size:</label>
                    <select id="shortsSize" name="shortsSize" required>
                        <option value="Small">Small</option>
                        <option value="Medium">Medium</option>
                        <option value="Large">Large</option>
                        <option value="XL">XL</option>
                        <option value="XXL">XXL</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>11. Food Preference:</label>
                    <div class="radio-group">
                        <input type="radio" id="veg" name="foodPreference" value="Veg">
                        <label for="veg">Veg</label>
                        <input type="radio" id="nonVeg" name="foodPreference" value="Non-Veg">
                        <label for="nonVeg">Non-Veg</label>
                    </div>
                </div>
                <div class="form-group">
                    <label>12. Accommodation Required:</label>
                    <div class="radio-group">
                        <input type="radio" id="yes" name="accommodation" value="Yes">
                        <label for="yes">Yes</label>
                        <input type="radio" id="no" name="accommodation" value="No">
                        <label for="no">No</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="paymentReference">13. Payment Reference:</label>
                    <input type="text" id="paymentReference" name="paymentReference" required>
                </div>
                <div class="form-group">
                    <button type="submit" name="submit">Register</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
