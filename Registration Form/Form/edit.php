<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit;
}

include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $address = $_POST["address"];
    $email = $_POST["email"];
    $landline = $_POST["landline"];
    $mobile = '0' . ltrim($_POST["mobile"], '0');
    $firstName = $_POST["firstName"];
    $middleName = $_POST["middleName"];
    $lastName = $_POST["lastName"];
    $presidentEmail = $_POST["presidentEmail"];
    $presidentLandline = $_POST["presidentLandline"];
    $presidentMobile = '0' . ltrim($_POST["presidentMobile"], '0');
    $gender = $_POST["gender"];
    $dateOrganized = $_POST["dateOrganized"];
    $dateCBLRatification = isset($_POST["dateCBLRatification"]) ? $_POST["dateCBLRatification"] : '';
    $placeOperation = $_POST["placeOperation"];
    $occupation = isset($_POST["occupation"]) ? $_POST["occupation"] : '';
    $otherOccupation = $_POST["otherOccupation"];
    $dateAccomplished = $_POST["dateAccomplished"];
    
    $sql = "UPDATE register SET applicantname='$name', applicantaddress='$address', email='$email', landline='$landline', mobile='$mobile', presidentfirstname='$firstName', presidentmiddlename='$middleName', presidentlastname='$lastName', presidentaddress='$address', presidentemail='$presidentEmail', presidentlandline='$presidentLandline', presidentmobile='$presidentMobile', dateorganized='$dateOrganized', dateofcblratification='$dateCBLRatification', placesofoperation='$placeOperation', gender='$gender', dateaccomplished='$dateAccomplished', otheroccupation='$otherOccupation', occupation='$occupation'";

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Record</title>
    <style>
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif, sans-serif;
            display: flex;
            height: 100vh;
            margin: 0;
            overflow: hidden; /* Prevent scrolling on the body */
        }
        .sidebar {
            font-family: Arial, sans-serif;
            font-size: 22px;
            width: 180px;
            background-color: #6A80B9;
            color: white;
            display: flex;
            flex-direction: column;
            padding: 20px;
            height: 100vh;
            overflow: hidden;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 11px 0;
            margin: 13px 0;
            border-bottom: 0px solid #444;
            display: flex;
            align-items: center;
        }
        .sidebar a img {
            margin-right: 10px;
            width: 25px; 
            height: 25px; 
        }
        .sidebar a:hover {
            background-color: rgb(140, 138, 138);
        }
        .main-content {
            flex: 1;
            padding: 20px;
            background-color: #f4f4f4;
            overflow-y: auto; /* Allow scrolling within the main content area */
            height: 100vh;
        }
        .header {
            background-color: lightblue;
            color: black;
            padding: 15px;
            text-align: center;
        }
        .section {
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .section h2 {
            margin-top: 1;
        }
        .iframe-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .iframe-container iframe {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="main-content">
        <div class="header">
            <h1>Edit Record</h1>
        </div>
        <div class="section">
            <form method="post" action="update.php" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                <label for="name">Applicant Name:</label>
                <input type="text" id="name" name="name" value="<?php echo $row['applicantname']; ?>" required><br>
                <label for="address">Applicant Address:</label>
                <input type="text" id="address" name="address" value="<?php echo $row['applicantaddress']; ?>" required><br>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $row['email']; ?>" required><br>
                <label for="landline">Landline:</label>
                <input type="text" id="landline" name="landline" value="<?php echo $row['landline']; ?>" required><br>
                <label for="mobile">Mobile:</label>
                <input type="text" id="mobile" name="mobile" value="<?php echo ltrim($row['mobile'], '0'); ?>" required><br>
                <label for="firstName">President First Name:</label>
                <input type="text" id="firstName" name="firstName" value="<?php echo $row['presidentfirstname']; ?>" required><br>
                <label for="middleName">President Middle Name:</label>
                <input type="text" id="middleName" name="middleName" value="<?php echo $row['presidentmiddlename']; ?>" required><br>
                <label for="lastName">President Last Name:</label>
                <input type="text" id="lastName" name="lastName" value="<?php echo $row['presidentlastname']; ?>" required><br>
                <label for="presidentEmail">President Email:</label>
                <input type="email" id="presidentEmail" name="presidentEmail" value="<?php echo $row['presidentemail']; ?>" required><br>
                <label for="presidentLandline">President Landline:</label>
                <input type="text" id="presidentLandline" name="presidentLandline" value="<?php echo $row['presidentlandline']; ?>" required><br>
                <label for="presidentMobile">President Mobile:</label>
                <input type="text" id="presidentMobile" name="presidentMobile" value="<?php echo ltrim($row['presidentmobile'], '0'); ?>" required><br>
                <label for="gender">Gender:</label>
                <input type="text" id="gender" name="gender" value="<?php echo $row['gender']; ?>" required><br>
                <label for="dateOrganized">Date Organized:</label>
                <input type="date" id="dateOrganized" name="dateOrganized" value="<?php echo $row['dateorganized']; ?>" required><br>
                <label for="dateCBLRatification">Date of CBL Ratification:</label>
                <input type="date" id="dateCBLRatification" name="dateCBLRatification" value="<?php echo $row['dateofcblratification']; ?>"><br>
                <label for="placeOperation">Places of Operation:</label>
                <input type="text" id="placeOperation" name="placeOperation" value="<?php echo $row['placesofoperation']; ?>" required><br>
                <label for="occupation">Occupation:</label>
                <input type="text" id="occupation" name="occupation" value="<?php echo $row['occupation']; ?>"><br>
                <label for="otherOccupation">Other Occupation:</label>
                <input type="text" id="otherOccupation" name="otherOccupation" value="<?php echo $row['otheroccupation']; ?>"><br>
                <label for="dateAccomplished">Date Accomplished:</label>
                <input type="date" id="dateAccomplished" name="dateAccomplished" value="<?php echo $row['dateaccomplished']; ?>"><br>
                <label for="attachment">Attachment:</label>
                <input type="file" id="attachment" name="attachment"><br>
                <input type="submit" value="Update">
            </form>
        </div>
    </div>
</body>
</html>
