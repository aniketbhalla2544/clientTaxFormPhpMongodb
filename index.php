<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- my css -->
    <link rel="stylesheet" href="./css/app.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">

    <title>User Tax Form</title>
</head>

<?php

//  
require 'vendor/autoload.php'; // include Composer's autoloader
echo phpinfo();

// connect to mongodb
$client = new MongoDB\Client("mongodb://localhost:27017");

// selected collection from db
$clientRecordsCollection = $client->selectCollection('taxFormDb', 'clientRecords');

function preFomatOutput($value)
{
    echo "<pre>" . $value . "</pre>";
}

if (isset($_POST["taxFormSubmitBtn"]) && $_SERVER["REQUEST_METHOD"] == "POST") {


    $fields = ["clientFirstName", "clientLastName", "clientContactNum", "clientEmail", "clientCollegeName", "clientSinNum", "clientBirthdate", "clientNationality", "isClientAloneYesNo", "mailingService", "canadaLandedDate", "alwaysInQuebec", "everChangedProvince", "clientMartialStatus", "clientResidencyStatus", "isHomeMailingAddrSame", "isResidenceDisposed", "anyForeignIncome", "ownProperty100k", "anyMadeDonations", "anyRrsp", "anyMedicalExpenses", "tfourform", "RL1Form", "T2202AForm", "RL8Form", "RL31Form", "voidCheque", "anyPendingdocs", "clientFinacialInstName", "clientInstNum", "clientBranchNum", "clientAccNum"];

    $important_fields = ['clientFirstName', 'clientLastName', 'clientEmail', 'clientSinNum', 'clientBirthdate', 'clientNationality', 'clientMartialStatus', 'clientResidencyStatus'];

    // $important_fields = ['clientFirstName'];

    $values = $errors = [];

    $error_span = "<span class='tax-form__error-fields'>* Required</span>";

    $json_data = null;

    // if empty field then field in $errrors & if not then in $values (associative array)
    foreach ($fields as $field) {

        // only important fields in $errors
        if (empty($_POST[$field]) && in_array($field, $important_fields)) {

            $errors[] = $field;
        } else {

            // all the unset fields
            if ((isset($_POST[$field]) === false)) {
                // echo $field . "<br>";
                $_POST[$field] = "";
            }

            $values[$field] = htmlentities(trim(stripslashes($_POST[$field])));
        }
    }

    if (empty($errors)) {

        // echo "form gets submitted..!!<br><br>";

        $json_data = json_encode($values);

        $json_data = json_decode($json_data, FALSE);

        // echo gettype($json_data);

        foreach ($values as $field) {
            $values[$field] = "";
        }

        // echo "<pre>" . $json_data . "</pre>";

        try {
            $insertOneResult = $clientRecordsCollection->insertOne($json_data);
            $insertOneResultId =  $insertOneResult->getInsertedId();
            echo "Inserted successfully in Monogodb database @ : " . $insertOneResultId;
        } catch (Exception $ex) {
            echo "[Exception occured]: " . $ex->getMessage();
        }
    }
}

function checkFieldInErrors($fieldName)
{
    global $errors, $error_span;

    if (isset($_POST["taxFormSubmitBtn"]))  if (in_array($fieldName, $errors)) echo $error_span;
}

function getFieldValue($fieldName)
{
    global $values;

    if (isset($_POST["taxFormSubmitBtn"]) && array_key_exists($fieldName, $values)) {

        echo htmlentities($values[$fieldName]);
    }
}

function getSelectSelected($fieldName, $optionValue)
{
    global $values;

    if (isset($_POST["taxFormSubmitBtn"]) && array_key_exists($fieldName, $values) && $values[$fieldName] === $optionValue) return "selected";
}

function getRadioChecked($fieldName, $radioValue)
{
    global $values;

    if (isset($_POST["taxFormSubmitBtn"]) && array_key_exists($fieldName, $values) && $values[$fieldName] === $radioValue) return "checked";
}

function getCheckboxChecked($fieldName, $checkboxValue)
{
    global $values;

    if (isset($_POST["taxFormSubmitBtn"]) && array_key_exists($fieldName, $values) && $values[$fieldName] === $checkboxValue) return "checked";
}
?>

<body id="taxFormPage">

    <header>
        <div class="container">

            <div class="header__main-wrapper gx-4 d-flex justify-content-start align-items-center">
                <img src="./images/canada-flag.png" alt="canada-flag-img not available">
                <h4 class="m-0 text-uppercase header__logo-text">tax filling form</h4>
            </div>
        </div>
    </header>

    <main>

        <form method="POST" action="<?php htmlentities($_SERVER['PHP_SELF']) ?>" autocomplete="off" class="tax-form__form" id="taxt-form">

            <!-- personal details section -->
            <section class="tax-form__form-sections">

                <div class="container p-0">
                    <h4>personal details</h4>
                    <hr>

                    <!-- first name and last name -->
                    <div class="tax-form__row-my row mx-0">
                        <div class="ps-0 col-6">

                            <label for="firstnameinput" class="tax-form__label">first name</label>

                            <?php if (isset($_POST["taxFormSubmitBtn"])) checkFieldInErrors('clientFirstName') ?>

                            <input type="text" class="tax-form__input form-control" name="clientFirstName" value="<?php getFieldValue("clientFirstName") ?>" id="firstnameinput" placeholder="Enter your first name">
                        </div>


                        <div class="pe-0 col-6">
                            <label for="lastnameinput" class="tax-form__label">last name</label>

                            <?php if (isset($_POST["taxFormSubmitBtn"]))  checkFieldInErrors("clientLastName") ?>

                            <input type="text" class="tax-form__input form-control" name="clientLastName" value="<?php getFieldValue("clientLastName") ?>" id="lastnameinput" placeholder="Enter your last name">
                        </div>
                    </div>

                    <!-- contact number & email address -->
                    <div class="tax-form__row-my row mx-0">
                        <div class="ps-0 col-6">
                            <label for="contactnuminput" class="tax-form__label">contact number</label>
                            <input type="number" class="tax-form__input form-control" name="clientContactNum" value="<?php getFieldValue("clientContactNum") ?>" id="contactnuminput" placeholder="Enter your contact number">
                        </div>

                        <div class="pe-0 col-6">
                            <label for="emailinput" class="tax-form__label">Email</label>

                            <?php if (isset($_POST["taxFormSubmitBtn"])) checkFieldInErrors("clientEmail") ?>

                            <input type="email" class="tax-form__input form-control" name="clientEmail" value="<?php getFieldValue("clientEmail") ?>" id="emailinput" placeholder="Enter your email">
                        </div>
                    </div>

                    <!-- college name, sin number & birth date -->
                    <div class="tax-form__row-my row mx-0">
                        <div class="ps-0 col">

                            <label for="collegenameinput" class="tax-form__label">college name</label>
                            <input type="text" class="tax-form__input form-control" name="clientCollegeName" value="<?php getFieldValue("clientCollegeName") ?>" id="collegenameinput" placeholder="Enter your college name">
                        </div>

                        <div class="pe-0 col">
                            <label for="sinnumberinput" class="tax-form__label">SIN number</label>

                            <?php if (isset($_POST["taxFormSubmitBtn"])) checkFieldInErrors("clientSinNum") ?>

                            <input type="number" class="tax-form__input form-control" name="clientSinNum" value="<?php getFieldValue("clientSinNum") ?>" id="sinnumberinput" placeholder="Enter your SIN number">
                        </div>

                        <div class="pe-0 col">
                            <label for="birthdateinput" class="tax-form__label">birthdate</label>

                            <?php if (isset($_POST["taxFormSubmitBtn"])) checkFieldInErrors("clientBirthdate") ?>

                            <input type="date" class="tax-form__input form-control" name="clientBirthdate" value="<?php getFieldValue("clientBirthdate") ?>" id="birthdateinput" placeholder="Enter your birth date">
                        </div>
                    </div>

                    <!-- nationaility & lives alone -->
                    <div class="row mx-0">

                        <!-- client nationality -->
                        <div class="pt-2 ps-0 col-4">
                            <label for="clientNationality" class="tax-form__label">nationality</label>

                            <?php if (isset($_POST["taxFormSubmitBtn"])) checkFieldInErrors("clientNationality") ?>

                            <input type="text" class="tax-form__input form-control" name="clientNationality" value="<?php getFieldValue("clientNationality") ?>" id="clientNationality" placeholder="Enter your nationality">
                        </div>

                        <!-- client lives alone or not ?? -->
                        <div class="pt-2 pe-0 col-6">
                            <label class="d-block tax-form__label">lives alone ?</label>

                            <div class="d-flex justify-content-start align-items-center gap-5">

                                <!-- yes -->
                                <span class="cursor-pointer">
                                    <input type="radio" value="yes" <?php echo getRadioChecked("isClientAloneYesNo", "yes") ?> value="yes" name="isClientAloneYesNo" id="isClientAloneYes">
                                    <label for="isClientAloneYes" class="text-uppercase">yes</label>
                                </span>

                                <!-- no -->
                                <span class="cursor-pointer">
                                    <input type="radio" value="no" <?php echo getRadioChecked("isClientAloneYesNo", "no") ?> value="no" name="isClientAloneYesNo" id="isClientAloneNo">
                                    <label for="isClientAloneNo" class="text-uppercase">no</label>
                                </span>
                            </div>


                        </div>
                    </div>
                </div>

            </section>

            <!-- email address authorization section -->
            <section class="tax-form__form-sections">

                <div class="container p-0">
                    <h4>mailing service authorization</h4>
                    <hr>

                    <div class="d-flex justify-content-start align-items-center gap-4 tax-form__row-my">

                        <p class="m-0">Authorizes the registeration for the <b>CRA Online Mailing Service - </b></p>

                        <div class="d-flex justify-content-start align-items-center gap-5">

                            <!-- yes-->
                            <span class="cursor-pointer">

                                <input value="yes" <?php echo getRadioChecked("mailingService", "yes") ?> type="radio" name="mailingService" id="mailingServiceYes">
                                <label for="mailingServiceYes" class="text-uppercase">yes</label>
                            </span>

                            <span class="cursor-pointer">
                                <input value="no" <?php echo getRadioChecked("mailingService", "no") ?> type="radio" name="mailingService" id="mailingServiceNo">
                                <label for="mailingServiceNo" class="text-uppercase">no</label>
                            </span>
                        </div>
                    </div>
                </div>

            </section>

            <!-- province -->
            <section class="tax-form__form-sections">

                <div class="container p-0">
                    <h4>province</h4>
                    <hr>

                    <!-- Canada landed date-->
                    <div class="tax-form__row-my row mx-0">
                        <div class="ps-0 col-5 d-flex justify-content-start align-items-center gap-4  flex-nowrap">
                            <p class="m-0 text-nowrap">Landed in Canada on - </p>

                            <input type="date" class="tax-form__input form-control" name="canadaLandedDate" value="<?php getFieldValue("canadaLandedDate") ?>">
                        </div>
                    </div>

                    <!-- have always been in Quebec -->
                    <div class="d-flex justify-content-start align-items-center gap-4 tax-form__row-my">
                        <p class="m-0">Have always been in Quebec - </b></p>

                        <div class="d-flex justify-content-start align-items-center gap-5">
                            <!-- yes-->
                            <span class="cursor-pointer">
                                <input type="radio" value="yes" <?php echo getRadioChecked("alwaysInQuebec", "yes") ?> name="alwaysInQuebec" id="alwaysInQuebecYes">
                                <label for="alwaysInQuebecYes" class="text-uppercase">yes</label>
                            </span>

                            <span class="cursor-pointer">
                                <input type="radio" value="no" <?php echo getRadioChecked("alwaysInQuebec", "no") ?> name="alwaysInQuebec" id="alwaysInQuebecNo">
                                <label for="alwaysInQuebecNo" class="text-uppercase">no</label>
                            </span>
                        </div>
                    </div>

                    <!-- Never move any province -->
                    <div class="d-flex justify-content-start align-items-center gap-4 tax-form__row-my">
                        <p class="m-0">Never move any province - </b></p>

                        <div class="d-flex justify-content-start align-items-center gap-5">
                            <!-- yes-->
                            <span class="cursor-pointer">
                                <input type="radio" value="yes" <?php echo getRadioChecked("everChangedProvince", "yes") ?> name="everChangedProvince" id="everChangedProvinceYes">
                                <label for="everChangedProvinceYes" class="text-uppercase">yes</label>
                            </span>

                            <span class="cursor-pointer">
                                <input type="radio" value="no" <?php echo getRadioChecked("everChangedProvince", "no") ?> name="everChangedProvince" id="everChangedProvinceNo">
                                <label for="everChangedProvinceNo" class="text-uppercase">no</label>
                            </span>
                        </div>
                    </div>
                </div>

            </section>

            <!-- martial status section -->
            <section class="tax-form__form-sections">

                <div class="container p-0">
                    <h4>Martial status</h4>
                    <hr>

                    <div class="d-flex justify-content-start align-items-center gap-4 tax-form__row-my">
                        <div class="col">

                            <?php if (isset($_POST["taxFormSubmitBtn"]))  checkFieldInErrors("clientMartialStatus") ?>

                            <select name="clientMartialStatus" class="tax-form__input form-control">
                                <option selected value="">-- Choose martial status --</option>
                                <option <?php echo getSelectSelected("clientMartialStatus", "single") ?>>single</option>
                                <option <?php echo getSelectSelected("clientMartialStatus", "married") ?>>married</option>
                                <option <?php echo getSelectSelected("clientMartialStatus", "separated") ?>>separated</option>
                                <option <?php echo getSelectSelected("clientMartialStatus", "widowed") ?>>widowed</option>
                                <option <?php echo getSelectSelected("clientMartialStatus", "common-law") ?>>common-law</option>
                                <option <?php echo getSelectSelected("clientMartialStatus", "divorced") ?>>divorced</option>
                            </select>
                        </div>
                    </div>
                </div>

            </section>

            <!-- residency status -->
            <section class="tax-form__form-sections">

                <div class="container p-0">
                    <h4>residency status</h4>
                    <hr>

                    <div class="d-flex justify-content-start align-items-center gap-4 tax-form__row-my">
                        <div class="col">

                            <?php if (isset($_POST["taxFormSubmitBtn"]))  checkFieldInErrors("clientResidencyStatus") ?>

                            <select name="clientResidencyStatus" value="<?php getFieldValue("clientResidencyStatus") ?>" class="tax-form__input form-control">
                                <option value="" selected>-- Choose residency status --</option>
                                <option <?php echo getSelectSelected("clientResidencyStatus", "international student") ?>>international student</option>
                                <option <?php echo getSelectSelected("clientResidencyStatus", "canadian citizen") ?>>canadian citizen</option>
                            </select>

                        </div>
                    </div>
                </div>

            </section>

            <!-- Address -->
            <section class="tax-form__form-sections">

                <div class="container p-0">
                    <h4>address</h4>
                    <hr>

                    <!-- isHomeMailingAddrSame -->
                    <div class="d-flex justify-content-start align-items-center gap-4 tax-form__row-my">
                        <p class="m-0">Is your <span class="text-uppercase">home & mailing</span> address same ? </p>

                        <!-- yes / no radio buttons -->
                        <div class="d-inline-flex justify-content-start align-items-center gap-5">
                            <!-- yes-->
                            <span class="cursor-pointer">

                                <input type="radio" value="yes" <?php echo getRadioChecked("isHomeMailingAddrSame", "yes") ?> name="isHomeMailingAddrSame" id="isHomeMailingAddrSameYes">
                                <label for="isHomeMailingAddrSameYes" class="text-uppercase">yes</label>
                            </span>

                            <span class="cursor-pointer">
                                <input type="radio" value="no" <?php echo getRadioChecked("isHomeMailingAddrSame", "no") ?> name="isHomeMailingAddrSame" id="isHomeMailingAddrSameNo">
                                <label for="isHomeMailingAddrSameNo" class="text-uppercase">no</label>
                            </span>
                        </div>
                    </div>

                    <!-- append node's parent -->
                    <div class="appendNodeParent tax-form__row-my">

                    </div>

                    <!-- NO | template 1 | 2 inputs -->
                    <template id="homeMailingTemplate">

                        <div class="row mx-0">
                            <div class="ps-0 col-6">
                                <label for="clientHomeAddress" class="tax-form__label">client Home Address</label>

                                <input type="text" class="tax-form__input form-control" name="clientHomeAddress" id="clientHomeAddress" placeholder="Enter your Home Address">
                            </div>

                            <div class="pe-0 col-6">
                                <label for="clientMailingAddress" class="tax-form__label">client mailing Address</label>

                                <input type="text" class="tax-form__input form-control" name="clientMailingAddress" id="clientMailingAddress" placeholder="Enter your Mailing Address">
                            </div>
                        </div>

                    </template>

                    <!-- YES | template 2 | 1 input -->
                    <template id="homeTemplate">

                        <div class="row mx-0">
                            <div class="ps-0 col-6">
                                <label for="clientHomeAddress" class="tax-form__label">client Home Address</label>

                                <input type="text" class="tax-form__input form-control" name="clientHomeAddress" id="clientHomeAddress" placeholder="Enter your Address">
                            </div>
                        </div>

                    </template>

                </div>
                <!-- container ends -->

            </section>

            <!-- Wealth -->
            <section class="tax-form__form-sections">

                <div class="container p-0">
                    <h4>Wealth</h4>
                    <hr>

                    <!-- residence disposing -->
                    <div class="d-flex justify-content-start align-items-center gap-4 tax-form__row-my">
                        <p class="m-0">Disposed of any residence in the taxation year 2020 - </b></p>

                        <div class="d-flex justify-content-start align-items-center gap-5">
                            <!-- yes-->
                            <span class="cursor-pointer">
                                <input type="radio" value="yes" <?php echo getRadioChecked("isResidenceDisposed", "yes") ?> name="isResidenceDisposed" id="isResidenceDisposedYes">
                                <label for="isResidenceDisposedYes" class="text-uppercase">yes</label>
                            </span>

                            <span class="cursor-pointer">
                                <input type="radio" value="no" <?php echo getRadioChecked("isResidenceDisposed", "no") ?> name="isResidenceDisposed" id="isResidenceDisposedNo">
                                <label for="isResidenceDisposedNo" class="text-uppercase">no</label>
                            </span>
                        </div>
                    </div>

                    <!-- any foreign income -->
                    <div class="d-flex justify-content-start align-items-center gap-4 tax-form__row-my">
                        <p class="m-0">Any foreign income from <b class="text-uppercase">home / any country</b> - </b></p>

                        <div class="d-flex justify-content-start align-items-center gap-5">
                            <!-- yes-->
                            <span class="cursor-pointer">
                                <input type="radio" value="yes" <?php echo getRadioChecked("anyForeignIncome", "yes") ?> name="anyForeignIncome" id="anyForeignIncomeYes">
                                <label for="anyForeignIncomeYes" class="text-uppercase">yes</label>
                            </span>

                            <span class="cursor-pointer">
                                <input type="radio" value="no" <?php echo getRadioChecked("anyForeignIncome", "no") ?> name="anyForeignIncome" id="anyForeignIncomeNo">
                                <label for="anyForeignIncomeNo" class="text-uppercase">no</label>
                            </span>
                        </div>
                    </div>

                    <!-- own any property > 100k -->
                    <div class="d-flex justify-content-start align-items-center gap-4 tax-form__row-my">
                        <p class="m-0">Own any property worth more than <b>$100,000 outside Canada</b> - </b></p>

                        <div class="d-flex justify-content-start align-items-center gap-5">
                            <!-- yes-->
                            <span class="cursor-pointer">
                                <input type="radio" value="yes" <?php echo getRadioChecked("ownProperty100k", "yes") ?> name="ownProperty100k" id="ownProperty100kYes">
                                <label for="ownProperty100kYes" class="text-uppercase">yes</label>
                            </span>

                            <span class="cursor-pointer">
                                <input type="radio" value="no" <?php echo getRadioChecked("ownProperty100k", "no") ?> name="ownProperty100k" id="ownProperty100kNo">
                                <label for="ownProperty100kNo" class="text-uppercase">no</label>
                            </span>
                        </div>
                    </div>

                    <!-- donations made -->
                    <div class="d-flex justify-content-start align-items-center gap-4 tax-form__row-my">
                        <p class="m-0">Donation made in the taxation year 2020 - </p>

                        <div class="d-flex justify-content-start align-items-center gap-5">
                            <!-- yes-->
                            <span class="cursor-pointer">
                                <input type="radio" value="yes" <?php echo getRadioChecked("anyMadeDonations", "yes") ?> name="anyMadeDonations" id="anyMadeDonationsYes">
                                <label for="anyMadeDonationsYes" class="text-uppercase">yes</label>
                            </span>

                            <span class="cursor-pointer">
                                <input type="radio" value="no" <?php echo getRadioChecked("anyMadeDonations", "no") ?> name="anyMadeDonations" id="anyMadeDonationsNo">
                                <label for="anyMadeDonationsNo" class="text-uppercase">no</label>
                            </span>
                        </div>
                    </div>

                    <!-- RRSP Contribution -->
                    <div class="d-flex justify-content-start align-items-center gap-4 tax-form__row-my">
                        <p class="m-0">Any contributions in RRSP - </p>

                        <div class="d-flex justify-content-start align-items-center gap-5">
                            <!-- yes-->
                            <span class="cursor-pointer">
                                <input type="radio" value="yes" <?php echo getRadioChecked("anyRrsp", "yes") ?> name="anyRrsp" id="anyRrspYes">
                                <label for="anyRrspYes" class="text-uppercase">yes</label>
                            </span>

                            <span class="cursor-pointer">
                                <input type="radio" value="no" <?php echo getRadioChecked("anyRrsp", "no") ?> name="anyRrsp" id="anyRrspNo">
                                <label for="anyRrspNo" class="text-uppercase">no</label>
                            </span>
                        </div>
                    </div>

                </div>

            </section>

            <!-- Medical -->
            <section class="tax-form__form-sections">

                <div class="container p-0">
                    <h4>Medical</h4>
                    <hr>

                    <!-- any medical expenses ? -->
                    <div class="d-flex justify-content-start align-items-center gap-4 tax-form__row-my">
                        <p class="m-0">Any medical expenses incurred in the taxation year 2020 - </b></p>

                        <div class="d-flex justify-content-start align-items-center gap-5">
                            <!-- yes-->
                            <span class="cursor-pointer">
                                <input type="radio" value="yes" <?php echo getRadioChecked("anyMedicalExpenses", "yes") ?> name="anyMedicalExpenses" id="anyMedicalExpensesYes">
                                <label for="anyMedicalExpensesYes" class="text-uppercase">yes</label>
                            </span>

                            <span class="cursor-pointer">
                                <input type="radio" value="no" <?php echo getRadioChecked("anyMedicalExpenses", "no") ?> name="anyMedicalExpenses" id="anyMedicalExpensesNo">
                                <label for="anyMedicalExpensesNo" class="text-uppercase">no</label>
                            </span>
                        </div>
                    </div>

                </div>

            </section>

            <!-- documents checklist -->
            <section class="tax-form__form-sections">

                <div class="container p-0">
                    <h4>Documents Checklist: </h4>
                    <hr>

                    <!-- docs check list flex container -->
                    <div class="d-flex justify-content-start align-items-start flex-column">

                        <!-- T4 Form -->
                        <div class="d-inline-flex justify-content-start align-items-center mt-4 mb-4 gap-5">
                            <input class="rounded-0 form-check-input tax-form__input" type="checkbox" value="tFourFormTaken" <?php echo getCheckboxChecked("tfourform", "tFourFormTaken") ?> name="tfourform" id="tfourform">
                            <label for="tfourform" class="mb-0 tax-form__label">T4 Form</label>
                        </div>

                        <!-- RL-1 Form -->
                        <div class="d-inline-flex justify-content-start align-items-center mb-4 gap-5">
                            <input class="rounded-0 form-check-input tax-form__input" type="checkbox" value="RL1FormTaken" <?php echo getCheckboxChecked("RL1Form", "RL1FormTaken") ?> name="RL1Form" id="RL1Form">
                            <label for="RL1Form" class="mb-0 tax-form__label">RL-1 Form</label>
                        </div>

                        <!-- T2202A Form -->
                        <div class="d-inline-flex justify-content-start align-items-center mb-4 gap-5">
                            <input class="rounded-0 form-check-input tax-form__input" type="checkbox" value="T2202AFormTaken" <?php echo getCheckboxChecked("T2202AForm", "T2202AFormTaken") ?> name="T2202AForm" id="T2202AForm">
                            <label for="T2202AForm" class="mb-0 tax-form__label">T2202A Form</label>
                        </div>

                        <!-- RL8 Form -->
                        <div class="d-inline-flex justify-content-start align-items-center mb-4 gap-5">
                            <input class="rounded-0 form-check-input tax-form__input" type="checkbox" value="RL8FormTaken" <?php echo getCheckboxChecked("RL8Form", "RL8FormTaken") ?> name="RL8Form" id="RL8Form">
                            <label for="RL8Form" class="mb-0 tax-form__label">RL-8 Form</label>
                        </div>

                        <!-- RL-31 Form -->
                        <div class="d-inline-flex justify-content-start align-items-center mb-4 gap-5">
                            <input class="rounded-0 form-check-input tax-form__input" type="checkbox" value="RL31FormTaken" <?php echo getCheckboxChecked("RL31Form", "RL31FormTaken") ?> name="RL31Form" id="RL31Form">
                            <label for="RL31Form" class="mb-0 tax-form__label">RL-31 Form</label>
                        </div>

                        <!-- Void Cheque -->
                        <div class="d-inline-flex justify-content-start align-items-center mb-4 gap-5">
                            <input class="rounded-0 form-check-input tax-form__input" type="checkbox" value="voidChequeTaken" <?php echo getCheckboxChecked("voidCheque", "voidChequeTaken") ?> name="voidCheque" id="voidCheque">
                            <label for="voidCheque" class="mb-0 tax-form__label">Void Cheque</label>
                        </div>

                        <!-- any pending documents -->
                        <div class="d-flex justify-content-start align-items-center gap-4 tax-form__row-my">
                            <p class="m-0">Any <b>pending documents</b> ? - </b></p>

                            <div class="d-flex justify-content-start align-items-center gap-5">
                                <!-- yes-->
                                <span class="cursor-pointer">
                                    <input type="radio" value="yes" <?php echo getRadioChecked("anyPendingdocs", "yes") ?> name="anyPendingdocs" id="anyPendingdocsYes">
                                    <label for="anyPendingdocsYes" class="text-uppercase">yes</label>
                                </span>

                                <span class="cursor-pointer">
                                    <input type="radio" value="no" <?php echo getRadioChecked("anyPendingdocs", "no") ?> name="anyPendingdocs" id="anyPendingdocsNo">
                                    <label for="anyPendingdocsNo" class="text-uppercase">no</label>
                                </span>
                            </div>
                        </div>

                    </div>
                </div>

            </section>

            <!-- DIRECT DEPOSIT INFORMATION section -->
            <section class="tax-form__form-sections">

                <div class="container p-0">
                    <h4>direct deposit information</h4>
                    <hr>

                    <!-- Finacial institution name & number -->
                    <div class="tax-form__row-my row mx-0">

                        <!-- Finacial Institution Name -->
                        <div class="ps-0 col-6">
                            <label for="clientFinacialInstName" class="tax-form__label">Finacial Institution Name: </label>
                            <input type="text" class="tax-form__input form-control" value="<?php getFieldValue("clientFinacialInstName") ?>" name="clientFinacialInstName" id="clientFinacialInstName" placeholder="Enter name of your Finacial Institution">
                        </div>

                        <!-- Instution Number -->
                        <div class="pe-0 col-6">
                            <label for="clientInstNum" class="tax-form__label">Instution Number: </label>
                            <input type="number" class="tax-form__input form-control" value="<?php getFieldValue("clientInstNum") ?>" name="clientInstNum" id="clientInstNum" placeholder="Enter your instution number">
                        </div>
                    </div>

                    <!-- branch & acc number -->
                    <div class="tax-form__row-my row mx-0">

                        <!-- Branch number -->
                        <div class="ps-0 col-6">
                            <label for="clientBranchNum" class="tax-form__label"> Branch number: </label>
                            <input type="number" class="tax-form__input form-control" value="<?php getFieldValue("clientBranchNum") ?>" name="clientBranchNum" id="clientBranchNum" placeholder="Enter branch number">
                        </div>

                        <!-- acc Number -->
                        <div class="pe-0 col-6">
                            <label for="clientAccNum" class="tax-form__label">Account Number: </label>
                            <input type="number" class="tax-form__input form-control" value="<?php getFieldValue("clientAccNum") ?>" name="clientAccNum" id="clientAccNum" placeholder="Enter account number">
                        </div>
                    </div>

                </div>

            </section>

            <!-- submit & reset buttons -->
            <section class="tax-form__form-sections">

                <div class="container p-0">

                    <!-- Buttons -->
                    <div class="tax-form__row-my gap-5 row mx-0">

                        <!-- submit button -->
                        <button class="tax-form__button __button-basic-style col-2" formmethod="post" name="taxFormSubmitBtn" type="submit">Submit</button>

                        <!-- reset button -->
                        <button class="tax-form__button __button-basic-style col-2" name="taxFormResetBtn" type="reset">Reset</button>

                    </div>

                </div>

            </section>

        </form>

    </main>

    <footer></footer>


    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>

    <!-- for address radios buttons -->
    <script>
        const appendNodeParent = document.querySelector(".appendNodeParent");

        const homeMailingTemplate = document.getElementById("homeMailingTemplate");
        // const homeMailingTemplateClone = homeMailingTemplate.content.cloneNode(true);

        const homeTemplate = document.getElementById("homeTemplate");
        // const homeTemplateClone = homeTemplate.content.cloneNode(true);

        const addressRads = [...document.getElementsByName("isHomeMailingAddrSame")];

        function removeFirstChild(parent) {
            parent.removeChild([...parent.children][0]);
        }

        function appenedToParent(isHomeMailingAddrSame) {

            if (isHomeMailingAddrSame === "yes") {
                // console.log(`in yes & total parent children: ${[...appendNodeParent.children].length}`);

                if ([...appendNodeParent.children].length !== 0) removeFirstChild(appendNodeParent);
                appendNodeParent.appendChild(homeTemplate.content.cloneNode(true));

            } else {
                // console.log(`in no & total parent children: ${[...appendNodeParent.children].length}`);

                if ([...appendNodeParent.children].length !== 0) removeFirstChild(appendNodeParent);
                appendNodeParent.appendChild(homeMailingTemplate.content.cloneNode(true));
            }
        }

        addressRads.forEach((radioInput) => {

            (radioInput.checked) ? appenedToParent(radioInput.value): "";

            radioInput.addEventListener('change', e => {
                appenedToParent(e.target.value);

            });
        });
    </script>
</body>

</html>