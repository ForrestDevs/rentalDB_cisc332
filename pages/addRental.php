<?php
require_once '../functions.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $rental_type = $_POST['rental_type'];
    $apt_num = $_POST['apt_num'] ?? null; // Apartment number can be null
    $street = $_POST['street'];
    $city = $_POST['city'];
    $province = $_POST['province'];
    $pc = $_POST['pc'];
    $num_bedrooms = $_POST['num_bedrooms'];
    $num_bathrooms = $_POST['num_bathrooms'];
    $laundry_type = $_POST['laundry_type'];
    $has_parking = isset($_POST['has_parking']) ? 1 : 0;
    $is_accessible = isset($_POST['is_accessible']) ? 1 : 0;
    $rent = $_POST['rent'];
    $date_listed = $_POST['date_listed'];
    $owner_ids = $_POST['owner_ids'] ?? []; // This could be an array of owner IDs
    $manager_id = $_POST['manager_id'];

    // Insert the rental and retrieve the rental_id
    $rental_id = insertNewRental($rental_type, $apt_num, $street, $city, $province, $pc, $num_bedrooms, $num_bathrooms, $laundry_type, $has_parking, $is_accessible, $rent, $date_listed);

    // Insert the owner associations
    foreach ($owner_ids as $owner_id) {
        insertNewOwnsRental($owner_id, $rental_id);
    }

    // Assume date_started is today; modify as needed
    $date_started = date('Y-m-d');
    // Insert the manager association
    insertNewManagesRental($manager_id, $date_started, $rental_id);

    // Redirect or output success message
    header('Location: /rental'); // Change to your success page location
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post New Rental</title>
    <link rel="stylesheet" type="text/css" href="../style.css">
</head>

<body>

    <h2>Post New Rental</h2>
    <div
        style="margin: 50px; width: auto; height: auto; border: 1px solid #ccc; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2); border-radius: 10px; padding: 20px;">
        <form action="addRental.php" method="post">
            <h3>Rental Details:</h3>
            <label for="rental_type">Rental Type:</label>
            <select id="rental_type" name="rental_type">
                <option value="House">House</option>
                <option value="Apartment">Apartment</option>
                <option value="Room">Room</option>
            </select><br>

            <label for="apt_num">Apartment Number:</label>
            <input type="text" id="apt_num" name="apt_num"><br>

            <label for="street">Street:</label>
            <input type="text" id="street" name="street" required><br>

            <label for="city">City:</label>
            <input type="text" id="city" name="city" required><br>

            <label for="province">Province:</label>
            <input type="text" id="province" name="province" maxlength="2" required><br>

            <label for="pc">Postal Code:</label>
            <input type="text" id="pc" name="pc" maxlength="7" required><br>

            <label for="num_bedrooms">Number of Bedrooms:</label>
            <input type="number" id="num_bedrooms" name="num_bedrooms" required><br>

            <label for="num_bathrooms">Number of Bathrooms:</label>
            <input type="number" id="num_bathrooms" name="num_bathrooms" required><br>

            <label for="laundry_type">Laundry Type:</label>
            <select id="laundry_type" name="laundry_type">
                <option value="ensuite">Ensuite</option>
                <option value="shared">Shared</option>
            </select><br>

            <label for="has_parking">Has Parking:</label>
            <input type="checkbox" id="has_parking" name="has_parking"><br>

            <label for="is_accessible">Is Accessible:</label>
            <input type="checkbox" id="is_accessible" name="is_accessible"><br>

            <label for="rent">Rent:</label>
            <input type="text" id="rent" name="rent" required><br>

            <label for="date_listed">Date Listed:</label>
            <input type="date" id="date_listed" name="date_listed" required><br>

            <h3>Owners:</h3>
            <div id="owners">
                <label for="owner1">Owner ID:</label>
                <input type="text" id="owner1" name="owner_ids[]" maxlength="5" required><br>
                <!-- Placeholder for adding more owners dynamically -->
            </div>
            <button type="button" onclick="addOwnerField()">Add Another Owner</button><br>

            <h3>Manager:</h3>
            <label for="manager_id">Manager ID:</label>
            <input type="text" id="manager_id" name="manager_id" maxlength="5" required><br>

            <input type="submit" value="Post Rental">
        </form>
    </div>


    <script>
        function addOwnerField() {
            const ownersDiv = document.getElementById('owners');
            const newField = document.createElement('input');
            newField.type = 'text';
            newField.name = 'owner_ids[]';
            newField.maxLength = '5';
            newField.required = true;
            ownersDiv.appendChild(newField);
            ownersDiv.appendChild(document.createElement('br'));
        }
    </script>

</body>

</html>