<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php

    ?>
    <form action="exercise2.php" method="post">
        <fieldset>
            <select name="numberOfGuests">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
            </select></br>
            <input type="text" name="mainGuestFirstName" placeholder="Main Guest First Name"></br>
            <input type="text" name="mainGuestLastName" placeholder="Main Guest Last Name"></br>
            <input type="text" name="mainGuestAddress" placeholder="Main Guest Address"></br>
            <input type="text" name="mainGuestEmail" placeholder="Main Guest Email"></br>
            <input type="text" name="creditCardNumber" placeholder="Credit Card Number"></br>
            <input type="text" name="creditCardExpirationDate" placeholder="Credit Card Expiration Date"></br>
            <input type="text" name="creditCardSecurityCode" placeholder="Credit Card Security Code"></br>
            <input type="date" name="checkInDate"></br>
            <input type="date" name="checkOutDate"></br>
            <input type="time" name="checkInTime"></br>
            <input type="checkbox" name="extraBedIncluded" value="no"> Do you need an extra bed?</br>
            <input type="checkbox" name="acIncluded" value="no"> Do you need an AC?</br>
            <input type="checkbox" name="isSmoker" value="no"> Are you smoking?</br>
        </fieldset>
        <button type="submit" name="submit">Submit</button>
    </form>
    </br>
    <a href="index.php">Menu</a>
</body>

</html>