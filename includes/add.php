<?php 
// Include database connection
include "../header.php";
include "../db.php";

if(isset($_POST['create'])) {
    // Retrieve form data
    $date = date('Y-m-d', strtotime($_POST['Date']));
    $patient = isset($_POST['patient']) ? $_POST['patient'] : '';               
    $guarantor = isset($_POST['guarantor']) ? $_POST['guarantor'] : '';        
    $address = isset($_POST['address']) ? $_POST['address'] : ''; 
    $contact = isset($_POST['contact']) ? $_POST['contact'] : ''; 
    $amount = isset($_POST['amount']) ? $_POST['amount'] : ''; 
    $due_date = date('Y-m-d', strtotime($_POST['Due_Date']));
    $collateral_types = isset($_POST['collateral_type']) ? $_POST['collateral_type'] : array();  

    // Initialize variables to store uploaded file names
    $promissory_note_filename = '';
    $collateral_image_filenames = array();
    $statement_of_account_filename = '';

    if(isset($_FILES['Promissory_Note'])) {
        // Define target directory for uploaded files
        $target_directory = "promissory_notes/";
        // Get the name of the uploaded file
        $promissory_note_filename = basename($_FILES["Promissory_Note"]["name"]);
        // Define the target path for the uploaded file
        $target_path = $target_directory . $promissory_note_filename;
        
        // Move the uploaded file to the target directory
        if(move_uploaded_file($_FILES["Promissory_Note"]["tmp_name"], $target_path)) {
            // File upload successful, proceed to save the filename in the database
        } else {
            echo "Sorry, there was an error uploading your file.";
            // You can handle this error according to your requirements
        }
    }
    if(isset($_FILES['Collateral_Image'])) {
        // Define target directory for uploaded files
        $target_directory = "collateral_images/";
        
        // Loop through each uploaded collateral image
        foreach($_FILES['Collateral_Image']['tmp_name'] as $key => $tmp_name) {
            // Get the name of the uploaded file
            $collateral_image_filename = basename($_FILES["Collateral_Image"]["name"][$key]);
            // Define the target path for the uploaded file
            $target_path = $target_directory . $collateral_image_filename;
            
            // Move the uploaded file to the target directory
            if(move_uploaded_file($tmp_name, $target_path)) {
                $collateral_image_filenames[] = $collateral_image_filename;
            } else {
                echo "Sorry, there was an error uploading your file.";
                // You can handle this error according to your requirements
            }
        }
    }

    // Combine collateral types with their respective image filenames
    $collateral_given = array();
    foreach ($collateral_types as $key => $collateral_type) {
        $collateral_given[] = $collateral_type;
    }
    // Combine collateral given into a single string
    $collateral = implode(", ", $collateral_given);
    
    // Check if any collateral images were uploaded
    if (!empty($collateral_image_filenames)) {
        // Combine multiple collateral image filenames into a single string
        $collateral_images_string = implode(", ", $collateral_image_filenames);
    } else {
        // If no collateral images were uploaded, set the value to an empty string
        $collateral_images_string = '';
    }

    if(isset($_FILES['Statement_of_Account'])) {
        // Define target directory for uploaded files
        $target_directory = "statement_of_account/";
        // Get the name of the uploaded file
        $statement_of_account_filename = basename($_FILES["Statement_of_Account"]["name"]);
        // Define the target path for the uploaded file
        $target_path = $target_directory . $statement_of_account_filename;
        
        // Move the uploaded file to the target directory
        if(move_uploaded_file($_FILES["Statement_of_Account"]["tmp_name"], $target_path)) {
            // File upload successful, proceed to save the filename in the database
        } else {
            echo "Sorry, there was an error uploading your file.";
            // You can handle this error according to your requirements
        }
    }

    // SQL query to insert user data and uploaded promissory note filename into the database
    $query= "INSERT INTO billing (Date, Patient_Name, Name_Gaurantor, Address, Contact, Amount, Due_Date, Collateral_Given, Promissory_Note, Collateral_Image, Statement_of_Account) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)";

    // Prepare the SQL statement
    $stmt = mysqli_prepare($conn, $query);
    
    // Check if prepare statement succeeded
    if ($stmt) {
        // Bind parameters
        mysqli_stmt_bind_param($stmt, "ssssissssss", $date, $patient, $guarantor, $address, $contact, $amount, $due_date, $collateral, $promissory_note_filename, $collateral_images_string, $statement_of_account_filename);

        // Execute the statement
        $result = mysqli_stmt_execute($stmt);
    
        // Check if the query executed successfully
        if ($result) {
            echo "<script type='text/javascript'>alert('User added successfully!'); window.location.href = 'home.php';</script>";
        } else {
            echo "Something went wrong: " . mysqli_error($conn);
        }
    
        // Close statement
        mysqli_stmt_close($stmt);
    } else {
        // Handle prepare statement error
        echo "Prepare statement error: " . mysqli_error($conn);
    }
}

// Close connection
mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>Responsive Registration Form | CodingLab</title>
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
  
<div class="container">
    <div class="title">Add New Patient</div>
    <div class="content">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="user-details">
                 <div class="input-box">
                    <span class="details">Date</span>
                    <input type="datetime-local" name="Date" required>
                </div>
                <div class="input-box">
                    <span class="details">Patient Name</span>
                    <input type="text" name="patient" placeholder="Enter Patient Name" required>
                </div>
                <div class="input-box">
                    <span class="details">Name of Guarantor</span>
                    <input type="text" name="guarantor" placeholder="Enter Name of Guarantor" required>
                </div>
                <div class="input-box">
                    <span class="details">Address</span>
                    <input type="text" name="address" placeholder="Enter Address" required>
                </div>
                <div class="input-box">
                    <label for="lastName"> Contact </label>
                    <input type="text" name="contact" placeholder="Enter Contact Number" required>
                </div>
                <div class="input-box">
                    <span class="details">Amount</span>
                    <input type="text" name="amount" placeholder="Enter Amount" required>
                </div>
                
                <div class="input-box">
                    <span class="details">Due Date</span>
                    <input type="datetime-local" name="Due_Date" required>
                </div>

                <div class="input-box">
                    <span class="details">Collateral Given</span>
                    <div id="collateral-container">
                        <div class="collateral-item">
                            <input type="text" name="collateral_type[]" placeholder="Collateral Type" >
                            <input type="file" name="Collateral_Image[]" accept="image/*" >
                        </div>
                    </div>
                    <button type="button" onclick="addCollateral()">Add Collateral</button>
                </div>

            <div class="input-box">
        <span class="details">Upload Promissory Notes</span>
      <input type="file" name="Promissory_Note" accept="image/*" >
            </div>         
            <div class="input-box">
         <span class="details">Upload Statement of Account</span>
     <input type="file" name="Statement_of_Account" accept="image/*" >
            </div>         
            </div>
            <div class="button">
                <input type="submit" name="create" value="Submit">
            </div>
            <div class="button1">     
                <a href="home.php" class="btn btn-warning mt-3"> Back </a>
            </div>
        </form>
    </div>
</div>

<script>
    function addCollateral() {
        const container = document.getElementById('collateral-container');
        const newItem = document.createElement('div');
        newItem.classList.add('collateral-item');
        newItem.innerHTML = `
            <input type="text" name="collateral_type[]" placeholder="Collateral Type" required>
            <input type="file" name="Collateral_Image[]" accept="image/*" required>
        `;
        container.appendChild(newItem);
    }
</script>

</body>
</html>
