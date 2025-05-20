<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "workers_association";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $address = $_POST["address"];
    $email = $_POST["email"];
    $landline = $_POST["landline"];
    $mobile = $_POST["mobile"];
    $firstName = $_POST["firstName"];
    $middleName = $_POST["middleName"];
    $lastName = $_POST["lastName"];
    $presidentEmail = $_POST["presidentEmail"];
    $presidentLandline = $_POST["presidentLandline"];
    $presidentMobile = $_POST["presidentMobile"];
    $gender = $_POST["gender"];
    $dateOrganized = $_POST["dateOrganized"];
    $dateCBLRatification = isset($_POST["dateCBLRatification"]) ? $_POST["dateCBLRatification"] : '';
    $placeOperation = $_POST["placeOperation"];
    $occupation = isset($_POST["occupation"]) ? $_POST["occupation"] : '';
    $otherOccupation = $_POST["otherOccupation"];
    $no_of_male = $_POST["no_of_male"];
    $no_of_female = $_POST["no_of_female"];
    $total = $_POST["total"];
    
    // Handle file upload
    $attachment = '';
    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == 0) {
        $attachment = addslashes(file_get_contents($_FILES['attachment']['tmp_name']));
    }

    $sql = "INSERT INTO register (applicantname, applicantaddress, email, landline, mobile, presidentfirstname, presidentmiddlename, presidentlastname, presidentaddress, presidentemail, presidentlandline, presidentmobile, dateorganized, dateofcblratification, placesofoperation, no_of_male, no_of_female, total, gender, dateaccomplished, otheroccupation, occupation, attachment) 
            VALUES ('$name', '$address', '$email', '$landline', '$mobile', '$firstName', '$middleName', '$lastName', '$address', '$presidentEmail', '$presidentLandline', '$presidentMobile', '$dateOrganized', '$dateCBLRatification', '$placeOperation', '$no_of_male', '$no_of_female', '$total', '$gender', NOW(), '$otherOccupation', '$occupation', '$attachment')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
                alert('Form Submitted Successfully!');
                window.location.href = 'WorkersAssociationForm.php'; // Redirect to WorkersAssociationForm.php
              </script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>
