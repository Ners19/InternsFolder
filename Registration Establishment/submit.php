<?php
$servername = "localhost";
$username = "root";
$password = ""; // Ensure this matches your MySQL setup
$database = "registry_of_establishment"; 
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
    if (empty($_POST["ein"]) || empty($_POST["establishment_name"]) || empty($_POST["street"]) || empty($_POST["city"]) || empty($_POST["province"])) {
        die("Error: Required fields are missing.");
    }

    $ein = mysqli_real_escape_string($conn, $_POST["ein"]);
    $establishment_name = mysqli_real_escape_string($conn, $_POST["establishment_name"]);
    $street = mysqli_real_escape_string($conn, $_POST["street"]);
    $city = mysqli_real_escape_string($conn, $_POST["city"]);
    $province = mysqli_real_escape_string($conn, $_POST["province"]);
    $tin = mysqli_real_escape_string($conn, $_POST["tin"] ?? null);
    $telephone = mysqli_real_escape_string($conn, $_POST["telephone"] ?? null);
    $fax = mysqli_real_escape_string($conn, $_POST["fax"] ?? null);
    $email = mysqli_real_escape_string($conn, $_POST["email"] ?? null);
    $manager_owner = mysqli_real_escape_string($conn, $_POST["manager_owner"] ?? null);
    $business_nature = mysqli_real_escape_string($conn, $_POST["business_nature"] ?? null);
    $business_nature_other = mysqli_real_escape_string($conn, $_POST["business_nature_other"] ?? null);
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
    $labor_union = mysqli_real_escape_string($conn, $_POST["labor_union"] ?? null);
    $blr_certification = mysqli_real_escape_string($conn, $_POST["blr_certification"] ?? null);
    $chemicals_used = mysqli_real_escape_string($conn, $_POST["chemicals_used"] ?? null);
    $parent_establishment = mysqli_real_escape_string($conn, $_POST["parent_establishment"] ?? null);
    $branch_street = mysqli_real_escape_string($conn, $_POST["branch_street"] ?? null);
    $branch_city = mysqli_real_escape_string($conn, $_POST["branch_city"] ?? null);
    $branch_province = mysqli_real_escape_string($conn, $_POST["branch_province"] ?? null);
    $capitalization = mysqli_real_escape_string($conn, $_POST["capitalization"] ?? null);
    $total_assets = mysqli_real_escape_string($conn, $_POST["total_assets"] ?? null);
    $past_application_number = mysqli_real_escape_string($conn, $_POST["past_application_number"] ?? null);
    $past_application_date = mysqli_real_escape_string($conn, $_POST["past_application_date"] ?? null);
    $former_name = mysqli_real_escape_string($conn, $_POST["former_name"] ?? null);
    $past_address = mysqli_real_escape_string($conn, $_POST["past_address"] ?? null);
    $certification = isset($_POST["certification"]) ? 1 : 0;
    $owner_president = mysqli_real_escape_string($conn, $_POST["owner_president"] ?? null);
    $date_filed = mysqli_real_escape_string($conn, $_POST["date_filed"] ?? null);
    $date_approved = mysqli_real_escape_string($conn, $_POST["date_approved"] ?? null);
    $approved_by = mysqli_real_escape_string($conn, $_POST["approved_by"] ?? null);

    // Handle file upload
    $dti_permit_path = null;
    if (isset($_FILES['dti_permit']) && $_FILES['dti_permit']['error'] === 0) {
        $upload_dir = "uploads/";
        
        // Ensure the uploads directory exists
        if (!is_dir($upload_dir)) {
            if (!mkdir($upload_dir, 0777, true)) {
                die("Failed to create uploads directory.");
            }
        }

        $dti_permit_path = $upload_dir . basename($_FILES['dti_permit']['name']);
        if (!move_uploaded_file($_FILES['dti_permit']['tmp_name'], $dti_permit_path)) {
            die("Error uploading file.");
        }
    } else if (isset($_FILES['dti_permit']) && $_FILES['dti_permit']['error'] !== UPLOAD_ERR_NO_FILE) {
        die("File upload error: " . $_FILES['dti_permit']['error']);
    }

    $sql = "INSERT INTO `registry_of_establishment` 
            (`EIN`, `establishment_name`, `street`, `city`, `province`, `tin`, `telephone`, `fax`, `email`, `manager_owner`, `business_nature`, `business_nature_other`, 
            `filipino_male`, `resident_alien_male`, `non_resident_alien_male`, `below15_male`, `15to17_male`, `18to30_male`, `above30_male`, 
            `filipino_female`, `resident_alien_female`, `non_resident_alien_female`, `below15_female`, `15to17_female`, `18to30_female`, `above30_female`, 
            `labor_union`, `blr_certification`, `chemicals_used`, `parent_establishment`, `branch_street`, `branch_city`, `branch_province`, 
            `capitalization`, `total_assets`, `dti_permit_path`, `past_application_number`, `past_application_date`, `former_name`, `past_address`, 
            `certification`, `owner_president`, `date_filed`, `date_approved`, `approved_by`) 
            VALUES 
            ('$ein', '$establishment_name', '$street', '$city', '$province', '$tin', '$telephone', '$fax', '$email', '$manager_owner', '$business_nature', '$business_nature_other', 
            '$filipino_male', '$resident_alien_male', '$non_resident_alien_male', '$below15_male', '$male_15to17', '$male_18to30', '$above30_male', 
            '$filipino_female', '$resident_alien_female', '$non_resident_alien_female', '$below15_female', '$female_15to17', '$female_18to30', '$above30_female', 
            '$labor_union', '$blr_certification', '$chemicals_used', '$parent_establishment', '$branch_street', '$branch_city', '$branch_province', 
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
