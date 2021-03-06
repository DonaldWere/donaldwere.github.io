<?php
session_start();
include_once('../includes/db_connect.php');
if(!isset($_SESSION["admin_login"])){
    header("Location:login.php");
}
else{
    
}
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
<meta charset="UTF-8">
<title>Admin | Home</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
<link rel="stylesheet" href="../css/bootstrap.min.css">
<link rel="stylesheet" href="../css/custom.css">
  <script src="../js/jquery.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>
	<script type="text/javascript">
			function mymodal(){
		// Get the modal
		var modal = document.getElementById("myModal");

		// Get the <span> element that closes the modal
		var span = document.getElementsByClassName("close")[0];

		// When the user clicks on the button, open the modal
			modal.style.display = "block";

		// When the user clicks on <span> (x), close the modal
		span.onclick = function() {
			modal.style.display = "none";
		}

		// When the user clicks anywhere outside of the modal, close it
		window.onclick = function(event) {
			if (event.target == modal) {
				modal.style.display = "none";
			}
		}
		}
</script>
<script type="text/javascript">
function ConfirmDelete(){
    var d = confirm('Do you really want to delete data?');
    if(d == false){
        return false;
    }
}
</script>
</head>
<body>
<?php include_once("header.php"); ?> 
<div class="container" >
<div style="width:80%">
<?php
	echo "<h3><u>All Our Crops</u></h3>";
		//get the records from the database
		if ($result = mysqli_query($con, "SELECT * FROM allcrops ORDER BY id")) {
			//display records if there are records to display
			if ($result->num_rows > 0){
				
				$total_results = $result->num_rows;
				//display records in a table
				echo "<p><b>Showing " . $total_results . " Entries </b></p>";
				echo "<table id='myTable' border='1' cellpadding='10' class='table table-responsive'>";
				
				//set table headers
				echo "<tr class='header' ><th>#</th><th>English Name:</th><th>Scientific Name:</th><th>Altitude:</th><th>Harvest Time:</th><th></th><th></th></tr>";
				
				while ($row = mysqli_fetch_assoc($result)){
					
					//set up a row for each record
					echo "<tr class='success'>";
					echo "<td>".$row["id"]. "</td>";
					echo "<td>" .$row["english_name"]. "</td>";
					echo "<td>" .$row["scientific_name"]. "</td>";
					echo "<td>" .$row["altitude"]. "</td>";
					echo "<td>" .$row["harvest_time"]. "</td>";
					echo "<td><a href= ' edit.php?id=".$row["id"]."' ><image src='../images/link/pensil.jpg' height='16px' width='16px'></a></td>";
					echo "<td><a href= ' delete.php?id=".$row["id"]."' onClick='return ConfirmDelete();'><image src='../images/link/trash.png' height='16px' width='16px'></a></td>";
					echo "</tr>";
				}
				echo "</table>";
			} else {
			//if there are no records in the database, display an alert message
			echo "No results to display!";
			}
		}else {
		//show an error if there is an issue with the database query
		echo "Error:" . $con->error;
		}
	
?> 
 <a class="btn btn-info"  onClick="return mymodal();" id="myBtn" class="link-butn">Add New Crop</a> 
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-info"  href="inquiry.php"  id="myBtn" class="link-butn">Check Inquiries</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <a class="btn btn-info"  href="../index.php"  id="myBtn" class="link-butn">My Site</a>
</div>
<?php
				if(isset($_POST['newcrop'])){ 
						
							$e_name=mysqli_real_escape_string($con, $_POST['english_name']);
							$s_name=mysqli_real_escape_string($con, $_POST['scientific_name']);
							$altitude=mysqli_real_escape_string($con, $_POST['altitude']);
							$harvest_time=mysqli_real_escape_string($con, $_POST['harvest_time']);
							$f_method=mysqli_real_escape_string($con, $_POST['farming_method']);
							$diseases=mysqli_real_escape_string($con, $_POST['diseases']);
							
							// check if the crop exists
							$check=mysqli_num_rows(mysqli_query($con, "SELECT * FROM allcrops WHERE scientific_name='$s_name'"));
							if ($check >= 1){
								echo "<div class='row'>";
								echo "<p class='text-danger'>A crop with a similar scientific name already exists. Please confirm and try again.</p>";
								echo "</div>";
							}else{
								
							if(isset($_FILES['croppic'])){
							  $croppic = $_FILES['croppic']['name'];
							  $file_size =$_FILES['croppic']['size'];
							  $file_tmp =$_FILES['croppic']['tmp_name'];
							  $file_type=$_FILES['croppic']['type'];
							  $extension=end(explode(".", $croppic));
							  $newcroppic=$s_name.".".$extension;
							  move_uploaded_file($file_tmp,"../images/crops/".$newcroppic);
						   }
							
							$insert=mysqli_query($con,"INSERT INTO allcrops (english_name,scientific_name,altitude,harvest_time,farming_method,diseases,pic) VALUES('$e_name','$s_name','$altitude','$harvest_time','$f_method','$diseases','$newcroppic')");
						   if($insert){
						   
						    echo "<div class='row' >";
						   echo "<p class='text-success'> $e_name was succefully added to the list of crops.</p>";
						   echo "</div>";
						   }
						   else{
						   echo "<div class='row'>";
						   echo "<p class='text-danger'>We experienced some problem adding the crop. Please try again later.</p>";
						   echo "</div>";
						   } 
						}
					}
                ?>
		<!-- The Modal -->
	<div id="myModal" class="modal">

			  <!-- Modal content -->
			  <div class="modal-content">
					<div class="modal-header">
						<span class="close">&times;</span>
						<h2>Enter Crop Details Below</h2>
					 </div>
					 <div class="modal-body">

					<form action="index.php" method="post" enctype="multipart/form-data">
						<div class="form-group">
							<input type="text" name="english_name" class="form-control" placeholder="Enter English Name" required /> <br/>
						</div>
						<div class="form-group">
							<input type="text" name="scientific_name" class="form-control" placeholder="Enter Scientific Name" required /> <br/>
						</div>
						<div class="form-group">
							<input type="text" name="altitude" class="form-control" placeholder="Enter Favourable Altitude in meters a. s. l." required /> <br/>
						</div>
						<div class="form-group">
							<input type="text" name="harvest_time" class="form-control" placeholder="Enter Time to harvest in Months" required /> <br/>
						</div>
						<div class="form-group">
							 <label for="farming_method">Describe the farming method:</label>
							 <textarea class="form-control" rows="5" name="farming_method" required></textarea>
						 </div>
						 <div class="form-group">
							 <label for="diseases">Outline the diseases affecting the crop:</label>
							 <textarea class="form-control" rows="5" name="diseases" required></textarea>
						 </div>
						 <div class="form-group">
								 <label for="croppic">Upload a Picture for the Crop:</label>
								 <input type="file" id="croppic" name="croppic"  accept="image/gif, image/jpeg, image/png">
						</div>
						<button type="submit" name= "newcrop"class="btn btn-primary">Submit</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type="reset" class="btn btn-primary">Cancel</button>
					</form>
		
					 </div>
					 <div class="modal-footer">
						<h2>Thanks</h2>
					 </div>
			</div>

	</div>
</div>
<?php include_once("../includes/footer.php"); ?>
</body>
</html>