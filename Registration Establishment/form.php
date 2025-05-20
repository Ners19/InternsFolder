<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registry of Establishment</title>
    <link rel="icon" href="dole.png" type="image/png">
    <script src="calculateTotals.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif;
            font-size: 14px; /* Adjusted font size */
            line-height: 1.5; /* Adjusted line height for better readability */
            margin: 20px;
        }
        .container {
            width: 80%;
            margin: auto;
            border: 1px solid #ccc;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            max-width: 100px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        input[type="text"], input[type="email"], input[type="number"], select {
            width: calc(100% - 16px);
            padding: 8px;
            margin-bottom: 5px;
            box-sizing: border-box;
            font-size: 14px; /* Adjusted font size */
        }
        .checkbox-group label {
            display: block;
            margin-bottom: 5px;
            font-size: 15px; /* Adjusted font size */
        }
        .submit-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            font-size: 15px; /* Adjusted font size */
            border-radius: 5px;
            transition: background-color 0.3s ease;
            display: block;
            margin: 30px auto; /* Center the button */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header" style="text-align: center;">
            <img src="dole.png" alt="DOLE Logo" style="max-width: 100px;">
            <h2>Republic of the Philippines<br>
            DEPARTMENT OF LABOR AND EMPLOYMENT<br>
            Regional Office No. <span contenteditable="true" style="display: inline-block; width: 50px; text-align: center; border-bottom: 1px dotted black;"></span>
            </h2>
            <h3>Registry of Establishment</h3>
        </div>

        <form action="submit.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
            <div style="text-align: right; margin-bottom: 10px;">
                <strong>EIN:</strong>
                <input type="text" name="ein" style="width: 150px;" required>
            </div>
            <table>
                <tr>
                    <td colspan="2">
                        <strong>1. Name of Establishment:</strong><br>
                        <input type="text" name="establishment_name" required><br>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <strong>2. Address:</strong><br>
                        <input type="text" name="street" placeholder="Street" required>
                        <input type="text" name="city" placeholder="City/Municipality" required>
                        <input type="text" name="province" placeholder="Province" required>
                    </td>
                </tr>
                <tr>
                    <td><strong>3. TIN:</strong><br><input type="text" name="tin"></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <table>
                            <tr>
                                <td><strong>4. Telephone No.:</strong><br><input type="text" name="telephone"></td>
                                <td><strong>Fax No.:</strong><br><input type="text" name="fax"></td>
                                <td><strong>Email Address:</strong><br><input type="email" name="email"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <strong>5. Name of Manager / Owner:</strong><br>
                        <input type="text" name="manager_owner">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <strong>6. Nature of Business & Product Manufactured, Service rendered or Merchandise sold:</strong><br>
                        <div style="display: flex; gap: 10px;">
                            <select name="business_nature" style="flex: 1; padding: 8px;">
                                <option value="" disabled selected> Select an option</option>
                                <option value="manufacturing_textile"> Manufacturing - Textile</option>
                                <option value="construction_building"> Construction - Building</option>
                                <option value="agriculture_livestock"> Agriculture - Production of Livestock</option>
                                <option value="forestry_logging"> Forestry - Logging</option>
                                <option value="services_electricity"> Services - Generation and Distribution of Electricity</option>
                                <option value="commerce_lumber">Commerce - Lumber and Construction Materials</option>
                                <option value="wholesale_retail">Wholesale or Retail</option>
                                <option value="other">Other (Specify Below)</option>
                            </select>
                            <input type="text" name="business_nature_other" placeholder="Specify if Other" style="flex: 1; padding: 8px;">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <strong> 7. Number of Employees:</strong><br>
                        <table>
                            <tr>
                                <th> </th>
                                <th>Filipinos</th>
                                <th>Resident Alien</th>
                                <th>Non-Resident Alien</th>
                                <th>Below 15 yrs.</th>
                                <th>15-17 yrs.</th>
                                <th>18-30 yrs.</th>
                                <th>Above 30 yrs.</th>
                                <th>Total</th>
                            </tr>
                            <tr>
                                <td>Male</td>
                                <td><input type="number" name="filipino_male"></td>
                                <td><input type="number" name="resident_alien_male"></td>
                                <td><input type="number" name="non_resident_alien_male"></td>
                                <td><input type="number" name="below15_male"></td>
                                <td><input type="number" name="15to17_male"></td>
                                <td><input type="number" name="18to30_male"></td>
                                <td><input type="number" name="above30_male"></td>
                                <td><input type="number" name="total_male" readonly></td>
                            </tr>
                            <tr>
                                <td>Female</td>
                                <td><input type="number" name="filipino_female"></td>
                                <td><input type="number" name="resident_alien_female"></td>
                                <td><input type="number" name="non_resident_alien_female"></td>
                                <td><input type="number" name="below15_female"></td>
                                <td><input type="number" name="15to17_female"></td>
                                <td><input type="number" name="18to30_female"></td>
                                <td><input type="number" name="above30_female"></td>
                                <td><input type="number" name="total_female" readonly></td>
                            </tr>
                            <tr>
                                <td>Grand Total</td>
                                <td><input type="number" name="grand_total_filipinos" readonly></td>
                                <td><input type="number" name="grand_total_resident_alien" readonly></td>
                                <td><input type="number" name="grand_total_non_resident_alien" readonly></td>
                                <td><input type="number" name="grand_total_below15" readonly></td>
                                <td><input type="number" name="grand_total_15to17" readonly></td>
                                <td><input type="number" name="grand_total_18to30" readonly></td>
                                <td><input type="number" name="grand_total_above30" readonly></td>
                                <td><input type="number" name="grand_total_all" readonly></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div style="display: flex; gap: 10px;">
                            <div style="flex: 1;">
                                <strong>8. Name & Address of Labor Union, if any:</strong><br>
                                <input type="text" name="labor_union" style="width: 100%; padding: 8px;">
                            </div>
                            <div style="flex: 1;">
                                <strong>8. BLR Registration No.:</strong><br>
                                <input type="text" name="blr_registration" style="width: 100%; padding: 8px;">
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <strong>9. Technical Information (Please Check / Enumerate):</strong><br>
                        <strong>9a. Machinery, Equipment and Other Devices in use:</strong><br>
                        <div class="checkbox-group">
                            <label><input type="checkbox" name="circular_saw"> Circular Saw</label>
                            <label><input type="checkbox" name="methane_drill"> Methane Drill Press</label>
                            <label><input type="checkbox" name="boiler"> Boiler</label>
                            <label><input type="checkbox" name="pressure_vessel"> Pressure Vessel</label>
                            <label><input type="checkbox" name="engine_diesel"> Engine Diesel</label>
                            <label><input type="checkbox" name="gasoline"> Gasoline</label>
                            <label><input type="checkbox" name="internal_combustion_engine"> Internal Combustion Engine:</label>
                            <label><input type="checkbox" name="others_specify"> Others, Specify: <input type="text" name="others_specify_text"></label>
                        </div>
                
                        <strong>9b. Materials Handling Equipment:</strong><br>
                        <div class="checkbox-group">
                            <label><input type="checkbox" name="power_trucks"> Power Trucks</label>
                            <label><input type="checkbox" name="delivery_trucks"> Delivery Trucks</label>
                            <label><input type="checkbox" name="conveyors"> Conveyors</label>
                            <label><input type="checkbox" name="forklift"> Forklift</label>
                            <label><input type="checkbox" name="cranes"> Cranes</label>
                            <label><input type="checkbox" name="others_specify_material"> Others, Specify: <input type="text" name="others_specify_material_text"></label>
                        </div>
                        <strong>9c. Chemical or Substances Used or Handled:</strong><br>
                        <input type="text" name="chemicals_used" style="width: calc(100% - 16px); padding: 8px;">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <strong>10. If Branch unit, name of parent establishment:</strong><br>
                        <input type="text" name="parent_establishment" placeholder="Name of Parent Establishment" style="width: calc(100% - 16px); padding: 8px;"><br>
                        <input type="text" name="branch_street" placeholder="Street" style="width: calc(100% - 16px); padding: 8px;">
                        <input type="text" name="branch_city" placeholder="City/Municipality" style="width: calc(100% - 16px); padding: 8px;">
                        <input type="text" name="branch_province" placeholder="Province" style="width: calc(100% - 16px); padding: 8px;">
                    </td>
                </tr>
                <tr>
                    <td><strong>11. Current Capitalization:</strong><br><input type="text" name="capitalization"></td>
                    <td><strong>Total Assets:</strong><br><input type="text" name="total_assets"></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <strong>12. Photocopy of DTI Certificate of Registration / Business Permit (pls. attach):</strong><br>
                        <input type="file" name="dti_permit" accept=".pdf,.jpg,.png">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <strong>FOR RE-REGISTRATION ACCOMPLISH ALSO:</strong><br>
                        <strong>13. Past Application Number:</strong><br><input type="text" name="past_application_number">
                        <strong>Date of Application:</strong><br><input type="date" name="past_application_date">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <strong>14. If Changing Name of Establishment, State Former Name:</strong><br>
                        <input type="text" name="former_name">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <strong>15. If Changing Location, Give Past Address:</strong><br>
                        <input type="text" name="past_address">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="checkbox" name="certification"> I hereby certify that the above information is true and correct.
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <strong>Owner/President:</strong><br><input type="text" name="owner_president">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <table style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td style="width: 33%; box-sizing: border-box; padding: 8px;">
                                    <strong>Date Filed:</strong><br>
                                    <input type="date" name="date_filed" style="width: 100%; padding: 6px; box-sizing: border-box;">
                                </td>
                                <td style="width: 33%; box-sizing: border-box; padding: 8px;">
                                    <strong>Date Approved:</strong><br>
                                    <input type="date" name="date_approved" style="width: 100%; padding: 6px; box-sizing: border-box;">
                                </td>
                                <td style="width: 34%; box-sizing: border-box; padding: 8px;">
                                    <strong>Approved by:</strong><br>
                                    <input type="text" name="approved_by" style="width: 100%; padding: 6px; box-sizing: border-box;">
                                    <small>(Regional Director or Assistant Regional Director or Head of Field Office)</small>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <button type="submit" class="submit-button">Submit</button>
        </form>
    </div>

    <script>
    function validateForm() {
        const requiredFields = ['ein', 'establishment_name', 'street', 'city', 'province'];
        for (const field of requiredFields) {
            const input = document.querySelector(`[name="${field}"]`);
            if (!input || input.value.trim() === '') {
                alert(`Please fill out the ${field.replace('_', ' ')} field.`);
                input.focus();
                return false;
            }
        }
        return true;
    }
    </script>
</body>
</html>