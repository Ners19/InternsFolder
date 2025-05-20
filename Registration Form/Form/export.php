<?php
include 'database.php';

$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : '';

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=registered_data.xls");

$sql = "SELECT id, Registration_Certification, applicantname, applicantaddress, email, landline, mobile, presidentfirstname, presidentmiddlename, presidentlastname, presidentaddress, presidentemail, presidentlandline, presidentmobile, dateorganized, dateofcblratification, placesofoperation, no_of_male, no_of_female, total, gender, dateaccomplished, otheroccupation, occupation 
        FROM register 
        WHERE 1";

if ($startDate && $endDate) {
    $sql .= " AND dateaccomplished BETWEEN '$startDate' AND '$endDate'";
}

$result = mysqli_query($conn, $sql);

echo "ID\tRegistration Certification\tApplicant Name\tApplicant Address\tEmail\tLandline\tMobile\tPresident First Name\tPresident Middle Name\tPresident Last Name\tPresident Address\tPresident Email\tPresident Landline\tPresident Mobile\tDate Organized\tDate of CBL Ratification\tPlaces of Operation\tNo. of Male\tNo. of Female\tTotal\tGender\tDate Accomplished\tOther Occupation\tOccupation\n";

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo implode("\t", $row) . "\n";
    }
}
?>
