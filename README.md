# Purpose

This is my `College's Php Project` which aims at making it easier for accountants to get information from their clients who want to file their individual taxes by filing out the form. I've applied some `Form Validations` on the form on some important fields which're mandatory to be filled by the user, also storing client information on the databse through connectivity with `MongoDb`

<p>&nbsp;</p>

# Tax Form Field Names 
1. Personal Details
    * name="clientFirstName"
    * name="clientLastName"
    * name="clientContactNum"
    * name="clientEmail" 
    * name="clientCollegeName"
    * name="clientSinNum"
    * name="clientBirthdate"
    * name="clientNationality"
    * name="isClientAloneYesNo"

<br />

2. Mailing Service Authorization
    * name="mailingService"

<br />

3. Province Details
    * name="canadaLandedDate"
    * name="alwaysInQuebec" 
    * name="everChangedProvince"

<br />

4. Martial Status
    * name="clientMartialStatus"

<br />

5.  Residency Status
    * name="clientResidencyStatus"

<br />

6. Address
    * name="isHomeMailingAddrSame"
    * YES = name="clientHomeAddress"
    * NO = name="clientMailingAddress" |  name="clientHomeAddress"

<br />

7. Wealth
    * name="isResidenceDisposed"
    * name="anyForeignIncome"
    * name="ownProperty100k"
    * name="anyMadeDonations"
    * name="anyRrsp"

<br />

8. Medical
    * name="anyMedicalExpenses"

<br />

9. Document Checklist
    * name="tfourform"
    * name="RL1Form"
    * name="T2202AForm"
    * name="RL8Form"
    * name="RL31Form"
    * name="voidCheque"
    * name="anyPendingdocs"

<br />

10. Direct Deposit Information
    * name="clientFinacialInstName"
    * name="clientInstNum"
    * name="clientBranchNum"
    * name="clientAccNum"

<br />
    
11. Buttons
    * name="taxFormSubmitBtn"
    * name="taxFormResetBtn"

<p>&nbsp;</p>

# Step by Step Project Setup Guidance

1. Installing MongoDB community Server

    <p>Install MongoDb from the following link.</p><br />

     >[Installing MongoDb Link](https://www.mongodb.com/try/download/community) 

    <br />

2. Installing XAMPP

    <p>Install XAMPP from the following link.</p><br />

    >[Installing XAMPP Link](https://www.apachefriends.org/download.html) 

    <br />

     <p><b>RECOMMEDATION NOTE :</b> &nbsp; No need to install Php separately, XAMPP is self equipped with it.</p><br/>



3. Get Php information
    
    <p> Check for Running Php version, Architecture & Thread Safety. One can get all these details by adding following code to the top of php script.</p>
    <br />
    
    <code>echo phpinfo();</code>
    
    <br />

4. Installing MongoDb Php Extension (Using PECL)

    <p>One needs to install php MongoDb extension, according to php information one gets through step 1, from the following link</p>
    <br />

    >[Installing Extension Link](https://pecl.php.net/package/mongodb)

     <br />
    <p><b>RECOMMEDATION NOTE :</b> &nbsp; To install the most recent stable version by clicking on the corresponding <b>DLL</b> link, and then choosing DLL file from the <b>DLL List</b> according to the php version, system architecture and thread safety.</p>

    <p>&nbsp;</p>

    <p>Check for installed  MongoDb Php Extension by adding the following code at the top of php script. One will get <b>monogodb extension loaded</b> as an output.</p><br />

    <code>echo extension_loaded("mongodb") ? "monogodb extension loaded\n" : "not loaded\n";</code>

<p>&nbsp;</p>

5. Configuring Php Extension
    <p>Add the following to the <b>XAMPP > php > php.ini</b> file of XAMPP.</p><br />

    > extension=php_mongodb.dll


    <br />
    <p>Restart Apache server from XAMPP's control panel.</p><br />

6. Installing Composer

   <p>Composer is the php's <b>package manager</b>, just like npm is the official package manager of node js. Install Composer from following given link.</p><br/>

   >[Installing Composer Link](https://getcomposer.org/)
  
   <br />

    <p>Check for the composer by opening <b>cmd</b> and type <b>Composer</b>. If everything is good, then you will see Composer as a result.</p><br />


7. Installing MongoDb package/library using Composer

    <p> For installing mongoDb library, do the following command in CLI.</p><br />
    
    >composer require mongodb/mongodb

    <br />

    <p><b>RECOMMEDATION NOTE :</b> &nbsp;&nbsp; To do the above command through cmd from the <b>root directory</b> of your project.</p><br />

8. Checking for Installed MongoDb package

    <p>After completing <b>STEP 5</b>, Composer will create <b>composer.json and composer.lock</b> files in the root directory of the project. And the following added <b>dependencies</b> can be seen in the composer.json file.</p><br />

    ```
    {
        "require": {
            "mongodb/mongodb": "^1.8"
        }
    }
    ```
    <br />

9. Configure Autoloading of MongoDb php library

    <p>Add the following code to the top level of the php script.</p><br />

    <code>
    require_once __DIR__ . '/vendor/autoload.php';
    </code>






    