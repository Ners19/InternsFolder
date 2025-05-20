<?php
$servername = "localhost";
$username = "root";
$password = ""; // Ensure this matches your MySQL setup
$database = "workers_association"; // Use the workers_association database

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    die("Method Not Allowed");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate required fields
    if (empty($_POST["ein"]) || empty($_POST["establishment_name"]) || empty($_POST["street"]) || empty($_POST["city"]) || empty($_POST["branch_province"])) {
        die("Error: Required fields are missing.");
    }

    // Sanitize inputs
    $ein = filter_var($_POST["ein"], FILTER_SANITIZE_STRING);
    $establishment_name = filter_var($_POST["establishment_name"], FILTER_SANITIZE_STRING);
    $street = filter_var($_POST["street"], FILTER_SANITIZE_STRING);
    $city = filter_var($_POST["city"], FILTER_SANITIZE_STRING);
    $branch_province = filter_var($_POST["branch_province"], FILTER_SANITIZE_STRING);
    $tin = filter_var($_POST["tin"] ?? null, FILTER_SANITIZE_STRING);
    $telephone = filter_var($_POST["telephone"] ?? null, FILTER_SANITIZE_STRING);
    $fax = filter_var($_POST["fax"] ?? null, FILTER_SANITIZE_STRING);
    $email = filter_var($_POST["email"] ?? null, FILTER_SANITIZE_EMAIL);
    $manager_owner = filter_var($_POST["manager_owner"] ?? null, FILTER_SANITIZE_STRING);
    $business_nature = filter_var($_POST["business_nature"] ?? null, FILTER_SANITIZE_STRING);
    $business_nature_other = filter_var($_POST["business_nature_other"] ?? null, FILTER_SANITIZE_STRING);
    $filipino_male = (int) ($_POST["filipino_male"] ?? 0);
    $resident_alien_male = (int) ($_POST["resident_alien_male"] ?? 0);
    $non_resident_alien_male = (int) ($_POST["non_resident_alien_male"] ?? 0);
    $below15_male = (int) ($_POST["below15_male"] ?? 0);
    $male_15to17 = (int) ($_POST["15to17_male"] ?? 0);
    $male_18to30 = (int) ($_POST["18to30_male"] ?? 0);
    $above30_male = (int) ($_POST["above30_male"] ?? 0);
    $filipino_female = (int) ($_POST["filipino_female"] ?? 0);
    $resident_alien_female = (int) ($_POST["resident_alien_female"] ?? 0);
    $non_resident_alien_female = (int) ($_POST["non_resident_alien_female"] ?? 0);
    $below15_female = (int) ($_POST["below15_female"] ?? 0);
    $female_15to17 = (int) ($_POST["15to17_female"] ?? 0);
    $female_18to30 = (int) ($_POST["18to30_female"] ?? 0);
    $above30_female = (int) ($_POST["above30_female"] ?? 0);
    $labor_union = filter_var($_POST["labor_union"] ?? null, FILTER_SANITIZE_STRING);
    $blr_certification = filter_var($_POST["blr_certification"] ?? null, FILTER_SANITIZE_STRING);
    $chemicals_used = filter_var($_POST["chemicals_used"] ?? null, FILTER_SANITIZE_STRING);
    $parent_establishment = filter_var($_POST["parent_establishment"] ?? null, FILTER_SANITIZE_STRING);
    $branch_street = filter_var($_POST["branch_street"] ?? null, FILTER_SANITIZE_STRING);
    $branch_city = filter_var($_POST["branch_city"] ?? null, FILTER_SANITIZE_STRING);
    $capitalization = filter_var($_POST["capitalization"] ?? null, FILTER_SANITIZE_STRING);
    $total_assets = filter_var($_POST["total_assets"] ?? null, FILTER_SANITIZE_STRING);
    $past_application_number = filter_var($_POST["past_application_number"] ?? null, FILTER_SANITIZE_STRING);
    $past_application_date = filter_var($_POST["past_application_date"] ?? null, FILTER_SANITIZE_STRING);
    $former_name = filter_var($_POST["former_name"] ?? null, FILTER_SANITIZE_STRING);
    $past_address = filter_var($_POST["past_address"] ?? null, FILTER_SANITIZE_STRING);
    $certification = isset($_POST["certification"]) ? 1 : 0;
    $owner_president = filter_var($_POST["owner_president"] ?? null, FILTER_SANITIZE_STRING);
    $date_filed = filter_var($_POST["date_filed"] ?? null, FILTER_SANITIZE_STRING);
    $date_approved = filter_var($_POST["date_approved"] ?? null, FILTER_SANITIZE_STRING);
    $approved_by = filter_var($_POST["approved_by"] ?? null, FILTER_SANITIZE_STRING);

    // Handle file upload
    $dti_permit_path = null;
    if (isset($_FILES['dti_permit']) && $_FILES['dti_permit']['error'] === 0) {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
        $fileExtension = strtolower(pathinfo($_FILES['dti_permit']['name'], PATHINFO_EXTENSION));

        if (!in_array($fileExtension, $allowedExtensions)) {
            die("Invalid file type. Only JPG, PNG, and PDF files are allowed.");
        }

        $upload_dir = "uploads/";
        
        // Ensure the uploads directory exists
        if (!is_dir($upload_dir)) {
            if (!mkdir($upload_dir, 0777, true)) {
                die("Failed to create uploads directory.");
            }
        }

        $dti_permit_path = $upload_dir . uniqid() . '.' . $fileExtension;
        if (!move_uploaded_file($_FILES['dti_permit']['tmp_name'], $dti_permit_path)) {
            die("Error uploading file.");
        }
    } else if (isset($_FILES['dti_permit']) && $_FILES['dti_permit']['error'] !== UPLOAD_ERR_NO_FILE) {
        die("File upload error: " . $_FILES['dti_permit']['error']);
    }

    $sql = "INSERT INTO `registry_of_establishment` 
            (`EIN`, `establishment_name`, `street`, `city`, `branch_province`, `tin`, `telephone`, `fax`, `email`, `manager_owner`, `business_nature`, `business_nature_other`, 
            `filipino_male`, `resident_alien_male`, `non_resident_alien_male`, `below15_male`, `15to17_male`, `18to30_male`, `above30_male`, 
            `filipino_female`, `resident_alien_female`, `non_resident_alien_female`, `below15_female`, `15to17_female`, `18to30_female`, `above30_female`, 
            `labor_union`, `blr_certification`, `chemicals_used`, `parent_establishment`, `branch_street`, `branch_city`, 
            `capitalization`, `total_assets`, `dti_permit_path`, `past_application_number`, `past_application_date`, `former_name`, `past_address`, 
            `certification`, `owner_president`, `date_filed`, `date_approved`, `approved_by`) 
            VALUES 
            ('$ein', '$establishment_name', '$street', '$city', '$branch_province', '$tin', '$telephone', '$fax', '$email', '$manager_owner', '$business_nature', '$business_nature_other', 
            '$filipino_male', '$resident_alien_male', '$non_resident_alien_male', '$below15_male', '$male_15to17', '$male_18to30', '$above30_male', 
            '$filipino_female', '$resident_alien_female', '$non_resident_alien_female', '$below15_female', '$female_15to17', '$female_18to30', '$above30_female', 
            '$labor_union', '$blr_certification', '$chemicals_used', '$parent_establishment', '$branch_street', '$branch_city', 
            '$capitalization', '$total_assets', '$dti_permit_path', '$past_application_number', '$past_application_date', '$former_name', '$past_address', 
            '$certification', '$owner_president', '$date_filed', '$date_approved', '$approved_by')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
                alert('Form Submitted Successfully!');
                window.location.href = 'form.php';
              </script>";
    } else {
        die("Error inserting data: " . mysqli_error($conn));
    }

    mysqli_close($conn);
}
?>
