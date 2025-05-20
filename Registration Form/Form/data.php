<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit;
}

include 'database.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : '';

$sql = "SELECT id, Registration_Certification, applicantname, applicantaddress, email, landline, mobile, presidentfirstname, presidentmiddlename, presidentlastname, presidentaddress, presidentemail, presidentlandline, presidentmobile, dateorganized, dateofcblratification, placesofoperation, no_of_male, no_of_female, total, gender, dateaccomplished, otheroccupation, occupation, attachment, last_updated 
        FROM register 
        WHERE (applicantname LIKE '%$search%' 
        OR applicantaddress LIKE '%$search%' 
        OR email LIKE '%$search%' 
        OR landline LIKE '%$search%' 
        OR mobile LIKE '%$search%' 
        OR presidentfirstname LIKE '%$search%' 
        OR presidentmiddlename LIKE '%$search%' 
        OR presidentlastname LIKE '%$search%' 
        OR presidentemail LIKE '%$search%' 
        OR presidentlandline LIKE '%$search%' 
        OR presidentmobile LIKE '%$search%' 
        OR placesofoperation LIKE '%$search%' 
        OR gender LIKE '%$search%' 
        OR occupation LIKE '%$search%' 
        OR otheroccupation LIKE '%$search%')";

if ($startDate && $endDate) {
    $sql .= " AND dateaccomplished BETWEEN '$startDate' AND '$endDate'";
}

$sql .= " ORDER BY $sort";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Worker's Association Registered Data</title>
    <link rel="icon" href="dole.png" type="image/png">
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
            position: relative;
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
            padding: 12px 16px; /* Increased padding for more space */
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            /* Optionally, add more spacing for headers */
        }
        td {
            vertical-align: top;
            /* Optionally, add more spacing for data cells */
        }
        .search-container, .date-filter-container {
            display: flex;
            justify-content: center;
            margin-bottom: 10px;
        }
        .search-container input[type="text"], 
        .date-filter-container input[type="date"] {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-right: 10px;
        }
        .date-filter-container button {
            padding: 8px 12px;
            background-color: #4A6FA5;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .date-filter-container button:hover {
            background-color: #3A5F85;
        }
        .add-new-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #4A6FA5;
            color: white;
            padding: 8px 12px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: bold;
            border: none;
            cursor: pointer;
        }
        .add-new-btn:hover {
            background-color: #3A5F85;
        }
    </style>
</head>
<body>
<div class="sidebar">
    <h5>Admin Dashboard</h5>
    <a href="admin.php">
        <img src="home.png" alt="Home" class="home-icon"> Home
    </a>
    <a href="statistics.php">
        <img src="statics.png" alt="Statistics" class="statistics-icon"> Statistics
    </a>
    <a href="data.php">
        <img src="data.png" alt="Data" class="data-icon"> Workers Association Data
    </a>
    <a href="Registration Establishment/registered_data.php">
        <img src="data.png" alt="Data" class="data-icon"> Registered Data of Establishment
    </a>
    <a href="settings.php">
        <img src="settings.png" alt="Settings" class="setting-icon"> Settings
    </a>
    <a href="logout.php" onclick="confirmLogout(event)">
        <img src="logout.png" alt="Logout" class="logout-icon"> Logout
    </a>
</div>
<div class="main-content">
    <div class="header">
        <h1>Worker's Association Registered Data</h1>
        <a href="WAsform.php" class="add-new-btn">+ Add New</a>
    </div>
    <div class="section">
        <div class="search-container">
            <form method="get" action="data.php">
                <input type="text" name="search" placeholder="Search..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit">Search</button>
            </form>
        </div>
        <div class="date-filter-container">
            <form method="get" action="data.php">
                <label for="start_date">Start Date:</label>
                <input type="date" id="start_date" name="start_date" value="<?php echo $startDate; ?>">
                <label for="end_date">End Date:</label>
                <input type="date" id="end_date" name="end_date" value="<?php echo $endDate; ?>">
                <button type="submit">Filter</button>
                <a href="export.php?start_date=<?php echo $startDate; ?>&end_date=<?php echo $endDate; ?>" target="_blank">Export to Excel</a>
            </form>
        </div>
        <div class="sort-container">
            <select id="sortSelect" onchange="sortTable()">
                <option value="id">ID</option>
                <option value="Registration_Certification">Registration Certification</option>
                <option value="applicantname">Applicant Name</option>
                <option value="applicantaddress">Applicant Address</option>
                <option value="email">Email</option>
                <option value="landline">Landline</option>
                <option value="mobile">Mobile</option>
                <option value="presidentfirstname">President First Name</option>
                <option value="presidentmiddlename">President Middle Name</option>
                <option value="presidentlastname">President Last Name</option>
                <option value="presidentaddress">President Address</option>
                <option value="presidentemail">President Email</option>
                <option value="presidentlandline">President Landline</option>
                <option value="presidentmobile">President Mobile</option>
                <option value="dateorganized">Date Organized</option>
                <option value="dateofcblratification">Date of CBL Ratification</option>
                <option value="placesofoperation">Places of Operation</option>
                <option value="gender">Gender</option>
                <option value="dateaccomplished">Date Accomplished</option>
                <option value="otheroccupation">Other Occupation</option>
                <option value="occupation">Occupation</option>
                <option value="no_of_male">No. of Male</option>
                <option value="no_of_female">No. of Female</option>
                <option value="total">Total</option>
            </select>
        </div>
        <table>
            <thead>
                <tr>
                    <!-- Removed the ID column header -->
                    <th class="<?php echo $sort == 'Registration_Certification' ? 'bold' : ''; ?>">Registration Certification</th>
                    <th class="<?php echo $sort == 'applicantname' ? 'bold' : ''; ?>">Applicant Name</th>
                    <th class="<?php echo $sort == 'applicantaddress' ? 'bold' : ''; ?>">Applicant Address</th>
                    <th class="<?php echo $sort == 'email' ? 'bold' : ''; ?>">Email</th>
                    <th class="<?php echo $sort == 'landline' ? 'bold' : ''; ?>">Landline</th>
                    <th class="<?php echo $sort == 'mobile' ? 'bold' : ''; ?>">Mobile</th>
                    <th class="<?php echo $sort == 'presidentfirstname' ? 'bold' : ''; ?>">President First Name</th>
                    <th class="<?php echo $sort == 'presidentmiddlename' ? 'bold' : ''; ?>">President Middle Name</th>
                    <th class="<?php echo $sort == 'presidentlastname' ? 'bold' : ''; ?>">President Last Name</th>
                    <th class="<?php echo $sort == 'presidentaddress' ? 'bold' : ''; ?>">President Address</th>
                    <th class="<?php echo $sort == 'presidentemail' ? 'bold' : ''; ?>">President Email</th>
                    <th class="<?php echo $sort == 'presidentlandline' ? 'bold' : ''; ?>">President Landline</th>
                    <th class="<?php echo $sort == 'presidentmobile' ? 'bold' : ''; ?>">President Mobile</th>
                    <th class="<?php echo $sort == 'dateorganized' ? 'bold' : ''; ?>">Date Organized</th>
                    <th class="<?php echo $sort == 'dateofcblratification' ? 'bold' : ''; ?>">Date of CBL Ratification</th>
                    <th class="<?php echo $sort == 'placesofoperation' ? 'bold' : ''; ?>">Places of Operation</th>
                    <th class="<?php echo $sort == 'gender' ? 'bold' : ''; ?>">Gender</th>
                    <th class="<?php echo $sort == 'dateaccomplished' ? 'bold' : ''; ?>">Date Accomplished</th>
                    <th class="<?php echo $sort == 'otheroccupation' ? 'bold' : ''; ?>">Other Occupation</th>
                    <th class="<?php echo $sort == 'occupation' ? 'bold' : ''; ?>">Occupation</th>
                    <th class="<?php echo $sort == 'no_of_male' ? 'bold' : ''; ?>">No. of Male</th>
                    <th class="<?php echo $sort == 'no_of_female' ? 'bold' : ''; ?>">No. of Female</th>
                    <th class="<?php echo $sort == 'total' ? 'bold' : ''; ?>">Total</th>
                    <th>Attachment</th>
                    <th>Last Updated</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        // Removed the ID column data
                        echo "<td class='" . ($sort == 'Registration_Certification' ? 'bold' : '') . "'>" . $row["Registration_Certification"] . "</td>";
                        echo "<td class='" . ($sort == 'applicantname' ? 'bold' : '') . "'>" . $row["applicantname"] . "</td>";
                        echo "<td class='" . ($sort == 'applicantaddress' ? 'bold' : '') . "'>" . $row["applicantaddress"] . "</td>";
                        echo "<td class='" . ($sort == 'email' ? 'bold' : '') . "'>" . $row["email"] . "</td>";
                        echo "<td class='" . ($sort == 'landline' ? 'bold' : '') . "'>" . $row["landline"] . "</td>";
                        echo "<td class='" . ($sort == 'mobile' ? 'bold' : '') . "'>" . $row["mobile"] . "</td>";
                        echo "<td class='" . ($sort == 'presidentfirstname' ? 'bold' : '') . "'>" . $row["presidentfirstname"] . "</td>";
                        echo "<td class='" . ($sort == 'presidentmiddlename' ? 'bold' : '') . "'>" . $row["presidentmiddlename"] . "</td>";
                        echo "<td class='" . ($sort == 'presidentlastname' ? 'bold' : '') . "'>" . $row["presidentlastname"] . "</td>";
                        echo "<td class='" . ($sort == 'presidentaddress' ? 'bold' : '') . "'>" . $row["presidentaddress"] . "</td>";
                        echo "<td class='" . ($sort == 'presidentemail' ? 'bold' : '') . "'>" . $row["presidentemail"] . "</td>";
                        echo "<td class='" . ($sort == 'presidentlandline' ? 'bold' : '') . "'>" . $row["presidentlandline"] . "</td>";
                        echo "<td class='" . ($sort == 'presidentmobile' ? 'bold' : '') . "'>" . $row["presidentmobile"] . "</td>";
                        echo "<td class='" . ($sort == 'dateorganized' ? 'bold' : '') . "'>" . $row["dateorganized"] . "</td>";
                        echo "<td class='" . ($sort == 'dateofcblratification' ? 'bold' : '') . "'>" . $row["dateofcblratification"] . "</td>";
                        echo "<td class='" . ($sort == 'placesofoperation' ? 'bold' : '') . "'>" . $row["placesofoperation"] . "</td>";
                        echo "<td class='" . ($sort == 'gender' ? 'bold' : '') . "'>" . $row["gender"] . "</td>";
                        echo "<td class='" . ($sort == 'dateaccomplished' ? 'bold' : '') . "'>" . $row["dateaccomplished"] . "</td>";
                        echo "<td class='" . ($sort == 'otheroccupation' ? 'bold' : '') . "'>" . $row["otheroccupation"] . "</td>";
                        echo "<td class='" . ($sort == 'occupation' ? 'bold' : '') . "'>" . $row["occupation"] . "</td>";
                        echo "<td class='" . ($sort == 'no_of_male' ? 'bold' : '') . "'>" . $row["no_of_male"] . "</td>";
                        echo "<td class='" . ($sort == 'no_of_female' ? 'bold' : '') . "'>" . $row["no_of_female"] . "</td>";
                        echo "<td class='" . ($sort == 'total' ? 'bold' : '') . "'>" . $row["total"] . "</td>";
                        echo "<td>";
                        if ($row["attachment"]) {
                            echo "<button type='button' onclick=\"openPreview('preview_attachment.php?id=" . $row["id"] . "')\">Preview</button>";
                        } else {
                            echo "No attachment";
                        }
                        echo "</td>";
                        // New Last Updated column
                        echo "<td>";
                        if (!empty($row["last_updated"])) {
                            echo "Updated on " . date("M d, Y h:i A", strtotime($row["last_updated"]));
                        } else {
                            echo "-";
                        }
                        echo "</td>";
                        echo "<td><a href='update.php?id=" . $row["id"] . "'>Edit</a> | <a href='delete.php?id=" . $row["id"] . "' onclick='return confirm(\"Are you sure you want to delete this record?\");'>Delete</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='23'>No records found</td></tr>"; // Adjusted colspan
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<script>
function openPreview(url) {
    // Create modal if not exists
    let modal = document.getElementById('previewModal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'previewModal';
        modal.style.position = 'fixed';
        modal.style.top = '0';
        modal.style.left = '0';
        modal.style.width = '100vw';
        modal.style.height = '100vh';
        modal.style.background = 'rgba(0,0,0,0.7)';
        modal.style.display = 'flex';
        modal.style.alignItems = 'center';
        modal.style.justifyContent = 'center';
        modal.style.zIndex = '9999';
        modal.innerHTML = `
            <div style="background:#fff;max-width:90vw;max-height:90vh;padding:10px;position:relative;">
                <button onclick="document.getElementById('previewModal').remove()" style="position:absolute;top:5px;right:5px;">Close</button>
                <iframe id="previewFrame" src="" style="width:80vw;height:80vh;border:none;"></iframe>
            </div>
        `;
        document.body.appendChild(modal);
    }
    document.getElementById('previewFrame').src = url;
}
</script>
</body>
</html>