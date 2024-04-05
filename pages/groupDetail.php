<?php
require_once '../functions.php';
$group_id = isset($_GET['group_id']) ? $_GET['group_id'] : 'Not provided';
$group = fetchGroupbyID($group_id);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rental Group
        <?php echo $group_id ?>
    </title>
    <link rel="stylesheet" type="text/css" href="../style.css">

</head>

<body style="display: flex; flex-direction: column; gap: 2px; align-items: start;">
    <h1>Rental Group
        <?php
        $group_id = isset($_GET['group_id']) ? $_GET['group_id'] : 'Not provided';
        echo htmlspecialchars($group_id);
        ?>
    </h1>

    <div style="display: flex; gap: 50px; justify-content: center; align-items: start">
        <div
            style="display: flex; flex-direction: column; gap: 2px; align-items: start; border: 1px solid #ccc; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2); border-radius: 10px; padding: 20px; width: 300px; height: auto;">
            <h2>Group Preferences</h2>
            <p>
                <?php
                if (!empty($group)) {
                    echo 'Rental Type: ' . htmlspecialchars($group['rental_type']) . '<br>';
                    echo 'Accessibility: ' . ($group['is_accessible'] ? 'Yes' : 'No') . '<br>';
                    echo 'Parking: ' . ($group['has_parking'] ? 'Yes' : 'No') . '<br>';
                    echo 'Number of Bedrooms: ' . htmlspecialchars($group['num_bedrooms']) . '<br>';
                    echo 'Number of Bathrooms: ' . htmlspecialchars($group['num_bathrooms']) . '<br>';
                    echo 'Rent Price: $' . htmlspecialchars($group['rent_price']) . '<br>';
                    echo 'Date Listed: ' . htmlspecialchars($group['date_listed']) . '<br>';
                    echo 'Laundry Type: ' . htmlspecialchars($group['laundry_type']) . '<br>';
                } else {
                    echo 'Group not found';
                }
                ?>
            </p>
        </div>
        <div
            style="display: flex; flex-direction: column; gap: 2px; align-items: start; border: 1px solid #ccc; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2); border-radius: 10px; padding: 20px; width: 300px; height: auto;">
            <h2>Group Lease</h2>
            <p>
                <?php
                $lease = fetchGroupLease($group_id);
                $rental = fetchRentalbyID($lease['rental_id']);
                if (!empty($lease)) {
                    echo 'Lease Start Date: ' . htmlspecialchars($lease['date_signed']) . '<br>';
                    echo 'Lease End Date: ' . htmlspecialchars($lease['end_date']) . '<br>';
                    echo 'Lease Price: $' . htmlspecialchars($lease['rent_price']) . '<br>';
                    echo 'Rental Address: ' . htmlspecialchars($rental['street']) . ', ' . htmlspecialchars($rental['city']) . ', ' . htmlspecialchars($rental['province']) . ', ' . htmlspecialchars($rental['pc']) . '<br>';
                    echo 'Rental Type: ' . htmlspecialchars($rental['rental_type']) . '<br>';
                    echo 'Number of Bedrooms: ' . htmlspecialchars($rental['num_bedrooms']) . '<br>';
                    echo 'Number of Bathrooms: ' . htmlspecialchars($rental['num_bathrooms']) . '<br>';
                    echo 'Parking: ' . ($rental['has_parking'] ? 'Yes' : 'No') . '<br>';
                    echo 'Accessible: ' . ($rental['is_accessible'] ? 'Yes' : 'No') . '<br>';
                    echo 'Laundry: ' . htmlspecialchars($rental['laundry_type']) . '<br>';

                } else {
                    echo 'Lease not found';
                }
                ?>
            </p>
        </div>
    </div>

    <div
        style="display: flex; flex-direction: column; margin-top: 50px; gap: 2px; align-items: start; border: 1px solid #ccc; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2); border-radius: 10px; padding: 20px; width: auto; height: auto;">

        <h2>Group Members</h2>
        <table>
            <tr>
                <th>Name</th>
                <th>Grad Year</th>
                <th>Program</th>
                <th>Phone</th>
            </tr>
            <?php
            $members = fetchGroupMembers($group_id);
            if (!empty($members) && count($members) > 0) {
                foreach ($members as $member) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($member['first_name']) . '</td>';
                    echo '<td>' . htmlspecialchars($member['grad_year']) . '</td>';
                    echo '<td>' . htmlspecialchars($member['program']) . '</td>';
                    echo '<td>' . htmlspecialchars($member['phone_num']) . '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="5">No members found</td></tr>';
            }
            ?>
        </table>


    </div>

</body>

</html>