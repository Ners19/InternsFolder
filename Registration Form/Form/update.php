<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit;
}

include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $registrationCertification = $_POST["registrationCertification"]; // New field
    $name = $_POST["name"];
    $address = $_POST["address"];
    $email = $_POST["email"];
    $landline = $_POST["landline"]; // Keep the leading zero
    $mobile = $_POST["mobile"]; // Keep the leading zero
    $firstName = $_POST["firstName"];
    $middleName = $_POST["middleName"];
    $lastName = $_POST["lastName"];
    $presidentEmail = $_POST["presidentEmail"];
    $presidentLandline = $_POST["presidentLandline"]; // Keep the leading zero
    $presidentMobile = $_POST["presidentMobile"]; // Keep the leading zero
    $gender = $_POST["gender"];
    $dateOrganized = $_POST["dateOrganized"];
    $dateCBLRatification = isset($_POST["dateCBLRatification"]) ? $_POST["dateCBLRatification"] : '';
    $placeOperation = $_POST["placeOperation"];
    $occupation = isset($_POST["occupation"]) ? $_POST["occupation"] : '';
    $otherOccupation = $_POST["otherOccupation"];
    $dateAccomplished = $_POST["dateAccomplished"];
    $no_of_male = $_POST["no_of_male"];
    $no_of_female = $_POST["no_of_female"];
    $total = $_POST["total"];
    
    // Example: $sql = "UPDATE register SET applicantname=?, ... WHERE id=?";
    $sql = "UPDATE register SET 
        Registration_Certification='$registrationCertification', 
        applicantname='$name', 
        applicantaddress='$address', 
        email='$email', 
        landline='$landline', 
        mobile='$mobile', 
        presidentfirstname='$firstName', 
        presidentmiddlename='$middleName', 
        presidentlastname='$lastName', 
        presidentaddress='$address', 
        presidentemail='$presidentEmail', 
        presidentlandline='$presidentLandline', 
        presidentmobile='$presidentMobile', 
        dateorganized='$dateOrganized', 
        dateofcblratification='$dateCBLRatification', 
        placesofoperation='$placeOperation', 
        no_of_male='$no_of_male', 
        no_of_female='$no_of_female', 
        total='$total', 
        gender='$gender', 
        dateaccomplished='$dateAccomplished', 
        otheroccupation='$otherOccupation', 
        occupation='$occupation',
        last_updated=NOW()";

    // Handle file upload
    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == 0) {
        $attachment = addslashes(file_get_contents($_FILES['attachment']['tmp_name']));
        $sql .= ", attachment='$attachment'";
    }

    $sql .= " WHERE id='$id'";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Record updated successfully'); window.location.href = 'data.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    mysqli_close($conn);    
} else {
    $id = $_GET["id"];
    $sql = "SELECT * FROM register WHERE id='$id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Record</title>
    <link rel="icon" href="dole.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }
        .container {
            font-family: 'Poppins', sans-serif;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 700px;
            height: 100vh;
            overflow-y: scroll;
        }
        .header {
            font-family: 'Poppins', sans-serif;
            background-color: lightblue;
            color: black;
            padding: 15px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .section {
            font-family: 'Poppins', sans-serif;
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .form-group {
            font-family: 'Poppins', sans-serif;
            margin-bottom: 15px;
        }
        .form-group label {
            font-family: 'Poppins', sans-serif;
            display: block;
            margin-bottom: 5px;
        }
        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="date"],
        .form-group input[type="file"] {
            font-family: 'Poppins', sans-serif;
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        .form-group input[type="submit"] {
            font-family: 'Poppins', sans-serif;
            background-color: lightblue;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
        }
        .form-group input[type="submit"]:hover {
            font-family: 'Poppins', sans-serif;
            background-color: limegreen;
        }
        .back-link {
            font-family: 'Poppins', sans-serif;
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .back-link img {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Edit Record</h1>
        </div>
        <div class="section">
            <a href="data.php" class="back-link">
                <img src="back.png" alt="Logo" width="20" height="20"> Back to Data
            </a>
            <form method="post" action="update.php" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                <div class="form-group">
                    <b><label for="registrationCertification">Registration Certification:</label></b>
                    <input type="text" id="registrationCertification" name="registrationCertification" value="<?php echo $row['Registration_Certification']; ?>" required>
                </div>
                <div class="form-group">
                    <b><label for="name">Applicant Name:</label></b>
                    <input type="text" id="name" name="name" value="<?php echo $row['applicantname']; ?>" required>
                </div>
                <div class="form-group">
                    <b><label for="address">Applicant Address:</label></b>
                    <input type="text" id="address" name="address" value="<?php echo $row['applicantaddress']; ?>" required>
                </div>
                <div class="form-group">
                    <b><label for="email">Email:</label></b>
                    <input type="email" id="email" name="email" value="<?php echo $row['email']; ?>" required>
                </div>
                <div class="form-group">
                    <b><label for="landline">Landline:</label></b>
                    <input type="text" id="landline" name="landline" value="<?php echo $row['landline']; ?>" required>
                </div>
                <div class="form-group">
                    <b><label for="mobile">Mobile:</label></b>
                    <input type="text" id="mobile" name="mobile" value="<?php echo $row['mobile']; ?>" required>
                </div>
                <div class="form-group">
                    <b><label for="firstName">President First Name:</label></b>
                    <input type="text" id="firstName" name="firstName" value="<?php echo $row['presidentfirstname']; ?>" required>
                </div>
                <div class="form-group">
                    <b><label for="middleName">President Middle Name:</label></b>
                    <input type="text" id="middleName" name="middleName" value="<?php echo $row['presidentmiddlename']; ?>" required>
                </div>
                <div class="form-group">
                    <b><label for="lastName">President Last Name:</label></b>
                    <input type="text" id="lastName" name="lastName" value="<?php echo $row['presidentlastname']; ?>" required>
                </div>
                <div class="form-group">
                    <b><label for="presidentEmail">President Email:</label></b>
                    <input type="email" id="presidentEmail" name="presidentEmail" value="<?php echo $row['presidentemail']; ?>" required>
                </div>
                <div class="form-group">
                    <b><label for="presidentLandline">President Landline:</label></b>
                    <input type="text" id="presidentLandline" name="presidentLandline" value="<?php echo $row['presidentlandline']; ?>" required>
                </div>
                <div class="form-group">
                    <b><label for="presidentMobile">President Mobile:</label></b>
                    <input type="text" id="presidentMobile" name="presidentMobile" value="<?php echo $row['presidentmobile']; ?>" required>
                </div>
                <div class="form-group">
                    <b><label for="gender">Gender:</label></b>
                    <input type="text" id="gender" name="gender" value="<?php echo $row['gender']; ?>" required>
                </div>
                <div class="form-group">
                    <b><label for="dateOrganized">Date Organized:</label></b>
                    <input type="date" id="dateOrganized" name="dateOrganized" value="<?php echo $row['dateorganized']; ?>" required>
                </div>
                <div class="form-group">
                    <b><label for="dateCBLRatification">Date of CBL Ratification:</label></b>
                    <input type="date" id="dateCBLRatification" name="dateCBLRatification" value="<?php echo $row['dateofcblratification']; ?>">
                </div>
                <div class="form-group">
                    <b><label for="placeOperation">Places of Operation:</label></b>
                    <input type="text" id="placeOperation" name="placeOperation" value="<?php echo $row['placesofoperation']; ?>" required>
                </div>
                <div class="form-group">
                    <b><label for="occupation">Occupation:</label></b>
                    <input type="text" id="occupation" name="occupation" value="<?php echo $row['occupation']; ?>">
                </div>
                <div class="form-group">
                    <b><label for="otherOccupation">Other Occupation:</label></b>
                    <input type="text" id="otherOccupation" name="otherOccupation" value="<?php echo $row['otheroccupation']; ?>">
                </div>
                <div class="form-group">
                    <b><label for="dateAccomplished">Date Accomplished:</label></b>
                    <input type="date" id="dateAccomplished" name="dateAccomplished" value="<?php echo $row['dateaccomplished']; ?>">
                </div>
                <div class="form-group">
                    <b><label for="no_of_male">Number of Male:</label></b>
                    <input type="text" id="no_of_male" name="no_of_male" value="<?php echo $row['no_of_male']; ?>">
                </div>
                <div class="form-group">
                    <b><label for="no_of_female">Number of Female:</label></b>
                    <input type="text" id="no_of_female" name="no_of_female" value="<?php echo $row['no_of_female']; ?>">
                </div>
                <div class="form-group">
                    <b><label for="total">Total:</label></b>
                    <input type="text" id="total" name="total" value="<?php echo $row['total']; ?>">
                </div>
                <div class="form-group">
                    <b><label for="attachment">Attachment:</label></b>
                    <input type="file" id="attachment" name="attachment">
                </div>
                <div class="form-group">
                    <input type="submit" value="Update">
                </div>
            </form>
        </div>
    </div>
</body>
</html>
