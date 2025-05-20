<?php
// Database configuration
$host = 'localhost';
$dbname = 'registry_of_establishment';
$username = 'root';
$password = '';

// Create a connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Function to insert data into the database
function insertRegistry($data) {
    global $pdo;
    try {
        // Validate required fields
        if (empty($data['EIN']) || empty($data['establishment_name']) || empty($data['street']) || empty($data['city']) || empty($data['province'])) {
            throw new Exception("Error: Required fields are missing.");
        }

        $sql = "INSERT INTO `registry_of_establishment` 
                (`EIN`, `establishment_name`, `street`, `city`, `province`, `tin`, `telephone`, `fax`, `email`, `manager_owner`, `business_nature`, `business_nature_other`, 
                `filipino_male`, `resident_alien_male`, `non_resident_alien_male`, `below15_male`, `15to17_male`, `18to30_male`, `above30_male`, 
                `filipino_female`, `resident_alien_female`, `non_resident_alien_female`, `below15_female`, `15to17_female`, `18to30_female`, `above30_female`, 
                `labor_union`, `blr_certification`, `chemicals_used`, `parent_establishment`, `branch_street`, `branch_city`, `branch_province`, 
                `capitalization`, `total_assets`, `dti_permit_path`, `past_application_number`, `past_application_date`, `former_name`, `past_address`, 
                `certification`, `owner_president`, `date_filed`, `date_approved`, `approved_by`) 
                VALUES 
                (:EIN, :establishment_name, :street, :city, :province, :tin, :telephone, :fax, :email, :manager_owner, :business_nature, :business_nature_other, 
                :filipino_male, :resident_alien_male, :non_resident_alien_male, :below15_male, :15to17_male, :18to30_male, :above30_male, 
                :filipino_female, :resident_alien_female, :non_resident_alien_female, :below15_female, :15to17_female, :18to30_female, :above30_female, 
                :labor_union, :blr_certification, :chemicals_used, :parent_establishment, :branch_street, :branch_city, :branch_province, 
                :capitalization, :total_assets, :dti_permit_path, :past_application_number, :past_application_date, :former_name, :past_address, 
                :certification, :owner_president, :date_filed, :date_approved, :approved_by)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);
    } catch (Exception $e) {
        die("Error inserting data: " . $e->getMessage());
    }
}

// Function to fetch all records
function fetchAllRegistries() {
    global $pdo;
    $sql = "SELECT * FROM `registry_of_establishment`";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to update a record
function updateRegistry($data, $id) {
    global $pdo;
    try {
        $sql = "UPDATE `registry_of_establishment` SET 
                `EIN` = :EIN, `establishment_name` = :establishment_name, `street` = :street, `city` = :city, `province` = :province, 
                `tin` = :tin, `telephone` = :telephone, `fax` = :fax, `email` = :email, `manager_owner` = :manager_owner, 
                `business_nature` = :business_nature, `business_nature_other` = :business_nature_other, 
                `filipino_male` = :filipino_male, `resident_alien_male` = :resident_alien_male, `non_resident_alien_male` = :non_resident_alien_male, 
                `below15_male` = :below15_male, `15to17_male` = :15to17_male, `18to30_male` = :18to30_male, `above30_male` = :above30_male, 
                `filipino_female` = :filipino_female, `resident_alien_female` = :resident_alien_female, `non_resident_alien_female` = :non_resident_alien_female, 
                `below15_female` = :below15_female, `15to17_female` = :15to17_female, `18to30_female` = :18to30_female, `above30_female` = :above30_female, 
                `labor_union` = :labor_union, `blr_certification` = :blr_certification, `chemicals_used` = :chemicals_used, 
                `parent_establishment` = :parent_establishment, `branch_street` = :branch_street, `branch_city` = :branch_city, `branch_province` = :branch_province, 
                `capitalization` = :capitalization, `total_assets` = :total_assets, `dti_permit_path` = :dti_permit_path, 
                `past_application_number` = :past_application_number, `past_application_date` = :past_application_date, 
                `former_name` = :former_name, `past_address` = :past_address, `certification` = :certification, `owner_president` = :owner_president, 
                `date_filed` = :date_filed, `date_approved` = :date_approved, `approved_by` = :approved_by 
                WHERE `id` = :id";
        $stmt = $pdo->prepare($sql);
        $data['id'] = $id;
        $stmt->execute($data);
    } catch (PDOException $e) {
        die("Error updating data: " . $e->getMessage());
    }
}

// Function to delete a record
function deleteRegistry($id) {
    global $pdo;
    $sql = "DELETE FROM `registry_of_establishment` WHERE `id` = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
}
?>
