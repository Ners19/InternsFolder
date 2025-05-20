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

// Fetch the record to edit
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$sql = "SELECT * FROM `registry_of_establishment` WHERE `id` = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$record = $result->fetch_assoc();

if (!$record) {
    die("Record not found.");
}

// Ensure all keys exist in the $record array to avoid undefined array key warnings
$record = array_merge([
    'province' => '',
    'dti_permit_path' => '',
    // Add other keys with default values as needed
], $record);     

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize inputs
    $data = [
        'EIN' => filter_var($_POST["ein"], FILTER_SANITIZE_STRING),
        'establishment_name' => filter_var($_POST["establishment_name"], FILTER_SANITIZE_STRING),
        'street' => filter_var($_POST["street"], FILTER_SANITIZE_STRING),
        'city' => filter_var($_POST["city"], FILTER_SANITIZE_STRING),
        'tin' => filter_var($_POST["tin"] ?? null, FILTER_SANITIZE_STRING),
        'telephone' => filter_var($_POST["telephone"] ?? null, FILTER_SANITIZE_STRING),
        'fax' => filter_var($_POST["fax"] ?? null, FILTER_SANITIZE_STRING),
        'email' => filter_var($_POST["email"] ?? null, FILTER_SANITIZE_EMAIL),
        'manager_owner' => filter_var($_POST["manager_owner"] ?? null, FILTER_SANITIZE_STRING),
        'business_nature' => filter_var($_POST["business_nature"] ?? null, FILTER_SANITIZE_STRING),
        'business_nature_other' => filter_var($_POST["business_nature_other"] ?? null, FILTER_SANITIZE_STRING),
        'filipino_male' => (int) ($_POST["filipino_male"] ?? 0),
        'resident_alien_male' => (int) ($_POST["resident_alien_male"] ?? 0),
        'non_resident_alien_male' => (int) ($_POST["non_resident_alien_male"] ?? 0),
        'below15_male' => (int) ($_POST["below15_male"] ?? 0),
        '15to17_male' => (int) ($_POST["15to17_male"] ?? 0),
        '18to30_male' => (int) ($_POST["18to30_male"] ?? 0),
        'above30_male' => (int) ($_POST["above30_male"] ?? 0),
        'total_male' => (int) ($_POST["total_male"] ?? 0),
        'filipino_female' => (int) ($_POST["filipino_female"] ?? 0),
        'resident_alien_female' => (int) ($_POST["resident_alien_female"] ?? 0),
        'non_resident_alien_female' => (int) ($_POST["non_resident_alien_female"] ?? 0),
        'below15_female' => (int) ($_POST["below15_female"] ?? 0),
        '15to17_female' => (int) ($_POST["15to17_female"] ?? 0),
        '18to30_female' => (int) ($_POST["18to30_female"] ?? 0),
        'above30_female' => (int) ($_POST["above30_female"] ?? 0),
        'total_female' => (int) ($_POST["total_female"] ?? 0),
        'grand_total_filipinos' => (int) ($_POST["grand_total_filipinos"] ?? 0),
        'grand_total_resident_alien' => (int) ($_POST["grand_total_resident_alien"] ?? 0),
        'grand_total_non_resident_alien' => (int) ($_POST["grand_total_non_resident_alien"] ?? 0),
        'grand_total_below15' => (int) ($_POST["grand_total_below15"] ?? 0),
        'grand_total_15to17' => (int) ($_POST["grand_total_15to17"] ?? 0),
        'grand_total_18to30' => (int) ($_POST["grand_total_18to30"] ?? 0),
        'grand_total_above30' => (int) ($_POST["grand_total_above30"] ?? 0),
        'grand_total_all' => (int) ($_POST["grand_total_all"] ?? 0),
        'labor_union' => filter_var($_POST["labor_union"] ?? null, FILTER_SANITIZE_STRING),
        'blr_certification' => filter_var($_POST["blr_certification"] ?? null, FILTER_SANITIZE_STRING),
        'machinery_equipmen' => filter_var($_POST["machinery_equipmen"] ?? null, FILTER_SANITIZE_STRING),
        'materials_handling' => filter_var($_POST["materials_handling"] ?? null, FILTER_SANITIZE_STRING),
        'chemicals_used' => filter_var($_POST["chemicals_used"] ?? null, FILTER_SANITIZE_STRING),
        'parent_establishment' => filter_var($_POST["parent_establishment"] ?? null, FILTER_SANITIZE_STRING),
        'branch_street' => filter_var($_POST["branch_street"] ?? null, FILTER_SANITIZE_STRING),
        'branch_city' => filter_var($_POST["branch_city"] ?? null, FILTER_SANITIZE_STRING),
        'branch_province' => filter_var($_POST["branch_province"] ?? null, FILTER_SANITIZE_STRING),
        'capitalization' => filter_var($_POST["capitalization"] ?? null, FILTER_SANITIZE_STRING),
        'total_assets' => filter_var($_POST["total_assets"] ?? null, FILTER_SANITIZE_STRING),
        'past_application_number' => filter_var($_POST["past_application_number"] ?? null, FILTER_SANITIZE_STRING),
        'past_application_date' => filter_var($_POST["past_application_date"] ?? null, FILTER_SANITIZE_STRING),
        'former_name' => filter_var($_POST["former_name"] ?? null, FILTER_SANITIZE_STRING),
        'past_address' => filter_var($_POST["past_address"] ?? null, FILTER_SANITIZE_STRING),
        'certification' => isset($_POST["certification"]) ? 1 : 0,
        'owner_president' => filter_var($_POST["owner_president"] ?? null, FILTER_SANITIZE_STRING),
        'date_filed' => filter_var($_POST["date_filed"] ?? null, FILTER_SANITIZE_STRING),
        'date_approved' => filter_var($_POST["date_approved"] ?? null, FILTER_SANITIZE_STRING),
        'approved_by' => filter_var($_POST["approved_by"] ?? null, FILTER_SANITIZE_STRING),
    ];

    // Handle file upload
    $dti_permit_path = $record['dti_permit_path'];
    if (isset($_FILES['dti_permit']) && $_FILES['dti_permit']['error'] === 0) {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
        $fileExtension = strtolower(pathinfo($_FILES['dti_permit']['name'], PATHINFO_EXTENSION));

        if (!in_array($fileExtension, $allowedExtensions)) {
            die("Invalid file type. Only JPG, PNG, and PDF files are allowed.");
        }

        $upload_dir = "uploads/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $dti_permit_path = $upload_dir . uniqid() . '.' . $fileExtension;
        if (!move_uploaded_file($_FILES['dti_permit']['tmp_name'], $dti_permit_path)) {
            die("Error uploading file.");
        }
    }

    $data['dti_permit_path'] = $dti_permit_path;

    // Include the updateRegistry function
    include '../database.php';

    // Update the record
    updateRegistry($data, $id);

    echo "<script>
            alert('Record updated successfully!');
            window.location.href = 'registered_data.php';
          </script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Record</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            width: 90%;
            max-width: 800px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            max-height: 90vh;
        }
        .header {
            background-color: #6A80B9;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .form-container {
            padding: 20px;
            overflow-y: auto;
            flex: 1;
        }
        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }
        input, select, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            margin-top: 20px;
            padding: 10px 20px;     
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Edit Record</h1>
        </div>
        <div class="form-container">
            <form action="edit.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $record['id']; ?>">
                <label for="ein">EIN:</label>
                <input type="text" name="ein" id="ein" value="<?php echo htmlspecialchars($record['EIN']); ?>" required>

                <label for="establishment_name">Establishment Name:</label>
                <input type="text" name="establishment_name" id="establishment_name" value="<?php echo htmlspecialchars($record['establishment_name']); ?>" required>

                <label for="street">Street:</label>
                <input type="text" name="street" id="street" value="<?php echo htmlspecialchars($record['street']); ?>" required>

                <label for="city">City:</label>
                <input type="text" name="city" id="city" value="<?php echo htmlspecialchars($record['city']); ?>" required>

                <label for="branch_province">Branch Province:</label>
                <input type="text" name="branch_province" id="branch_province" value="<?php echo htmlspecialchars($record['branch_province']); ?>" required>

                <label for="tin">TIN:</label>
                <input type="text" name="tin" id="tin" value="<?php echo htmlspecialchars($record['tin']); ?>">

                <label for="telephone">Telephone:</label>
                <input type="text" name="telephone" id="telephone" value="<?php echo htmlspecialchars($record['telephone']); ?>">

                <label for="fax">Fax:</label>
                <input type="text" name="fax" id="fax" value="<?php echo htmlspecialchars($record['fax']); ?>">

                <label for="email">Email:</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($record['email']); ?>">

                <label for="manager_owner">Manager/Owner:</label>
                <input type="text" name="manager_owner" id="manager_owner" value="<?php echo htmlspecialchars($record['manager_owner']); ?>">

                <label for="business_nature">Business Nature:</label>
                <input type="text" name="business_nature" id="business_nature" value="<?php echo htmlspecialchars($record['business_nature']); ?>">

                <label for="business_nature_other">Other Business Nature:</label>
                <input type="text" name="business_nature_other" id="business_nature_other" value="<?php echo htmlspecialchars($record['business_nature_other']); ?>">

                <label for="dti_permit">DTI Permit:</label>
                <input type="file" name="dti_permit" id="dti_permit">
                <?php if (!empty($record['dti_permit_path'])): ?>
                    <a href="<?php echo htmlspecialchars($record['dti_permit_path']); ?>" target="_blank">View Current File</a>
                <?php endif; ?>

                <label for="filipino_male">Filipino Male:</label>
                <input type="number" name="filipino_male" id="filipino_male" value="<?php echo htmlspecialchars($record['filipino_male']); ?>">

                <label for="resident_alien_male">Resident Alien Male:</label>
                <input type="number" name="resident_alien_male" id="resident_alien_male" value="<?php echo htmlspecialchars($record['resident_alien_male']); ?>">

                <label for="non_resident_alien_male">Non-Resident Alien Male:</label>
                <input type="number" name="non_resident_alien_male" id="non_resident_alien_male" value="<?php echo htmlspecialchars($record['non_resident_alien_male']); ?>">

                <label for="below15_male">Below 15 Male:</label>
                <input type="number" name="below15_male" id="below15_male" value="<?php echo htmlspecialchars($record['below15_male']); ?>">

                <label for="15to17_male">15 to 17 Male:</label>
                <input type="number" name="15to17_male" id="15to17_male" value="<?php echo htmlspecialchars($record['15to17_male']); ?>">

                <label for="18to30_male">18 to 30 Male:</label>
                <input type="number" name="18to30_male" id="18to30_male" value="<?php echo htmlspecialchars($record['18to30_male']); ?>">

                <label for="above30_male">Above 30 Male:</label>
                <input type="number" name="above30_male" id="above30_male" value="<?php echo htmlspecialchars($record['above30_male']); ?>">

                <label for="total_male">Total Male:</label>
                <input type="number" name="total_male" id="total_male" value="<?php echo htmlspecialchars($record['total_male']); ?>">

                <label for="filipino_female">Filipino Female:</label>
                <input type="number" name="filipino_female" id="filipino_female" value="<?php echo htmlspecialchars($record['filipino_female']); ?>">

                <label for="resident_alien_female">Resident Alien Female:</label>
                <input type="number" name="resident_alien_female" id="resident_alien_female" value="<?php echo htmlspecialchars($record['resident_alien_female']); ?>">

                <label for="non_resident_alien_female">Non-Resident Alien Female:</label>
                <input type="number" name="non_resident_alien_female" id="non_resident_alien_female" value="<?php echo htmlspecialchars($record['non_resident_alien_female']); ?>">

                <label for="below15_female">Below 15 Female:</label>
                <input type="number" name="below15_female" id="below15_female" value="<?php echo htmlspecialchars($record['below15_female']); ?>">

                <label for="15to17_female">15 to 17 Female:</label>
                <input type="number" name="15to17_female" id="15to17_female" value="<?php echo htmlspecialchars($record['15to17_female']); ?>">

                <label for="18to30_female">18 to 30 Female:</label>
                <input type="number" name="18to30_female" id="18to30_female" value="<?php echo htmlspecialchars($record['18to30_female']); ?>">

                <label for="above30_female">Above 30 Female:</label>
                <input type="number" name="above30_female" id="above30_female" value="<?php echo htmlspecialchars($record['above30_female']); ?>">

                <label for="total_female">Total Female:</label>
                <input type="number" name="total_female" id="total_female" value="<?php echo htmlspecialchars($record['total_female']); ?>">

                <label for="grand_total_filipinos">Grand Total Filipinos:</label>
                <input type="number" name="grand_total_filipinos" id="grand_total_filipinos" value="<?php echo htmlspecialchars($record['grand_total_filipinos']); ?>">

                <label for="grand_total_resident_alien">Grand Total Resident Alien:</label>
                <input type="number" name="grand_total_resident_alien" id="grand_total_resident_alien" value="<?php echo htmlspecialchars($record['grand_total_resident_alien']); ?>">

                <label for="grand_total_non_resident_alien">Grand Total Non-Resident Alien:</label>
                <input type="number" name="grand_total_non_resident_alien" id="grand_total_non_resident_alien" value="<?php echo htmlspecialchars($record['grand_total_non_resident_alien']); ?>">

                <label for="grand_total_below15">Grand Total Below 15:</label>
                <input type="number" name="grand_total_below15" id="grand_total_below15" value="<?php echo htmlspecialchars($record['grand_total_below15']); ?>">

                <label for="grand_total_15to17">Grand Total 15 to 17:</label>
                <input type="number" name="grand_total_15to17" id="grand_total_15to17" value="<?php echo htmlspecialchars($record['grand_total_15to17']); ?>">

                <label for="grand_total_18to30">Grand Total 18 to 30:</label>
                <input type="number" name="grand_total_18to30" id="grand_total_18to30" value="<?php echo htmlspecialchars($record['grand_total_18to30']); ?>">

                <label for="grand_total_above30">Grand Total Above 30:</label>
                <input type="number" name="grand_total_above30" id="grand_total_above30" value="<?php echo htmlspecialchars($record['grand_total_above30']); ?>">

                <label for="grand_total_all">Grand Total All:</label>
                <input type="number" name="grand_total_all" id="grand_total_all" value="<?php echo htmlspecialchars($record['grand_total_all']); ?>">

                <label for="labor_union">Labor Union:</label>
                <input type="text" name="labor_union" id="labor_union" value="<?php echo htmlspecialchars($record['labor_union']); ?>">

                <label for="blr_certification">BLR Certification:</label>
                <input type="text" name="blr_certification" id="blr_certification" value="<?php echo htmlspecialchars($record['blr_certification']); ?>">

                <label for="machinery_equipmen">Machinery Equipment:</label>
                <input type="text" name="machinery_equipmen" id="machinery_equipmen" value="<?php echo htmlspecialchars($record['machinery_equipmen']); ?>">

                <label for="materials_handling">Materials Handling:</label>
                <input type="text" name="materials_handling" id="materials_handling" value="<?php echo htmlspecialchars($record['materials_handling']); ?>">

                <label for="chemicals_used">Chemicals Used:</label>
                <input type="text" name="chemicals_used" id="chemicals_used" value="<?php echo htmlspecialchars($record['chemicals_used']); ?>">

                <label for="parent_establishment">Parent Establishment:</label>
                <input type="text" name="parent_establishment" id="parent_establishment" value="<?php echo htmlspecialchars($record['parent_establishment']); ?>">

                <label for="branch_street">Branch Street:</label>
                <input type="text" name="branch_street" id="branch_street" value="<?php echo htmlspecialchars($record['branch_street']); ?>">

                <label for="branch_city">Branch City:</label>
                <input type="text" name="branch_city" id="branch_city" value="<?php echo htmlspecialchars($record['branch_city']); ?>">

                <label for="capitalization">Capitalization:</label>
                <input type="text" name="capitalization" id="capitalization" value="<?php echo htmlspecialchars($record['capitalization']); ?>">

                <label for="total_assets">Total Assets:</label>
                <input type="text" name="total_assets" id="total_assets" value="<?php echo htmlspecialchars($record['total_assets']); ?>">

                <label for="past_application_number">Past Application Number:</label>
                <input type="text" name="past_application_number" id="past_application_number" value="<?php echo htmlspecialchars($record['past_application_number']); ?>">

                <label for="past_application_date">Past Application Date:</label>
                <input type="text" name="past_application_date" id="past_application_date" value="<?php echo htmlspecialchars($record['past_application_date']); ?>">

                <label for="former_name">Former Name:</label>
                <input type="text" name="former_name" id="former_name" value="<?php echo htmlspecialchars($record['former_name']); ?>">

                <label for="past_address">Past Address:</label>
                <input type="text" name="past_address" id="past_address" value="<?php echo htmlspecialchars($record['past_address']); ?>">

                <label for="certification">Certification:</label>
                <input type="checkbox" name="certification" id="certification" <?php echo $record['certification'] ? 'checked' : ''; ?>>

                <label for="owner_president">Owner/President:</label>
                <input type="text" name="owner_president" id="owner_president" value="<?php echo htmlspecialchars($record['owner_president']); ?>">

                <label for="date_filed">Date Filed:</label>
                <input type="text" name="date_filed" id="date_filed" value="<?php echo htmlspecialchars($record['date_filed']); ?>">

                <label for="date_approved">Date Approved:</label>
                <input type="text" name="date_approved" id="date_approved" value="<?php echo htmlspecialchars($record['date_approved']); ?>">

                <label for="approved_by">Approved By:</label>
                <input type="text" name="approved_by" id="approved_by" value="<?php echo htmlspecialchars($record['approved_by']); ?>">

                <button type="submit">Update</button>
            </form>
        </div>
    </div>
</body>
</html>
