<?php
require_once '../functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the group_id from the POST request
    $group_id = isset($_POST['group_id']) ? $_POST['group_id'] : '';

    // Fetch the current preferences for the group
    $currentPreferences = fetchGroupByID($group_id);

    if (isset($_POST['action']) && $_POST['action'] == 'savePreferences') {
        savePreferences($_POST['group_id'], $_POST);
    }


    // Check if the group preferences were successfully fetched
    if ($currentPreferences) {
        // Display the form with the current preferences initialized
        ?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Update Group Preferences</title>
            <link rel="stylesheet" type="text/css" href="../style.css">
        </head>

        <body>
            <div
                style="margin: 50px; width: auto; height: auto; border: 1px solid #ccc; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2); border-radius: 10px; padding: 20px;">
                <h1>Update Group <?php echo htmlspecialchars($group_id)?> Preferences</h1>
                <form action="updatePreferences.php" method="post">
                    <input type="hidden" name="action" value="savePreferences">
                    <input type="hidden" name="group_id" value="<?php echo htmlspecialchars($group_id); ?>">

                    <label for="rental_type">Type:</label>
                    <select id="rental_type" name="rental_type">
                        <option value="House" <?php echo $currentPreferences['rental_type'] == 'House' ? 'selected' : ''; ?>>House
                        </option>
                        <option value="Apartment" <?php echo $currentPreferences['rental_type'] == 'Apartment' ? 'selected' : ''; ?>>
                            Apartment</option>
                        <option value="Room" <?php echo $currentPreferences['rental_type'] == 'Room' ? 'selected' : ''; ?>>Room
                        </option>
                    </select><br>

                    <label for="is_accessible">Accessible:</label>
                    <input type="checkbox" id="is_accessible" name="is_accessible" <?php echo $currentPreferences['is_accessible'] ? 'checked' : ''; ?>><br>

                    <label for="has_parking">Has Parking:</label>
                    <input type="checkbox" id="has_parking" name="has_parking" <?php echo $currentPreferences['has_parking'] ? 'checked' : ''; ?>><br>

                    <label for="num_bedrooms">Number of Bedrooms:</label>
                    <input type="number" id="num_bedrooms" name="num_bedrooms"
                        value="<?php echo htmlspecialchars($currentPreferences['num_bedrooms']); ?>"><br>

                    <label for="num_bathrooms">Number of Bathrooms:</label>
                    <input type="number" id="num_bathrooms" name="num_bathrooms"
                        value="<?php echo htmlspecialchars($currentPreferences['num_bathrooms']); ?>"><br>

                    <label for="rent_price">Rent Price:</label>
                    <input type="text" id="rent_price" name="rent_price"
                        value="<?php echo htmlspecialchars($currentPreferences['rent_price']); ?>"><br>

                    <label for="laundry_type">Laundry Type:</label>
                    <select id="laundry_type" name="laundry_type">
                        <option value="ensuite" <?php echo $currentPreferences['laundry_type'] == 'ensuite' ? 'selected' : ''; ?>>
                            Ensuite</option>
                        <option value="shared" <?php echo $currentPreferences['laundry_type'] == 'shared' ? 'selected' : ''; ?>>
                            Shared
                        </option>
                    </select><br>

                    <input type="submit" value="Save Preferences">
                </form>

            </div>

        </body>

        </html>
        <?php
        // Check if the preferences were just saved
        if (isset($_GET['saved']) && $_GET['saved'] == 'true') {
            // Add an alert for successful save
            echo "<script>alert('Preferences saved successfully!');</script>";
        }

    } else {
        echo '<p>Failed to fetch preferences for the group. Please try again.</p>';
    }
}

?>