<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Billing Management</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<style>
body {
	color: #566787;
	background: #f5f5f5;
	font-family: 'Varela Round', sans-serif;
	font-size: 13px;
}
.table-responsive {
    margin: 30px 0;
}
.table-wrapper {
	background: #fff;
	padding: 20px 25px;
	border-radius: 3px;
	min-width: 1000px;
	box-shadow: 0 1px 1px rgba(0,0,0,.05);
}
.table-title {        
	padding-bottom: 15px;
	background: #435d7d;
	color: #fff;
	padding: 16px 30px;
	min-width: 100%;
	margin: -20px -25px 10px;
	border-radius: 3px 3px 0 0;
}
.table-title h2 {
	margin: 5px 0 0;
	font-size: 24px;
}
.table-title .btn-group {
	float: right;
}
.table-title .btn {
	color: #fff;
	float: right;
	font-size: 13px;
	border: none;
	min-width: 50px;
	border-radius: 2px;
	border: none;
	outline: none !important;
	margin-left: 10px;
}
.table-title .btn i {
	float: left;
	font-size: 21px;
	margin-right: 5px;
}
.table-title .btn span {
	float: left;
	margin-top: 2px;
}
table.table tr th, table.table tr td {
	border-color: #e9e9e9;
	padding: 12px 15px;
	vertical-align: middle;
}
table.table tr th:first-child {
	width: 60px;
}
table.table tr th:last-child {
	width: 100px;
}
table.table-striped tbody tr:nth-of-type(odd) {
	background-color: #fcfcfc;
}
table.table-striped.table-hover tbody tr:hover {
	background: #f5f5f5;
}
table.table th i {
	font-size: 13px;
	margin: 0 5px;
	cursor: pointer;
}	
table.table td:last-child i {
	opacity: 0.9;
	font-size: 22px;
	margin: 0 5px;
}
table.table td a {
	font-weight: bold;
	color: #566787;
	display: inline-block;
	text-decoration: none;
	outline: none !important;
}
table.table td a:hover {
	color: #2196F3;
}
table.table td a.edit {
	color: #FFC107;
}
table.table td a.delete {
	color: #F44336;
}
table.table td i {
	font-size: 19px;
}
table.table .avatar {
	border-radius: 50%;
	vertical-align: middle;
	margin-right: 10px;
}
.pagination {
	float: right;
	margin: 0 0 5px;
}
.pagination li a {
	border: none;
	font-size: 13px;
	min-width: 30px;
	min-height: 30px;
	color: #999;
	margin: 0 2px;
	line-height: 30px;
	border-radius: 2px !important;
	text-align: center;
	padding: 0 6px;
}
.pagination li a:hover {
	color: #666;
}	
.pagination li.active a, .pagination li.active a.page-link {
	background: #03A9F4;
}
.pagination li.active a:hover {        
	background: #0397d6;
}
.pagination li.disabled i {
	color: #ccc;
}
.pagination li i {
	font-size: 16px;
	padding-top: 6px
}
.hint-text {
	float: left;
	margin-top: 10px;
	font-size: 13px;
}    

/* Modal styles */
.modal .modal-dialog {
	max-width: 400px;
}
.modal .modal-header, .modal .modal-body, .modal .modal-footer {
	padding: 20px 30px;
}
.modal .modal-content {
	border-radius: 3px;
	font-size: 14px;
}
.modal .modal-footer {
	background: #ecf0f1;
	border-radius: 0 0 3px 3px;
}
.modal .modal-title {
	display: inline-block;
}
.modal .form-control {
	border-radius: 2px;
	box-shadow: none;
	border-color: #dddddd;
}
.modal textarea.form-control {
	resize: vertical;
}
.modal .btn {
	border-radius: 2px;
	min-width: 100px;
}	
.modal form label {
	font-weight: normal;
}	
</style>

</head>

<body>
<?php
include "../db.php"; // Ensure the correct path to your db.php file

$search = isset($_POST['search']) ? mysqli_real_escape_string($conn, $_POST['search']) : "";

if (!empty($search)) {
    // SQL query to fetch filtered data based on the search input
    $query = "SELECT * FROM billing WHERE Patient_Name LIKE '%$search%'";
} else {
    // Default SQL query to fetch all table data if search input is not provided
    $query = "SELECT * FROM billing";
}

$view_billing = mysqli_query($conn, $query);

// Check for database query errors
if (!$view_billing) {
    echo "Error executing query: " . mysqli_error($conn);
    exit; // Exit the script if there's an error
}

// Fetch the notification count from the database
$currentDate = date('Y-m-d');
$query_notification = "SELECT COUNT(*) AS unread_count FROM billing WHERE notification_read = 0 AND Due_Date <= '$currentDate'";

$result_notification = mysqli_query($conn, $query_notification);

// Check if the query was successful
if ($result_notification) {
    $row_notification = mysqli_fetch_assoc($result_notification);
    // Get the unread notification count
    $notification_count = $row_notification['unread_count'];
} else {
    // Handle the case where the query failed
    echo "Error executing query: " . mysqli_error($conn);
    $notification_count = 0; // Set notification count to 0 in case of error
}
?>




<!-- Your HTML code goes here -->

<!-- Your existing HTML code -->

<div class="container-xl">
    <div class="table-responsive">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row align-items-center">
				<div class="col-sm-6">
    <div class="d-flex align-items-center justify-content-between">
        <h2 class="mb-0">Manage <b>Billing</b></h2>
    </div>
</div>

                    <div class="col-sm-6">
                        <div class="d-flex justify-content-end align-items-center">
                            <a href='notification.php?notification=<?php echo $notification_count; ?>&notification_clicked=true' class="mr-2">
                                <i class='material-icons' data-toggle='tooltip' title='Notification'>notifications</i>
                                <!-- Display the badge with the count of unread notifications -->
                                <span class="badge badge-pill badge-danger"><?php echo $notification_count; ?></span>
                            </a>
                            <!-- Add the button for the list of paid patients -->
                            <a href="paid_patient.php" class="btn btn-info btn-sm mr-2">
                                <i class="material-icons">list</i>
                                <span>List of Paid Patients</span>
                            </a>
                            <a href="add.php" class="btn btn-success btn-sm">
                                <i class="material-icons">&#xE147;</i>
                                <span>Add New Patient</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Rest of your HTML code -->

			<table class="table table-striped table-hover">
				<thead>
					<tr>
                        <th></th>
						<th>Date <br> (YEAR/MM/DD)</th>
						<th>Patient Name</th>
                        <th>Name of Guarantor</th>
						<th>Address</th>
						<th>Contact No.</th>
                        <th>Amount</th>
                        <th>Due Date <br> (YEAR/MM/DD)</th>
                        <th>Collateral Given</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
		
    <?php
        // Displaying all the data retrieved from the database using a while loop
        while ($row = mysqli_fetch_assoc($view_billing)) {
            $id = $row['id'];                
            $date = $row['Date'];        
            $patient = $row['Patient_Name'];         
            $guarantor = $row['Name_Gaurantor'];         
            $address = $row['Address'];        
            $contact = $row['Contact'];        
            $amount = $row['Amount'];        
            $due = $row['Due_Date'];        
            $collateral = $row['Collateral_Given'];        

            echo "<tr>";
           
            echo " <th scope='row'></th>";
            echo " <td>{$date}</td>";
            echo " <td>{$patient}</td>"; 
            echo " <td>{$guarantor}</td>"; 
            echo " <td>{$address}</td>";
            echo " <td>{$contact}</td>";
            echo " <td>{$amount}</td>";
            echo " <td>{$due}</td>";
            echo " <td>{$collateral}</td>";
            
			echo "<td class='text-center'>   
        <a href='view.php?user_id={$id}' class='View' data-toggle='tooltip' title='View'>
            <i class='material-icons'>&#xe8f4;</i>
        </a>
      </td>";
echo "<td class='text-center'> 
        <a href='edit.php?edit&user_id={$id}'><i class='material-icons' data-toggle='tooltip' title='Edit'>&#xE254;</i></a>   
      </td>";
echo "<td class='text-center'> 
        <a href='delete.php?delete={$id}'><i class='material-icons' data-toggle='tooltip' title='Delete'>&#xE872;</i></a>   
      </td>";
echo "</tr>";

        }  
    ?>
</td>

				 
				</tbody>
			</table>

		</div>
	</div>        
</div>


<!-- Place this script in your HTML file before the closing </body> tag -->
<!-- Place this script in your HTML file before the closing </body> tag -->
<!-- Your existing HTML code -->

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
$(document).ready(function() {
    // Function to update notification count using long polling
    function updateNotificationCount() {
        $.ajax({
            url: 'notification.php', // Replace 'check_notifications.php' with the appropriate URL to fetch the notification count
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.has_notifications) {
                    // Update the notification count in the badge only if the notification is not being viewed
                    if (!response.notification_clicked) {
                        $('.badge-danger').text(response.notification_count);
                    } else {
                        // Hide the badge when the notification icon is clicked
                        $('.badge-danger').hide();
                    }
                } else {
                    // If there are no notifications, reset the count to zero and display the badge
                    $('.badge-danger').text('0').show();
                }
                // Call the function again after a short delay
                setTimeout(updateNotificationCount, 5000); // 5 seconds delay before making the next request
            },
            error: function(xhr, status, error) {
                console.error('Error checking notifications:', error);
                // Retry after a short delay in case of an error
                setTimeout(updateNotificationCount, 5000); // 5 seconds delay before making the next request
            }
        });
    }

    // Call the updateNotificationCount function initially
    updateNotificationCount();

    // Reload the page after 1 minute (60000 milliseconds)
    setTimeout(function() {
        location.reload();
    }, 60000); // 60000 milliseconds = 1 minute
});
</script>

</body>
</html>
