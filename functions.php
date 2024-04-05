<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);



/**
 * Establishes a connection to the database.
 * 
 * @return PDO The database connection object.
 */
function getDatabaseConnection()
{
    $username = "root"; // Default XAMPP MySQL username
    $password = ""; // Default XAMPP MySQL password is empty
    $dbname = "rentalDB"; // Your database name
    try {
        $servername = "localhost"; // Assign the server name
        $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
        // Set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        die();
    }
}



/**
 * Fetches the average rent prices for each rental type from the rental table.
 * 
 * @return array An array of rental types and their average rent prices.
 */
function fetchAverageRents()
{
    $conn = getDatabaseConnection(); // Use the connection function

    // Update the SQL query to target the rental table and calculate the average rent
    $sql = "SELECT rental_type, AVG(rent) AS average_rent FROM rental GROUP BY rental_type";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // Fetch and return the average rents
        $averages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $averages;
    } catch (PDOException $e) {
        // It's better to handle the exception rather than just echoing it out,
        // for example, you could log the error and return an empty array or false.
        error_log($e->getMessage());
        return []; // Return an empty array indicating no data was found or an error occurred
    }
}

/**
 * Fetches all persons from the database.
 * 
 * @return array An array of persons with their details.
 */
function fetchPersons()
{
    $conn = getDatabaseConnection(); // Use the connection function
    $persons = [];
    try {
        $sql = "SELECT * FROM Person";
        $stmt = $conn->query($sql);
        $persons = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error fetching persons: " . $e->getMessage();
        die();
    }
    return $persons;
}

/**
 * Fetches all rental managers from the database.
 * 
 * @return array An array of rental managers with their details.
 */
function fetchManagers()
{
    $conn = getDatabaseConnection(); // Use the connection function
    $managers = [];
    try {
        $sql = "SELECT * FROM rental_manager";
        $stmt = $conn->query($sql);
        $managers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error fetching managers: " . $e->getMessage();
        die();
    }
    return $managers;
}

/**
 * Fetches all rental owners from the database.
 * 
 * @return array An array of rental owners with their details.
 */
function fetchOwners()
{
    $conn = getDatabaseConnection(); // Use the connection function
    $owners = [];
    try {
        $sql = "SELECT * FROM owns_rental";
        $stmt = $conn->query($sql);
        $owners = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error fetching owners: " . $e->getMessage();
        die();
    }
    return $owners;
}

/**
 * Fetches all owners of the given rental including the related first_name and last_name person object properties.
 * 
 * @return array An array of owners.
 */
function fetchOwnersNames($rental_id)
{
    $conn = getDatabaseConnection(); // Use the connection function
    $owners = [];
    try {
        $sql = "SELECT p.first_name, p.last_name FROM owns_rental o JOIN person p ON o.owner_id = p.id WHERE o.rental_id = :rental_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['rental_id' => $rental_id]);
        $owners = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching owners in fetchOwnersNames: " . $e->getMessage());
        echo "Error fetching owners: " . $e->getMessage();
        die();
    }
    error_log("Returning owners in fetchOwnersNames.");
    return $owners;
}

/**
 * Fetches all managers of the given rental including the related first_name and last_name person object properties.
 * 
 * @return array An array of managers.
 */
function fetchManagerNames($rental_id)
{
    $conn = getDatabaseConnection(); // Use the connection function
    $managers = [];
    try {
        $sql = "SELECT p.first_name, p.last_name FROM manages_rental m JOIN person p ON m.manager_id = p.id WHERE m.rental_id = :rental_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['rental_id' => $rental_id]);
        $managers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error fetching managers: " . $e->getMessage();
        die();
    }
    return $managers;
}


/**
 * Fetches all rental groups from the database.
 * 
 * @return array An array of rental groups with their details.
 */
function fetchGroups()
{
    $conn = getDatabaseConnection(); // Use the connection function
    $groups = [];
    try {
        $sql = "SELECT * FROM rental_group";
        $stmt = $conn->query($sql);
        $groups = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error fetching groups: " . $e->getMessage();
        die();
    }
    return $groups;
}

/**
 * Fetches a rental group by its ID.
 * 
 * @return array The rental group with its details.
 */
function fetchGroupbyID($id)
{
    $conn = getDatabaseConnection(); // Use the connection function
    $group = [];
    try {
        $sql = "SELECT * FROM rental_group WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        $group = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error fetching group: " . $e->getMessage();
        die();
    }
    return $group;
}

/**
 * Fetches all renters apart of a specific rental group from the database.
 * 
 * Note: All renters in this database are considered to be students.
 * 
 * @return array An array of renters with their details.
 */
function fetchGroupMembers($id)
{
    $conn = getDatabaseConnection(); // Use the connection function
    $members = [];
    try {
        $sql = "SELECT p.first_name, p.phone_num, r.grad_year, r.program FROM renter r INNER JOIN person p ON r.person_id = p.id WHERE r.rental_group_id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        $members = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error fetching group members: " . $e->getMessage();
        die();
    }
    return $members;
}


/**
 * Fetches all rentals from the database.
 * 
 * @return array An array of rentals with their details.
 */
function fetchRentals()
{
    $conn = getDatabaseConnection(); // Use the connection function
    $rentals = [];
    try {
        $sql = "SELECT * FROM rental";
        $stmt = $conn->query($sql);
        $rentals = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error fetching rentals: " . $e->getMessage();
        die();
    }
    return $rentals;
}

/**
 * Fetches a rental by its ID.
 * 
 * @return array The rental with its details.
 */
function fetchRentalById($id)
{
    $conn = getDatabaseConnection(); // Use the connection function
    $rental = [];
    try {
        $sql = "SELECT * FROM rental WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        $rental = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error fetching rental: " . $e->getMessage();
        die();
    }
    return $rental;
}

/**
 * Fetches all house rentals from the database.
 * 
 * @return array An array of house rentals with their details.
 */
function fetchHouseRentals()
{
    $conn = getDatabaseConnection(); // Use the connection function
    $rentals = [];
    try {
        $sql = "SELECT * FROM rental WHERE rental_type = 'house'";
        $stmt = $conn->query($sql);
        $rentals = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error fetching house rentals: " . $e->getMessage();
        die();
    }
    return $rentals;
}

/**
 * Fetches all apartment rentals from the database.
 * 
 * @return array An array of apartment rentals with their details.
 */
function fetchAppartmentRentals()
{
    $conn = getDatabaseConnection(); // Use the connection function
    $rentals = [];
    try {
        $sql = "SELECT * FROM rental WHERE rental_type = 'apartment'";
        $stmt = $conn->query($sql);
        $rentals = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error fetching apartment rentals: " . $e->getMessage();
        die();
    }
    return $rentals;
}

/**
 * Fetches all room rentals from the database.
 * 
 * @return array An array of room rentals with their details.
 */
function fetchRoomRentals()
{
    $conn = getDatabaseConnection(); // Use the connection function
    $rentals = [];
    try {
        $sql = "SELECT * FROM rental WHERE rental_type = 'room'";
        $stmt = $conn->query($sql);
        $rentals = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error fetching room rentals: " . $e->getMessage();
        die();
    }
    return $rentals;
}


function fetchGroupLease($id)
{
    $conn = getDatabaseConnection(); // Use the connection function
    $lease = [];
    try {
        $sql = "SELECT * FROM lease WHERE rental_group_id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        $lease = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error fetching group lease: " . $e->getMessage();
        die();
    }
    return $lease;
}

/**
 * Saves the new updated preferences for a rental group.
 * 
 * @param int $group_id The ID of the rental group.
 * @param array $data The new preferences data.
 */
function savePreferences($group_id, $data)
{
    // Get database connection
    $conn = getDatabaseConnection();

    // Prepare the update statement
    $sql = "UPDATE rental_group SET 
            rental_type = :rental_type, 
            is_accessible = :is_accessible,
            has_parking = :has_parking, 
            num_bedrooms = :num_bedrooms, 
            num_bathrooms = :num_bathrooms, 
            rent_price = :rent_price, 
            laundry_type = :laundry_type 
            WHERE id = :group_id";

    $stmt = $conn->prepare($sql);
    // Bind the parameters from the form
    $stmt->bindValue(':rental_type', $data['rental_type']);
    $stmt->bindValue(':is_accessible', isset($data['is_accessible']) ? 1 : 0);
    $stmt->bindValue(':has_parking', isset($data['has_parking']) ? 1 : 0);
    $stmt->bindValue(':num_bedrooms', $data['num_bedrooms']);
    $stmt->bindValue(':num_bathrooms', $data['num_bathrooms']);
    $stmt->bindValue(':rent_price', $data['rent_price']);
    $stmt->bindValue(':laundry_type', $data['laundry_type']);
    $stmt->bindValue(':group_id', $group_id);

    // Execute the update
    if ($stmt->execute()) {
        // Redirect to the home page or another page
        header('Location: /rental');
        exit;
    } else {
        // If there was an error, you can choose to display a message or redirect with an error flag
        header('Location: /rental?error=save');
        exit;
    }
}

/**
 * Searches the persons table by first name, last name, or phone number.
 * 
 * @param PDO $conn The database connection object.
 * @param string $searchTerm The search term entered by the user.
 * @return array An array of matching persons.
 */
function searchPersons($conn, $searchTerm)
{
    // Prepare the SQL query with placeholders for the search terms.
    $sql = "SELECT * FROM person 
            WHERE first_name LIKE :searchTerm 
               OR last_name LIKE :searchTerm 
               OR phone_num LIKE :searchTerm";

    // Prepare the statement.
    $stmt = $conn->prepare($sql);

    // Bind the search term to the placeholder in the SQL query.
    // The % signs are wildcards for the LIKE operator, allowing for any characters before and after the search term.
    $term = "%" . $searchTerm . "%";
    $stmt->bindParam(':searchTerm', $term);

    // Execute the query.
    $stmt->execute();

    // Fetch the results.
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $results;
}

/**
 * Inserts a new person into the database.
 * 
 * Note: There is currently no validation on the input data.
 * or duplicate checking. This should be added for a production application.
 * 
 * @param string $id The id of the person.
 * @param string $firstName The first name of the person.
 * @param string $lastName The last name of the person.
 * @param string $phoneNum The phone number of the person.
 * @return bool True if the insert was successful, false otherwise.
 */
function insertNewPerson($id, $firstName, $lastName, $phoneNum)
{
    try {
        $conn = getDatabaseConnection();
        $sql = "INSERT INTO person (id, first_name, last_name, phone_num) VALUES (:id, :first_name, :last_name, :phone_num)";
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':first_name', $firstName);
        $stmt->bindParam(':last_name', $lastName);
        $stmt->bindParam(':phone_num', $phoneNum);

        return $stmt->execute();
    } catch (PDOException $e) {
        error_log($e->getMessage()); // Log the error
        return false; // Return false to indicate failure
    }
}

/**
 * Inserts a new rental into the database.
 * 
 * Note: There is currently no validation on the input data.
 * or duplicate checking. This should be added for a production application.
 * 
 * @param string $rental_type The type of rental (house, apartment, room).
 * @param string $apt_num The apartment number.
 * @param string $street The street address.
 * @param string $city The city.
 * @param string $province The province.
 * @param string $pc The postal code.
 * @param int $num_bedrooms The number of bedrooms.
 * @param int $num_bathrooms The number of bathrooms.
 * @param string $laundry_type The laundry type (ensuite, shared).
 * @param bool $has_parking Whether the rental has parking.
 * @param bool $is_accessible Whether the rental is accessible.
 * @param float $rent The rent price.
 * @param string $date_listed The date the rental was listed.
 * @return int The ID of the new rental.
 */
function insertNewRental($rental_type, $apt_num, $street, $city, $province, $pc, $num_bedrooms, $num_bathrooms, $laundry_type, $has_parking, $is_accessible, $rent, $date_listed)
{
    try {
        $conn = getDatabaseConnection(); // Establish a database connection

        $sql = "INSERT INTO rental (rental_type, apt_num, street, city, province, pc, num_bedrooms, num_bathrooms, laundry_type, has_parking, is_accessible, rent, date_listed) 
            VALUES (:rental_type, :apt_num, :street, :city, :province, :pc, :num_bedrooms, :num_bathrooms, :laundry_type, :has_parking, :is_accessible, :rent, :date_listed)";

        $stmt = $conn->prepare($sql);
        // Bind the parameters
        $stmt->bindParam(':rental_type', $rental_type);
        $stmt->bindParam(':apt_num', $apt_num);
        $stmt->bindParam(':street', $street);
        $stmt->bindParam(':city', $city);
        $stmt->bindParam(':province', $province);
        $stmt->bindParam(':pc', $pc);
        $stmt->bindParam(':num_bedrooms', $num_bedrooms);
        $stmt->bindParam(':num_bathrooms', $num_bathrooms);
        $stmt->bindParam(':laundry_type', $laundry_type);
        $stmt->bindParam(':has_parking', $has_parking, PDO::PARAM_BOOL);
        $stmt->bindParam(':is_accessible', $is_accessible, PDO::PARAM_BOOL);
        $stmt->bindParam(':rent', $rent);
        $stmt->bindParam(':date_listed', $date_listed);

        // Execute the statement and return the new rental ID
        $stmt->execute();
        return $conn->lastInsertId();

    } catch (PDOException $e) {
        // Handle and log the error
        error_log($e->getMessage());
        return false;
    }
}

/**
 * Inserts a new rental_manager into the database.
 * 
 * Note: There is currently no validation on the input data.
 * or duplicate checking. This should be added for a production application.
 * 
 * @param int $person The ID of the person.
 * @param string $hire_date The hire date.
 * @param float $salary The salary of the manager.
 * @return bool True if the insert was successful, false otherwise.
 */
function insertNewManager($person_id, $hire_date, $salary)
{
    try {
        $conn = getDatabaseConnection();

        $sql = "INSERT INTO rental_manager (person_id, hire_date, salary) VALUES (:person_id, :hire_date, :salary)";

        $stmt = $conn->prepare($sql);

        // Bind the parameters
        $stmt->bindParam(':person_id', $person_id);
        $stmt->bindParam(':hire_date', $hire_date);
        $stmt->bindParam(':salary', $salary);

        // Execute the statement and return the result
        return $stmt->execute();
    } catch (PDOException $e) {
        // Handle and log the error
        error_log($e->getMessage());
        return false;
    }
}

/**
 * Inserts a new Owns_rental into the database.
 * 
 * Note: There is currently no validation on the input data.
 * or duplicate checking. This should be added for a production application.
 * 
 * @param int $owner_id The ID of the owner.
 * @param int $rental_id The ID of the rental.
 * @return bool True if the insert was successful, false otherwise.
 */
function insertNewOwnsRental($owner_id, $rental_id)
{
    try {
        $conn = getDatabaseConnection();

        $sql = "INSERT INTO owns_rental (owner_id, rental_id) VALUES (:owner_id, :rental_id)";

        $stmt = $conn->prepare($sql);

        // Bind the parameters
        $stmt->bindParam(':owner_id', $owner_id);
        $stmt->bindParam(':rental_id', $rental_id);

        // Execute the statement and return the result
        return $stmt->execute();
    } catch (PDOException $e) {
        // Handle and log the error
        error_log($e->getMessage());
        return false;
    }
}

/**
 * Inserts new Manages_rental into the database.
 * 
 * Note: There is currently no validation on the input data.
 * or duplicate checking. This should be added for a production application.
 * 
 * @param int $manager_id The ID of the manager.
 * @param string $date_started The date the manager started managing the rental.
 * @param int $rental_id The ID of the rental.
 * 
 * @return bool True if the insert was successful, false otherwise.
 * 
 */
function insertNewManagesRental($manager_id, $date_started, $rental_id)
{
    try {
        $conn = getDatabaseConnection();

        $sql = "INSERT INTO manages_rental (manager_id, date_started, rental_id) VALUES (:manager_id, :date_started, :rental_id)";

        $stmt = $conn->prepare($sql);

        // Bind the parameters
        $stmt->bindParam(':manager_id', $manager_id);
        $stmt->bindParam(':date_started', $date_started);
        $stmt->bindParam(':rental_id', $rental_id);

        // Execute the statement and return the result
        return $stmt->execute();
    } catch (PDOException $e) {
        // Handle and log the error
        error_log($e->getMessage());
        return false;
    }
}

?>