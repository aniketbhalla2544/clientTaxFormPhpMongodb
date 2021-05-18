<?php

function get($name)
{
    return $_POST[$name];
}

// security purpose
$submitBtn = get("taxFormSubmitBtn");
isset($submitBtn) ? "" : exit();

$fields = ['clientFirstName', 'clientLastName', 'clientContactNum', 'clientEmail', 'clientCollegeName', 'clientSinNum', 'clientBirthdate', 'clientNationality', 'isClientAloneYesNo', 'mailingService', 'canadaLandedDate', 'alwaysInQuebec', 'everChangedProvince', 'clientMartialStatus', 'clientResidencyStatus', 'isHomeMailingAddrSame', 'isResidenceDisposed', 'anyForeignIncome', 'ownProperty100k', 'anyMadeDonations', 'anyRrsp', 'anyMedicalExpenses', 'tfourform', 'RL1Form', 'T2202AForm', 'RL8Form', 'RL31Form', 'voidCheque', 'anyPendingdocs', 'clientFinacialInstName', 'clientInstNum', 'clientBranchNum', 'clientAccNum'];

$values = [];
$errors = [];
$_SESSION['values'] = $values;
$_SESSION['errors'] = $errors;
$_SESSION['name'] = "aniket";

// echo var_dump($_SESSION['name']);


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    foreach ($fields as $field) {
        (empty(get($field))) ?  $errors[] = $field : $values[$field] = get($field);
    }

    $errors = [];


    if (empty($errors)) {
        // print_r($values);
    }
}
