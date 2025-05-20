<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "workers_association";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
include 'WAsform.php';

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
                document.addEventListener('DOMContentLoaded', function() {
                    // Dim the background
                    const overlay = document.createElement('div');
                    overlay.style.position = 'fixed';
                    overlay.style.top = '0';
                    overlay.style.left = '0';
                    overlay.style.width = '100%';
                    overlay.style.height = '100%';
                    overlay.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
                    overlay.style.zIndex = '999';
                    document.body.appendChild(overlay);

                    const popup = document.createElement('div');
                    popup.style.position = 'fixed';
                    popup.style.top = '50%';
                    popup.style.left = '50%';
                    popup.style.transform = 'translate(-50%, -50%)';
                    popup.style.backgroundColor = 'white';
                    popup.style.border = '2px solid #4CAF50';
                    popup.style.borderRadius = '10px';
                    popup.style.padding = '20px';
                    popup.style.boxShadow = '0 4px 8px rgba(0, 0, 0, 0.2)';
                    popup.style.textAlign = 'center';
                    popup.style.zIndex = '1000';

                    const message = document.createElement('h2');
                    message.textContent = 'Form Submitted Successfully!';
                    popup.appendChild(message);

                    const backButton = document.createElement('button');
                    backButton.textContent = 'Back to Data';
                    backButton.style.margin = '10px';
                    backButton.style.padding = '10px 20px';
                    backButton.style.border = 'none';
                    backButton.style.borderRadius = '5px';
                    backButton.style.backgroundColor = '#333';
                    backButton.style.color = 'white';
                    backButton.style.cursor = 'pointer';
                    backButton.onclick = function() {
                        window.location.href = 'data.php';
                    };
                    popup.appendChild(backButton);

                    const newFormButton = document.createElement('button');
                    newFormButton.textContent = 'Submit Another Form';
                    newFormButton.style.margin = '10px';
                    newFormButton.style.padding = '10px 20px';
                    newFormButton.style.border = 'none';
                    newFormButton.style.borderRadius = '5px';
                    newFormButton.style.backgroundColor = '#4CAF50';
                    newFormButton.style.color = 'white';
                    newFormButton.style.cursor = 'pointer';
                    newFormButton.onclick = function() {
                        window.location.href = 'form.php';
                    };
                    popup.appendChild(newFormButton);

                    document.body.appendChild(popup);
                });
              </script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>