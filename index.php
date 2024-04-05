<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once 'functions.php';

$persons = fetchPersons();
$groups = fetchGroups();
$rentals = fetchRentals();
$houses = fetchHouseRentals();
$appartments = fetchAppartmentRentals();
$rooms = fetchRoomRentals();
$averageRents = fetchAverageRents();
$activeTab = isset($_GET['tab']) ? $_GET['tab'] : 'All';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
    // Get the search term from the POST data
    $searchTerm = $_POST['searchTerm'];

    // Call the function and pass in the database connection and the search term
    $conn = getDatabaseConnection();
    $searchResults = searchPersons($conn, $searchTerm);

    // Set the active tab to 'SearchResults' to display the search tab content
    $activeTab = 'SearchResults';
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rental Application</title>
    <link rel="stylesheet" type="text/css" href="./style.css">
</head>

<body>

    <div style="text-align: center;">
        <h1>Queens Rental Application</h1>
        <img src="quflag.png" alt="Queens University Logo" style="width: 200px; height: auto;">
    </div>

    <div
        style="margin: 50px; width: auto; height: auto; border: 1px solid #ccc; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2); border-radius: 10px; padding: 20px;">
        <div class="tab">
            <div class="search-container">
                <form action="index.php" method="post">
                    <input type="text" name="searchTerm" placeholder="Search name or phone">
                    <input type="submit" name="search" value="Search">
                </form>
            </div>

            <form action="" method="get">
                <button name="tab" value="All" type="submit" <?php echo $activeTab == 'All' ? 'class="active"' : ''; ?>>All Rentals</button>
                <button name="tab" value="Houses" type="submit" <?php echo $activeTab == 'Houses' ? 'class="active"' : ''; ?>>Houses</button>
                <button name="tab" value="Apartments" type="submit" <?php echo $activeTab == 'Apartments' ? 'class="active"' : ''; ?>>Apartments</button>
                <button name="tab" value="Rooms" type="submit" <?php echo $activeTab == 'Rooms' ? 'class="active"' : ''; ?>>Rooms</button>
            </form>

            <a href="pages/addRental.php" class="button-link">Post New Listing</a>
            <a href="pages/addPerson.php" class="button-link">Add a Person</a>
        </div>
        <div class="<?php echo $activeTab == 'All' ? 'tabcontent active' : 'tabcontent'; ?>">
            <h2>All Rentals</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Type</th>
                    <th>Owner(s)</th>
                    <th>Manager</th>
                    <th>Rent</th>
                    <th>Beds</th>
                    <th>Baths</th>
                    <th>Has Parking</th>
                    <th>Laundry Type</th>
                    <th>Is Accessible</th>
                    <th>Date Listed</th>
                </tr>
                <?php foreach ($rentals as $rental): ?>
                    <tr>
                        <td>
                            <?php echo htmlspecialchars($rental['id']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($rental['rental_type']); ?>
                        </td>
                        <td>
                            <?php
                            $ownersArray = fetchOwnersNames($rental['id']);
                            $ownersArray = array_map(function ($owner) {
                                return $owner['first_name'] . ' ' . $owner['last_name'];
                            }, $ownersArray);
                            $ownersString = implode(', ', $ownersArray);
                            echo htmlspecialchars($ownersString ?? 'N/A');
                            ?>
                        </td>
                        <td>
                            <?php
                            $managerArray = fetchManagerNames($rental['id']);
                            $managerArray = array_map(function ($manager) {
                                return $manager['first_name'] . ' ' . $manager['last_name'];
                            }, $managerArray);
                            $managerString = implode(', ', $managerArray);
                            echo htmlspecialchars($managerString ?? 'N/A');
                            ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($rental['rent']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($rental['num_bedrooms']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($rental['num_bathrooms']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($rental['has_parking']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($rental['laundry_type']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($rental['is_accessible']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($rental['date_listed']); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <div class="<?php echo $activeTab == 'Houses' ? 'tabcontent active' : 'tabcontent'; ?>">
            <h2>Houses</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Type</th>
                    <th>Owner(s)</th>
                    <th>Manager</th>
                    <th>Rent</th>
                    <th>Beds</th>
                    <th>Baths</th>
                    <th>Has Parking</th>
                    <th>Laundry Type</th>
                    <th>Is Accessible</th>
                    <th>Date Listed</th>
                </tr>
                <?php foreach ($houses as $rental): ?>
                    <tr>
                        <td>
                            <?php echo htmlspecialchars($rental['id']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($rental['rental_type']); ?>
                        </td>
                        <td>
                            <?php
                            $ownersArray = fetchOwnersNames($rental['id']);
                            $ownersArray = array_map(function ($owner) {
                                return $owner['first_name'] . ' ' . $owner['last_name'];
                            }, $ownersArray);
                            $ownersString = implode(', ', $ownersArray);
                            echo htmlspecialchars($ownersString ?? 'N/A');
                            ?>
                        </td>
                        <td>
                            <?php
                            $managerArray = fetchManagerNames($rental['id']);
                            $managerArray = array_map(function ($manager) {
                                return $manager['first_name'] . ' ' . $manager['last_name'];
                            }, $managerArray);
                            $managerString = implode(', ', $managerArray);
                            echo htmlspecialchars($managerString ?? 'N/A');
                            ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($rental['rent']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($rental['num_bedrooms']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($rental['num_bathrooms']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($rental['has_parking']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($rental['laundry_type']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($rental['is_accessible']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($rental['date_listed']); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <div class="<?php echo $activeTab == 'Apartments' ? 'tabcontent active' : 'tabcontent'; ?>">
            <h2>Apartments</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Type</th>
                    <th>Owner(s)</th>
                    <th>Manager</th>
                    <th>Rent</th>
                    <th>Beds</th>
                    <th>Baths</th>
                    <th>Has Parking</th>
                    <th>Laundry Type</th>
                    <th>Is Accessible</th>
                    <th>Date Listed</th>
                </tr>
                <?php foreach ($appartments as $rental): ?>
                    <tr>
                        <td>
                            <?php echo htmlspecialchars($rental['id']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($rental['rental_type']); ?>
                        </td>
                        <td>
                            <?php
                            $ownersArray = fetchOwnersNames($rental['id']);
                            $ownersArray = array_map(function ($owner) {
                                return $owner['first_name'] . ' ' . $owner['last_name'];
                            }, $ownersArray);
                            $ownersString = implode(', ', $ownersArray);
                            echo htmlspecialchars($ownersString ?? 'N/A');
                            ?>
                        </td>
                        <td>
                            <?php
                            $managerArray = fetchManagerNames($rental['id']);
                            $managerArray = array_map(function ($manager) {
                                return $manager['first_name'] . ' ' . $manager['last_name'];
                            }, $managerArray);
                            $managerString = implode(', ', $managerArray);
                            echo htmlspecialchars($managerString ?? 'N/A');
                            ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($rental['rent']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($rental['num_bedrooms']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($rental['num_bathrooms']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($rental['has_parking']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($rental['laundry_type']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($rental['is_accessible']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($rental['date_listed']); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <div class="<?php echo $activeTab == 'Rooms' ? 'tabcontent active' : 'tabcontent'; ?>">
            <h2>Rooms</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Type</th>
                    <th>Owner(s)</th>
                    <th>Manager</th>
                    <th>Rent</th>
                    <th>Beds</th>
                    <th>Baths</th>
                    <th>Has Parking</th>
                    <th>Laundry Type</th>
                    <th>Is Accessible</th>
                    <th>Date Listed</th>
                </tr>
                <?php foreach ($rooms as $rental): ?>
                    <tr>
                        <td>
                            <?php echo htmlspecialchars($rental['id']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($rental['rental_type']); ?>
                        </td>
                        <td>
                            <?php
                            $ownersArray = fetchOwnersNames($rental['id']);
                            $ownersArray = array_map(function ($owner) {
                                return $owner['first_name'] . ' ' . $owner['last_name'];
                            }, $ownersArray);
                            $ownersString = implode(', ', $ownersArray);
                            echo htmlspecialchars($ownersString ?? 'N/A');
                            ?>
                        </td>
                        <td>
                            <?php
                            $managerArray = fetchManagerNames($rental['id']);
                            $managerArray = array_map(function ($manager) {
                                return $manager['first_name'] . ' ' . $manager['last_name'];
                            }, $managerArray);
                            $managerString = implode(', ', $managerArray);
                            echo htmlspecialchars($managerString ?? 'N/A');
                            ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($rental['rent']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($rental['num_bedrooms']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($rental['num_bathrooms']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($rental['has_parking']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($rental['laundry_type']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($rental['is_accessible']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($rental['date_listed']); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <div class="<?php echo $activeTab == 'SearchResults' ? 'tabcontent active' : 'tabcontent'; ?>">
            <h2>Search Results</h2>
            <?php if (!empty($searchResults)): ?>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Phone</th>
                    </tr>
                    <?php foreach ($searchResults as $person): ?>
                        <tr>
                            <td>
                                <?php echo htmlspecialchars($person['id']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($person['first_name']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($person['last_name']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($person['phone_num']); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p>No results found.</p>
            <?php endif; ?>
        </div>

    </div>
    <div
        style="margin: 50px; width: auto; height: auto; border: 1px solid #ccc; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2); border-radius: 10px; padding: 20px;">
        <h2>Average Monthly Rent</h2>
        <table>
            <tr>
                <th>Houses</th>
                <th>Apartments</th>
                <th>Rooms</th>
            </tr>
            <?php if (!empty($averageRents) && count($averageRents) >= 3): ?>
                <tr>
                    <td>
                        <?php echo htmlspecialchars(number_format((float) $averageRents[0]['average_rent'], 2, '.', '')); ?>$
                    </td>
                    <td>
                        <?php echo htmlspecialchars(number_format((float) $averageRents[1]['average_rent'], 2, '.', '')); ?>$
                    </td>
                    <td>
                        <?php echo htmlspecialchars(number_format((float) $averageRents[2]['average_rent'], 2, '.', '')); ?>$
                    </td>
                </tr>
            <?php endif; ?>
        </table>

    </div>

    <div
        style="margin: 50px; width: auto; height: auto; border: 1px solid #ccc; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2); border-radius: 10px; padding: 20px;">
        <h2>Rental Groups</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($groups as $group): ?>
                <tr>
                    <td>
                        <?php echo htmlspecialchars($group['id']); ?>
                    </td>
                    <td class="form-actions">
                        <form action="pages/updatePreferences.php" method="post">
                            <input type="hidden" name="group_id" value="<?php echo htmlspecialchars($group['id']); ?>">
                            <input type="submit" class="form-action-button submit" value="Update Preferences">
                        </form>
                        <a href="pages/groupDetail.php?group_id=<?php echo urlencode($group['id']); ?>"
                            class="form-action-button details">View Details</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <footer>
        <p>&copy;
            <?php echo date('Y'); ?> Queens Rental Application
        </p>
        <p>By: Luke Gannon 20366499, CISC 332</p>
    </footer>
</body>

</html>