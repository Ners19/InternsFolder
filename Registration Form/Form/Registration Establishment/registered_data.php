<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit;
}

// Include the database connection file
include '../database.php';

if (!$conn) {
    die("Database connection not established. Please check the database.php file.");
}

// Sanitize search and sort inputs
$search = mysqli_real_escape_string($conn, $_GET['search'] ?? '');
$sort = mysqli_real_escape_string($conn, $_GET['sort'] ?? 'id');

// Validate sort column
$allowedSortColumns = ['id', 'EIN', 'establishment_name', 'city', 'branch_province', 'email'];
if (!in_array($sort, $allowedSortColumns)) {
    $sort = 'id';
}

$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Fetch data from the database
// Correct table name and column names
$sql = "SELECT * FROM `registry_of_establishment` 
        WHERE (`EIN` LIKE '%$search%' 
        OR `establishment_name` LIKE '%$search%' 
        OR `city` LIKE '%$search%' 
        OR `branch_province` LIKE '%$search%' 
        OR `email` LIKE '%$search%')";

if ($startDate && $endDate) {
    $sql .= " AND `date_registered` BETWEEN '$startDate' AND '$endDate'";
}

$sql .= " ORDER BY `$sort`";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM `registry_of_establishment` WHERE `id` = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    header("Location: registered_data.php");
    exit;
}

// Secure file preview
function sanitizeFilePath($filePath) {
    return htmlspecialchars($filePath, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Data of Establishments</title>
    <link rel="icon" href="../dole.png" type="image/png">
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            height: 100vh;
            margin: 0;
            overflow: hidden;
        }
        .sidebar {
            width: 150px;
            background-color: #4A6FA5;
            color: white;
            display: flex;
            flex-direction: column;
            padding: 10px;
            height: 100vh;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 8px 0;
            margin: 8px 0;
            font-size: 14px;
            display: flex;
            align-items: center;
        }
        .sidebar a img {
            margin-right: 8px;
            width: 20px;
            height: 20px;
        }
        .sidebar a:hover {
            background-color: #3A5F85;
        }
        .main-content {
            flex: 1;
            padding: 10px;
            background-color: #f9f9f9;
            overflow-y: auto;
        }
        .header {
            background-color: #E3F2FD;
            color: #333;
            padding: 10px;
            text-align: center;
            font-size: 18px;
        }
        .section {
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
        }
        table, th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<div class="sidebar">
    <h5>Admin Dashboard</h5>
    <a href="../admin.php">
        <img src="../home.png" alt="Home" class="home-icon"> Home
    </a>
    <a href="../statistics.php">
        <img src="../statics.png" alt="Statistics" class="statistics-icon"> Statistics
    </a>
    <a href="../data.php">
        <img src="../data.png" alt="Data" class="data-icon"> Workers Association Data
    </a>
    <a href="../Registration Establishment/registered_data.php">
        <img src="data.png" alt="Data" class="data-icon"> Registered Data of Establishment
    </a>
    <a href="../settings.php">
        <img src="../settings.png" alt="Settings" class="setting-icon"> Settings
    </a>
    <a href="../logout.php" onclick="confirmLogout(event)">
        <img src="../logout.png" alt="Logout" class="logout-icon"> Logout
    </a>
</div>
<div class="main-content">
    <div class="header">
        <h1>Registered Data of Establishments</h1>
        <a href="form.php" style="position: absolute; top: 10px; right: 10px; background-color: lightblue; padding: 10px 15px; text-decoration: none; color: black; border-radius: 5px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); font-size: 14px; font-weight: bold; border: 1px solid black;">
            + Add New
        </a>
    </div>
    <div class="section">
        <h2>Registered Data</h2>
        <div class="search-container">
            <form method="get" action="registered_data.php">
                <input type="text" name="search" placeholder="Search..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit">Search</button>
            </form>
        </div>
        <div class="date-filter-container">
            <form method="get" action="registered_data.php">
                <label for="start_date">Start Date:</label>
                <input type="date" id="start_date" name="start_date" value="<?php echo $startDate; ?>">
                <label for="end_date">End Date:</label>
                <input type="date" id="end_date" name="end_date" value="<?php echo $endDate; ?>">
                <button type="submit">Filter</button>
            </form>
        </div>
        <table>
            <thead>
                <tr>
                    <th>EIN</th>
                    <th>Establishment Name</th>
                    <th>Street</th>
                    <th>City</th>
                    <th>Branch Province</th>
                    <th>TIN</th>
                    <th>Telephone</th>
                    <th>Fax</th>
                    <th>Email</th>
                    <th>Manager/Owner</th>
                    <th>Business Nature</th>
                    <th>Business Nature Other</th>
                    <th>Filipino Male</th>
                    <th>Resident Alien Male</th>
                    <th>Non-Resident Alien Male</th>
                    <th>Below 15 Male</th>
                    <th>15 to 17 Male</th>
                    <th>18 to 30 Male</th>
                    <th>Above 30 Male</th>
                    <th>Total Male</th>
                    <th>Filipino Female</th>
                    <th>Resident Alien Female</th>
                    <th>Non-Resident Alien Female</th>
                    <th>Below 15 Female</th>
                    <th>15 to 17 Female</th>
                    <th>18 to 30 Female</th>
                    <th>Above 30 Female</th>
                    <th>Total Female</th>
                    <th>Grand Total Filipinos</th>
                    <th>Grand Total Resident Alien</th>
                    <th>Grand Total Non-Resident Alien</th>
                    <th>Grand Total Below 15</th>
                    <th>Grand Total 15 to 17</th>
                    <th>Grand Total 18 to 30</th>
                    <th>Grand Total Above 30</th>
                    <th>Grand Total All</th>
                    <th>Labor Union</th>
                    <th>BLR Registration</th>
                    <th>Machinery Equipment</th>
                    <th>Materials Handling</th>
                    <th>Chemicals Used</th>
                    <th>Parent Establishment</th>
                    <th>Branch Street</th>
                    <th>Branch City</th>
                    <th>Branch Province</th>
                    <th>Capitalization</th>
                    <th>Total Assets</th>
                    <th>DTI Permit Path</th>
                    <th>Past Application Number</th>
                    <th>Past Application Date</th>
                    <th>Former Name</th>
                    <th>Past Address</th>
                    <th>Certification</th>
                    <th>Owner/President</th>
                    <th>Date Filed</th>
                    <th>Date Approved</th>
                    <th>Approved By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['EIN']); ?></td>
                            <td><?php echo htmlspecialchars($row['establishment_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['street']); ?></td>
                            <td><?php echo htmlspecialchars($row['city']); ?></td>
                            <td><?php echo htmlspecialchars($row['branch_province']); ?></td>
                            <td><?php echo htmlspecialchars($row['tin']); ?></td>
                            <td><?php echo htmlspecialchars($row['telephone']); ?></td>
                            <td><?php echo htmlspecialchars($row['fax']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['manager_owner']); ?></td>
                            <td><?php echo htmlspecialchars($row['business_nature']); ?></td>
                            <td><?php echo htmlspecialchars($row['business_nature_other']); ?></td>
                            <td><?php echo htmlspecialchars($row['filipino_male']); ?></td>
                            <td><?php echo htmlspecialchars($row['resident_alien_male']); ?></td>
                            <td><?php echo htmlspecialchars($row['non_resident_alien_male']); ?></td>
                            <td><?php echo htmlspecialchars($row['below15_male']); ?></td>
                            <td><?php echo htmlspecialchars($row['15to17_male']); ?></td>
                            <td><?php echo htmlspecialchars($row['18to30_male']); ?></td>
                            <td><?php echo htmlspecialchars($row['above30_male']); ?></td>
                            <td><?php echo htmlspecialchars($row['total_male']); ?></td>
                            <td><?php echo htmlspecialchars($row['filipino_female']); ?></td>
                            <td><?php echo htmlspecialchars($row['resident_alien_female']); ?></td>
                            <td><?php echo htmlspecialchars($row['non_resident_alien_female']); ?></td>
                            <td><?php echo htmlspecialchars($row['below15_female']); ?></td>
                            <td><?php echo htmlspecialchars($row['15to17_female']); ?></td>
                            <td><?php echo htmlspecialchars($row['18to30_female']); ?></td>
                            <td><?php echo htmlspecialchars($row['above30_female']); ?></td>
                            <td><?php echo htmlspecialchars($row['total_female']); ?></td>
                            <td><?php echo htmlspecialchars($row['grand_total_filipinos']); ?></td>
                            <td><?php echo htmlspecialchars($row['grand_total_resident_alien']); ?></td>
                            <td><?php echo htmlspecialchars($row['grand_total_non_resident_alien']); ?></td>
                            <td><?php echo htmlspecialchars($row['grand_total_below15']); ?></td>
                            <td><?php echo htmlspecialchars($row['grand_total_15to17']); ?></td>
                            <td><?php echo htmlspecialchars($row['grand_total_18to30']); ?></td>
                            <td><?php echo htmlspecialchars($row['grand_total_above30']); ?></td>
                            <td><?php echo htmlspecialchars($row['grand_total_all']); ?></td>
                            <td><?php echo htmlspecialchars($row['labor_union']); ?></td>
                            <td><?php echo htmlspecialchars($row['blr_certification']); ?></td>
                            <td><?php echo htmlspecialchars($row['machinery_equipmen']); ?></td>
                            <td><?php echo htmlspecialchars($row['materials_handling']); ?></td>
                            <td><?php echo htmlspecialchars($row['chemicals_used']); ?></td>
                            <td><?php echo htmlspecialchars($row['parent_establishment']); ?></td>
                            <td><?php echo htmlspecialchars($row['branch_street']); ?></td>
                            <td><?php echo htmlspecialchars($row['branch_city']); ?></td>
                            <td><?php echo htmlspecialchars($row['branch_province']); ?></td>
                            <td><?php echo htmlspecialchars($row['capitalization']); ?></td>
                            <td><?php echo htmlspecialchars($row['total_assets']); ?></td>
                            <td>
                                <?php if (!empty($row['dti_permit_path'])): ?>
                                    <?php 
                                    $filePath = 'uploads/' . sanitizeFilePath($row['dti_permit_path']);
                                    $fileType = pathinfo($filePath, PATHINFO_EXTENSION);
                                    ?>
                                    <button type="button" onclick="openPreviewModal('<?php echo $filePath; ?>', '<?php echo $fileType; ?>')" style="color: white; background-color: blue; border: none; padding: 5px 10px; border-radius: 5px; cursor: pointer; font-size: 12px;">
                                        Preview
                                    </button>
                                <?php else: ?>
                                    No File
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($row['past_application_number']); ?></td>
                            <td><?php echo htmlspecialchars($row['past_application_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['former_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['past_address']); ?></td>
                            <td><?php echo htmlspecialchars($row['certification']); ?></td>
                            <td><?php echo htmlspecialchars($row['owner_president']); ?></td>
                            <td><?php echo htmlspecialchars($row['date_filed']); ?></td>
                            <td><?php echo htmlspecialchars($row['date_approved']); ?></td>
                            <td><?php echo htmlspecialchars($row['approved_by']); ?></td>
                            <td class="actions">
                                <a href="edit.php?id=<?php echo $row['id']; ?>">Edit</a>
                                <a href="registered_data.php?delete_id=<?php echo $row['id']; ?>" class="delete" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="58" style="text-align: center;">No records found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal for preview -->
<div id="previewModal" class="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.8); z-index: 1000; justify-content: center; align-items: center;">
    <div class="modal-content" style="background: white; padding: 20px; border-radius: 8px; max-width: 95%; max-height: 95%; overflow: auto; position: relative; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
        <button onclick="closePreviewModal()" style="position: absolute; top: 10px; right: 10px; background: red; color: white; border: none; padding: 10px 15px; cursor: pointer; border-radius: 5px; font-size: 16px;">Close</button>
        <div id="previewContent" style="text-align: center; max-height: 85%; overflow: auto;"></div>
    </div>
</div>

<script>
    function openPreviewModal(filePath, fileType) {
        const previewContent = document.getElementById('previewContent');
        if (['jpg', 'jpeg', 'png', 'gif'].includes(fileType.toLowerCase())) {
            previewContent.innerHTML = `<img src="${filePath}" style="max-width: 100%; max-height: 100%;">`;
        } else if (fileType.toLowerCase() === 'pdf') {
            previewContent.innerHTML = `<embed src="${filePath}" type="application/pdf" width="100%" height="600px">`;
        } else {
            previewContent.innerHTML = `<a href="${filePath}" target="_blank" style="font-size: 18px; color: blue; text-decoration: underline;">Download File</a>`;
        }
        const modal = document.getElementById('previewModal');
        modal.style.display = 'flex';
        modal.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function closePreviewModal() {
        document.getElementById('previewModal').style.display = 'none';
    }
</script>
</body>
</html>
