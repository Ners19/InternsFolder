<?php
// Removed session_start() and login check to make the form publicly accessible.

if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    $http_url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header("Location: $http_url");
    exit;
}

include 'database.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workers Association Form</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="dole.png" type="image/png"> 
    <script src="script.js" defer></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }
        .header {
            background: #add8e6; 
            color: black;
            padding: 15px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        .header img {
            margin-right: 15px;
        }
        .logout-icon {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 30px;
            height: 30px;
            cursor: pointer;
        }
        .container {
            display: flex;
            flex: 1;
        }
        .main-content {
            flex: 1;
            padding: 30px;
            background: #f4f4f4;
            border: solid rgb(255, 255, 255) 1px;
        }
        .footer {
            background: #333;
            color: white;
            text-align: center;
            padding: 10px;
        }
        .myInput-right, .myInput-left, .inputDate {
            border-radius: 5px;
            border: solid 1px gray;
            height: 35px;
        }
        .myInput-right {
            border: solid 1px rgb(255, 255, 255);
            width: 100%;
        }
        .myInput-left {
            width: 100%;
        }
        .inputDate {
            border: solid 1px rgb(255, 255, 255);
            float: right;
            width: 100%;
            clear: both;
        }
        .left_p {
            border: solid 1px rgb(255, 255, 255);
            float: left;
            width: 70%;
        }
        .right_p {
            border: solid 1px rgb(205, 205, 205);
            float: right;
            width: 28%;
        }
        .textArea {
            width: 100%;
            height: 80px;
        }
        .submit-button {
            background-color: #4CAF50;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border: none;
            border-radius: 4px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        table, th, td {
            border: 1px solid black;
        }
        head {
            font-weight: bold;
        }
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                text-align: center;
            }
            .container {
                flex-direction: column;
            }
            .main-content {
                padding: 10px;
            }
            table {
                font-size: 12px;
            }
            .myInput-right, .myInput-left, .inputDate {
                width: 100%;
            }
        }
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            border: 2px solid #4CAF50;
            padding: 20px;
            z-index: 1000;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        .popup button {
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .popup button:hover {
            background-color: #45a049;
        }
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
    </style>
    <script>
        function validateForm(event) {
            const requiredFields = [
                'name', 'address', 'email', 'landline', 'mobile', 'firstName', 'middleName', 'lastName', 'gender', 
                'dateOrganized', 'dateCBLRatification', 'placeOperation', 'no_of_male', 'no_of_female', 'occupation'
            ];
            let isValid = true;

            requiredFields.forEach(field => {
                const input = document.forms["registrationForm"][field];
                if (input && input.value.trim() === "") {
                    isValid = false;
                    input.style.border = "2px solid red";
                } else if (input) {
                    input.style.border = "";
                }
            });

            if (!isValid) {
                alert("Please fill out all required fields.");
                event.preventDefault(); // Prevent form submission
            }
        }
    </script>
</head>
<body>
    <div class="header">
       
        <img src="dole.png" alt="Logo" style="width: 100px; height: auto;">
        <b><div>
            Republic of the Philippines<br>
            DEPARTMENT OF LABOR AND EMPLOYMENT<br>
            Regional Office No.______
        </div></b>
    </div>
    <div class="container">
        <div class="main-content">
            <form name="registrationForm" action="SubmitForm.php" method="post" enctype="multipart/form-data" onsubmit="validateForm(event)">
                <table boarder="1" width="100%" cellspacing="20px">
                    <tr>
                        <td colspan="2">
                            <center>
                            <h2>APPLICATION FOR REGISTRATION OF WORKER'S ASSOCIATION (WA's)</h2>
                            </center>
                            <hr/>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div class="left_p">
                                <h3>Part I. INFORMATION ABOUT THE REPORTING ORGANIZATION</h3><br/>
                                To be accomplished by the applicant. Supply all required information. Misrepresentation,
                                false statement or fraud in this application or any supporting document is a 
                                ground for denial or cancellation of registration.</br>
                            </div>
                            <div class="right_p">
                                <b><div class="header">Date Accomplished (mm/dd/yyyy)</div><br/></b>
                                <input class="inputDate" type="date" name="dateAccomplished">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3"><hr/></td>
                    </tr>
                    <tr>
                        <td width="70%" valign="top">
                        <b><div class="header">Name of Applicant Association</div><br/></b>
                            <label>Name</label><br/>
                            <input class="myInput-right" type="text" name="name" required/><br/>
                            <label>Address</label><br/>
                            <input class="myInput-right" type="text" name="address" required/>    
                        </td>
                        <td valign="top">
                            <b><div class="header">Contact Nos.</div><br/></b>
                            <label>E-Mail:</label><br/>
                            <input class="myInput-left" type="text" name="email" required/><br/>
                            <label>Landline No:</label><br/>
                            <input class="myInput-left" type="text" name="landline" required/><br/>
                            <label>Mobile No:</label><br/>
                            <input class="myInput-left" type="text" name="mobile" required/><br/>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><hr/></td>
                    </tr>
                    <tr>
                        <td width="75%" valign="top">
                            <b><div class="header">Name of President<br/></div><br/></b>
                            <label>(First Name)</label><br/>
                            <input class="myInput-right" type="text" name="firstName" required/><br/>
                            <label>(Middle Name)</label><br/>
                            <input class="myInput-right" type="text" name="middleName" required/><br/>
                            <label>(Last Name)</label><br/>
                            <input class="myInput-right" type="text" name="lastName" required/><br/>
                            <label>Address</label><br/>
                            <input class="myInput-right" type="text" name="address" required/><br/>
                            <label for="gender">Gender:</label><br/>
                            <select id="gender" name="gender" style="width: 30%; height: 45px;" required>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </td>
                        <td valign="top">
                            <b><div class="header">President's Contact Nos.</div><br/></b>
                            <label>E-Mail:</label><br/>
                            <input class="myInput-left" type="text" name="presidentEmail"/><br/>
                            <label>Landline No:</label><br/>
                            <input class="myInput-left" type="text" name="presidentLandline"/><br/>
                            <label>Mobile No:</label><br/>
                            <input class="myInput-left" type="text" name="presidentMobile"/><br/>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><hr/></td>
                    </tr>
                    <tr>
                        <td width="70%" valign="top">
                        <b><div class="header">Date Organized<br/></div><br/></b>
                            <input type="date" id="dateOrganized" name="dateOrganized" style="width: 35%; height: 37px;" required>
                        </td>
                        <td valign="top">
                        <b><div class="header">Date of CBL Ratification</div><br/></b>
                            <input type="date" id="dateCBLRatification" name="dateCBLRatification" style="width: 40%; height: 37px;" required>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><hr/></td>
                    </tr>
                    <tr>
                        <td width="70%" valign="top">
                           <b><div class="header">Place of Operations</div><br/></b>
                            <textarea class="textArea" name="placeOperation" required>Input Here!!!</textarea>
                            <br/>
                        </td>
                        <td valign="top">
                            <b><div class="header">No. of Association Members </div></b><br/>
                            <label>Male</label><br/>
                            <input class="myInput-left" type="number" id="maleCount" name="no_of_male" min="0" oninput="calculateTotal()" required/><br/>
                            <label>Female</label><br/>
                            <input class="myInput-left" type="number" id="femaleCount" name="no_of_female" min="0" oninput="calculateTotal()" required/><br/>
                            <label>Total</label><br/>
                            <input class="myInput-left" type="number" id="totalCount" name="total" readonly/><br/>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><hr/></td>
                    </tr>
                    <tr>
                        <td width="70%" valign="top">
                        <b><div class="header">Occupation of Members:</div><br/></b>
                            <div class="occupation-checkboxes" style="border: 1px solid #ccc; padding: 10px; border-radius: 5px;">
                                <label><input type="radio" name="occupation" value="agricultural" required> Agricultural Workers (Farmers)</label><br/>
                                <label><input type="radio" name="occupation" value="fisherfolk"> Fisher folk</label><br/>
                                <label><input type="radio" name="occupation" value="artisans"> Artisans</label><br/>
                                <label><input type="radio" name="occupation" value="cottage"> Cottage</label><br/>
                                <label><input type="radio" name="occupation" value="small_transport"> Small Transport Workers (Drivers: Jeepney, FX, Tricycle, Pedicab)</label><br/>
                                <label><input type="radio" name="occupation" value="homebased"> Home-based / Homeworkers</label><br/>
                                <label><input type="radio" name="occupation" value="construction"> Small Construction Workers</label><br/>
                                <label><input type="radio" name="occupation" value="vendors"> Vendors (Market, Sidewalk, Ambulance)</label><br/>
                                <label><input type="radio" name="occupation" value="miners"> Small-scale Miners</label><br/>
                                <label><input type="radio" name="occupation" value="others"> Others/Own-Account (specify): <input type="text" name="otherOccupation"></label><br/>
                            </div>    
                        </td>
                        <td valign="top">
                            <b><div class="header">Attached Photo/Document</div><br/></b>
                            <input type="file" name="attachment" accept="image/*,application/pdf,.doc,.docx,.txt" onchange="previewFile()"><br/>
                            <div id="preview"></div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><hr/></td>
                    </tr>
                </table>
                <div class="footer">
                    <button type="submit" class="submit-button">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Popup and overlay -->
    <div class="overlay"></div>
    <div class="popup">
        <h3>Successfully submitted a form!</h3>
        <button onclick="closePopup()">Close</button>
    </div>

    <script>
        function previewFile() {
            const preview = document.getElementById('preview');
            const file = document.querySelector('input[name="attachment"]').files[0];
            const reader = new FileReader();

            reader.addEventListener("load", function () {
                if (file.type.startsWith('image/')) {
                    preview.innerHTML = '<img src="' + reader.result + '" style="max-width: 100%; height: auto;">';
                } else if (file.type === 'application/pdf') {
                    preview.innerHTML = '<embed src="' + reader.result + '" type="application/pdf" width="100%" height="500px">';
                } else if (file.type === 'application/msword' || file.type === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' || file.type === 'text/plain') {
                    preview.innerHTML = '<a href="' + reader.result + '" target="_blank">View Document</a>';
                } else {
                    preview.innerHTML = 'File preview not available';
                }
            }, false);

            if (file) {
                reader.readAsDataURL(file);
            }
        }

        function calculateTotal() {
            const maleCount = parseInt(document.getElementById('maleCount').value) || 0;
            const femaleCount = parseInt(document.getElementById('femaleCount').value) || 0;
            document.getElementById('totalCount').value = maleCount + femaleCount;
        }
    </script>
</body>
</html>
