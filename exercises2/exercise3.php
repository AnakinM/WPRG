<?php
require 'validators.php';
$errors = false;

function validate($field, $message, $validators, $min = 0, $max = 0)
{
    global $errors;
    foreach ($validators as $validator) {
        if ($validator === "validateLength") {
            if ($validator($field, $min, $max)) {
                echo "<span style='color: red;'>$message</span></br>";
                $errors = true;
            }
        } else {
            if ($validator($field)) {
                echo "<span style='color: red;'>$message</span></br>";
                $errors = true;
            }
        }
    }
    return $field;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        fieldset {
            width: 300px;
        }

        input {
            margin: 5px;
        }
    </style>
</head>

<body>
    <?php
    $numberOfGuests = "";
    $mainGuestFirstName = "";
    $mainGuestLastName = "";
    $mainGuestAddress = "";
    $mainGuestEmail = "";
    $creditCardNumber = "";
    $creditCardExpirationDate = "";
    $creditCardSecurityCode = "";
    $checkInDate = "";
    $checkOutDate = "";
    $checkInTime = "";
    $extraBedIncluded = false;
    $acIncluded = false;
    $isSmoker = false;

    if (isset($_POST['submit'])) {
        $numberOfGuests = validate($_POST['numberOfGuests'], "Number of guests is required", ["validateNumber"]);
        $mainGuestFirstName = validate($_POST['mainGuestFirstName'], "Main guest first name is required", ["validateString", "validateLength"], 2, 50);
        $mainGuestLastName = validate($_POST['mainGuestLastName'], "Main guest last name is required", ["validateString", "validateLength"], 2, 50);
        $mainGuestAddress = validate($_POST['mainGuestAddress'], "Main guest address is required", ["validateLength"], 2, 100);
        $mainGuestEmail = validate($_POST['mainGuestEmail'], "Main guest email is required", ["validateEmail"]);
        $creditCardNumber = validate($_POST['creditCardNumber'], "Credit card number is required", ["validateCreditCard"]);
        $creditCardExpirationDate = validate($_POST['creditCardExpirationDate'], "Credit card expiration date is required", ["validateExpirationDate"]);
        $creditCardSecurityCode = validate($_POST['creditCardSecurityCode'], "Credit card security code is required", ["validateSecurityCode"]);
        $checkInDate = validate($_POST['checkInDate'], "Check in date is required", ["validateDate"]);
        $checkOutDate = validate($_POST['checkOutDate'], "Check out date is required", ["validateDate"]);
        $checkInTime = validate($_POST['checkInTime'], "Check in time is required", ["validateTime"]);
        $extraBedIncluded = isset($_POST['extraBedIncluded']) ? true : false;
        $acIncluded = isset($_POST['acIncluded']) ? true : false;
        $isSmoker = isset($_POST['isSmoker']) ? true : false;
    }
    ?>
    <form action="exercise3.php" method="post">
        <fieldset>
            <legend>Reservation</legend>
            <label for="numberOfGuests">Number of guests</label>
            <select name="numberOfGuests" id="numberOfGuests" onchange="updateGuestFields()">
                <option value="1" <?php echo $numberOfGuests == 1 ? "selected" : "" ?>>1</option>
                <option value="2" <?php echo $numberOfGuests == 2 ? "selected" : "" ?>>2</option>
                <option value="3" <?php echo $numberOfGuests == 3 ? "selected" : "" ?>>3</option>
                <option value="4" <?php echo $numberOfGuests == 4 ? "selected" : "" ?>>4</option>
            </select></br>
            <!-- Based on numberOfGuests selected display additional fields for more guests -->
            <div id="additionalGuests"></div>
            <input type="text" name="mainGuestFirstName" placeholder="Main Guest First Name"
                value="<?php echo $mainGuestFirstName ?>"></br>
            <input type="text" name="mainGuestLastName" placeholder="Main Guest Last Name"
                value="<?php echo $mainGuestLastName ?>"></br>
            <input type="text" name="mainGuestAddress" placeholder="Main Guest Address"
                value="<?php echo $mainGuestAddress ?>"></br>
            <input type="text" name="mainGuestEmail" placeholder="Main Guest Email"
                value="<?php echo $mainGuestEmail ?>"></br>
            <input type="text" name="creditCardNumber" placeholder="Credit Card Number"
                value="<?php echo $creditCardNumber ?>"></br>
            <input type="text" name="creditCardExpirationDate" placeholder="Credit Card Expiration Date"
                value="<?php echo $creditCardExpirationDate ?>"></br>
            <input type="text" name="creditCardSecurityCode" placeholder="Credit Card Security Code"
                value="<?php echo $creditCardSecurityCode ?>"></br>
            <label for="checkInDate">Check in date</label>
            <input type="date" name="checkInDate" value="<?php echo $checkInDate ?>"></br>
            <label for="checkOutDate">Check out date</label>
            <input type="date" name="checkOutDate" value="<?php echo $checkOutDate ?>"></br>
            <label for="checkInTime">Check in time</label>
            <input type="time" name="checkInTime" value="<?php echo $checkInTime ?>"></br>
            <input type="checkbox" name="extraBedIncluded" <?php echo $extraBedIncluded ? "checked" : "" ?>>
            Do you need an extra bed?</br>
            <input type="checkbox" name="acIncluded" <?php echo $acIncluded ? "checked" : "" ?>>
            Do you need an AC?</br>
            <input type="checkbox" name="isSmoker" <?php echo $isSmoker ? "checked" : "" ?>>
            Are you smoking?</br>
        </fieldset>
        <button type="submit" name="submit">Submit</button>
    </form>



    <?php
    if (isset($_POST['submit']) && !$errors) {
        echo "<h1>Reservation summary</h1>";
        echo "<p>Number of guests: <strong>$numberOfGuests</strong></p>";
        echo "<p>Main guest first name: <strong>$mainGuestFirstName</strong></p>";
        echo "<p>Main guest last name: <strong>$mainGuestLastName</strong></p>";
        echo "<p>Main guest address: <strong>$mainGuestAddress</strong></p>";
        echo "<p>Main guest email: <strong>$mainGuestEmail</strong></p>";
        if ($numberOfGuests > 1) {
            for ($i = 2; $i <= $numberOfGuests; $i++) {
                $guestFirstName = validate($_POST["guestFirstName$i"], "Guest $i first name is required", ["validateString", "validateLength"], 2, 50);
                $guestLastName = validate($_POST["guestLastName$i"], "Guest $i last name is required", ["validateString", "validateLength"], 2, 50);
                echo "<p>Guest $i first name: <strong>$guestFirstName</strong></p>";
                echo "<p>Guest $i last name: <strong>$guestLastName</strong></p>";
            }
        }
        echo "<p>Credit card number: <strong>$creditCardNumber</strong></p>";
        echo "<p>Credit card expiration date: <strong>$creditCardExpirationDate</strong></p>";
        echo "<p>Credit card security code: <strong>$creditCardSecurityCode</strong></p>";
        echo "<p>Check in date: <strong>$checkInDate</strong></p>";
        echo "<p>Check out date: <strong>$checkOutDate</strong></p>";
        echo "<p>Check in time: <strong>$checkInTime</strong></p>";
        echo "<p>Extra bed included: <strong>" . ($extraBedIncluded ? "yes" : "no") . "</strong></p>";
        echo "<p>AC included: <strong>" . ($acIncluded ? "yes" : "no") . "</strong></p>";
        echo "<p>Is smoker: <strong>" . ($isSmoker ? "yes" : "no") . "</strong></p>";
    }
    ?>
    </br>
    <a href="index.php">Menu</a>
    <script>
        function updateGuestFields() {
            const numberOfGuests = document.getElementById('numberOfGuests').value;
            const additionalGuestsDiv = document.getElementById('additionalGuests');
            additionalGuestsDiv.innerHTML = '';
            if (numberOfGuests > 1) {
                for (let i = 2; i <= numberOfGuests; i++) {
                    additionalGuestsDiv.innerHTML += `
                <fieldset>
                    <legend>Guest ${i}</legend>
                    <input type="text" name="guestFirstName${i}" placeholder="Guest ${i} First Name" /><br/>
                    <input type="text" name="guestLastName${i}" placeholder="Guest ${i} Last Name" /><br/>
                </fieldset>`;
                }
            }
        }

        updateGuestFields();
    </script>


    ?>


    <a href="index.php">Menu</a>
</body>

</html>