<?php
require_once "../functions.php";

ini_set('display_errors', 1);
error_reporting(E_ALL);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture form data
    $id = $_POST['id'];
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $phoneNum = $_POST['phone_num'];

    // Insert the new person
    $result = insertNewPerson($id, $firstName, $lastName, $phoneNum);

    if ($result) {
        // Redirect to a confirmation page or display a success message
        header("Location: /rental"); // Adjust the redirection path as necessary
    } else {
        // Handle error
        echo "Error: Could not insert the new person into the database.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add new person</title>
    <link rel="stylesheet" type="text/css" href="../style.css">
</head>

<body>
    <h2>Add New Person to Database</h2>
    <div
        style="margin: 50px; width: auto; height: auto; border: 1px solid #ccc; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2); border-radius: 10px; padding: 20px;">
        <form action="addPerson.php" method="post">
            <label for="id">ID:</label>
            <input type="text" id="id" name="id" maxlength="5" required><br>

            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" required><br>

            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" required><br>

            <label for="phone_num">Phone Number:</label>
            <input type="text" id="phone_num" name="phone_num" maxlength="10" required><br>

            <input type="submit" value="Add Person">
        </form>
    </div>
</body>

</html>