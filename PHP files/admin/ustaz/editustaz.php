<?php
session_start();

$error = "";

include "../connection.php";

$uid = $_GET['uid'];

$query = "SELECT * FROM ustaz WHERE uid = '$uid'";
$display = mysqli_query($connection, $query);
$data = mysqli_fetch_array($display);

if(isset($_POST['Update']))
{

	$firstname = $_POST["firstname"];
	$lastname = $_POST["lastname"];
	$profilename = $_POST["profilename"];
	$icno = $_POST["icno"];
	$dob = $_POST["dob"];
	$place = $_POST["place"];
	$contact = $_POST["contact"];
	$email = $_POST["email"];
	$password = base64_encode($_POST["password"]);
	$photo = $_FILES["photo"]["name"];
	
	$currentphoto = $data['uphoto'];

	if($firstname =='' or $lastname =='' or $profilename =='' or $icno =='' or $email =='' or $password =='' or $place =='')
	{
		$error = "Please complete all the field";
	}
	

	else
	{
		if(!empty($photo))
		{
		
			$sql = mysqli_query($connection,"UPDATE ustaz SET ufirstname = '$firstname', ulastname = '$lastname' , uprofilename = '$profilename' , 
			uicno = '$icno', udob = '$dob', uplace = '$place', ucontact ='$contact', uphoto = '$photo' , uemail = '$email' , upassword = '$password' 
			WHERE uid = '$uid'");
					
			if($sql)
			{
				$target_dir = "../../ustaz/photo";
				$target_file = $target_dir . basename($_FILES["photo"]["name"]);
				$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
					
				if(move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file))
				{
					echo ("<SCRIPT LANGUAGE='JavaScript'>
					window.alert('Ustaz profile information has been updated')
					window.location.href='detailsustaz.php?uid=".$uid."';
					</SCRIPT>");
				}
			}
			else
			{
					echo ("<SCRIPT LANGUAGE='JavaScript'>
					window.alert('Failed to insert');
					</SCRIPT>");
			}	
		}
		
		if(empty($photo))
		{
		
			$sql = mysqli_query($connection,"UPDATE ustaz SET ufirstname = '$firstname', ulastname = '$lastname' , uprofilename = '$profilename' , 
			uicno = '$icno', udob = '$dob', uplace = '$place', ucontact ='$contact', uphoto = '$currentphoto' , uemail = '$email' , upassword = '$password' 
			WHERE uid = '$uid'");
					
			if($sql)
			{
				
					echo ("<SCRIPT LANGUAGE='JavaScript'>
					window.alert('Ustaz profile information has been updated')
					window.location.href='detailsustaz.php?uid=".$uid."';
					</SCRIPT>");
			}
			else
			{
					echo ("<SCRIPT LANGUAGE='JavaScript'>
					window.alert('Failed to insert');
					</SCRIPT>");
			}	
		}
	}
}
	

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Admin || Display Society</title>
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
     <link href="../../css/style.css" rel="stylesheet">
    
  </head>

  <body>

    <nav class="navbar navbar-default">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand">FMU Management System</a>
        </div>
          <ul class="nav navbar-nav navbar-right">
            <li><a>Welcome, <?php echo $_SESSION['username']; ?></a></li>
            <li><a href="../logout.php">Logout</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <header id="header">
     <div class="container">
     <div class="row">
     <div class="col-md-10">
     <h2><a class="href" href="../dashboard.php"><span class="glyphicon glyphicon-home" aria-hidden="true"></span>   Admin Control Panel</a></h2>
     </div>
     <div class="col-md-2">
     <div class="dropdown create">
          <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" 
          aria-haspopup="true" aria-expanded="true">
            Options
            <span class="caret"></span>
          </button>
          <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
            <li><a href="../viewadminprofile.php?username=<?php echo $_SESSION['username']; ?>">View Profile</a></li>
            <li><a href="../register.php">Register Admin</a></li>
            <li><a href="../adminlist.php">View Admin</a></li>
            <li><a href="../logout.php">Logout</a></li>
          </ul>
        </div>
     </div>
    
     </div>
    </header>
  
    <?php
  
  	$sql1 = "SELECT count(*) FROM ustaz";
	$result1 = mysqli_query($connection, $sql1);
	$row1 = mysqli_fetch_array($result1);
	$num1 = $row1[0];
	
	$sql2 = "SELECT count(*) FROM society";
	$result2 = mysqli_query($connection, $sql2);
	$row2 = mysqli_fetch_array($result2);
	$num2 = $row2[0];
	
	$sql3 = "SELECT count(*) FROM tmpustaz WHERE ustatus = 0";
	$result3 = mysqli_query($connection, $sql3);
	$row3 = mysqli_fetch_array($result3);
	$num3 = $row3[0];
  
  	$sql4 = "SELECT count(*) FROM orderdetails WHERE paystatus = 1 AND orderstatus = 0";
	$result4 = mysqli_query($connection, $sql4);
	$row4 = mysqli_fetch_array($result4);
	$num4 = $row4[0];
  
  ?>
  
  <section id="main">
      <div class="container">
          <div class="row">
          	<div class="col-md-3">
            <div class="list-group">
              <a href="../dashboard.php" class="list-group-item active main-color-bg">
                Dashboard
              </a>
              <a href="displayustaz.php" class="list-group-item"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>   Ustaz<span class="badge"><?php echo $num1; ?></a>
              <a href="../society/displaysociety.php" class="list-group-item"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>   User<span class="badge">  <?php echo $num2; ?></a>
              <a href="../waitingustaz/displaywaitingustaz.php" class="list-group-item"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>   Waiting Ustaz<span class="badge">  <?php echo $num3; ?></a>
              <a href="../order/displayorder.php" class="list-group-item"><span class="glyphicon glyphicon-tags" aria-hidden="true"></span>   Customer Order<span class="badge">  <?php echo $num4; ?></a>
              <a href="../transaction/displaytransaction.php" class="list-group-item"><span class="glyphicon glyphicon-usd" aria-hidden="true"></span>   Transaction History<span class="badge"></a>
            </div>
            
            <div class="well">
            <h4>Bandwidth Used</h4>
            <div class="progress">
              <div class="progress-bar" role="progressbar" aria-valuenow="12" aria-valuemin="0" aria-valuemax="100" 
              style="width: 60%;">69%
			</div>
            </div>
            <h4>Disk Space Used</h4>
            <div class="progress">
              <div class="progress-bar" role="progressbar" aria-valuenow="69" aria-valuemin="0" aria-valuemax="100" 
              style="width: 40%;">12%
			</div>
            </div>
            </div>
            </div>
            
            <!--Website Overview-->
            
            <div class="col-md-9">
            	<div class="panel panel-default">
                  <div class="panel-heading main-color-bg">
                    <h3 class="panel-title">Update Ustaz</h3>
                  </div>
    <form name="form1"  method="post" enctype="multipart/form-data">              
    <table width="413" border="0" align="center">
    <tr>
      <td colspan="3"><div id="error-message"><center><?php echo $error; ?></center></div>
        <p>
        <p>        
        <center> 
        <p><?php echo '<img src="../photo/'.$data["uphoto"].'" width="150px" height="150px"/>'; ?>
        </p>
        <p>&nbsp;</p>
      </center></p></td>
    </tr>
    <tr>
      <td width="110" height="32">First name</td>
      <td width="8">:</td>
      <td width="281"><input type="text" name="firstname" value="<?php echo $data ['ufirstname']; ?>"/></td>
    </tr>
    <tr>
      <td height="30">Last name</td>
      <td>:</td>
      <td><input type="text" name="lastname"  value="<?php echo $data ['ulastname']; ?>"/></td>
    </tr>
    <tr>
      <td height="30">Profile name</td>
      <td>:</td>
      <td><input type="text" name="profilename"  value="<?php echo $data ['uprofilename']; ?>"/></td>
    </tr>
    <tr>
      <td height="30">IC No</td>
      <td>:</td>
      <td><input type="text" name="icno"  value="<?php echo $data ['uicno']; ?>"/></td>
    </tr>
    <tr>
      <td height="30">Date of Birth</td>
      <td>:</td>
      <td><input type="text" name="dob" value="<?php echo $data ['udob']; ?>"/></td>
    </tr>
    <tr>
      <td height="30">Place</td>
      <td>:</td>
      <td><input type="text" name="place" value="<?php echo $data ['uplace']; ?>"/></td>
    </tr>
    <tr>
      <td height="30">Contact</td>
      <td>:</td>
      <td><input type="text" name="contact" value="<?php echo $data ['ucontact']; ?>" /></td>
    </tr>
    <tr>
      <td height="30">Email</td>
      <td>:</td>
      <td><input type="text" name="email" value="<?php echo $data ['uemail']; ?>" /></td>
    </tr>
    <tr>
      <td height="30">Password</td>
      <td>:</td>
      <td><input type="password" name="password" value="<?php echo base64_decode($data['upassword']);?>"/></td>
    </tr>
    <tr>
      <td height="30">Photo</td>
      <td>:</td>
      <td>  <input type="file" name="photo" value="<?php echo $data ['uphoto']; ?>"/> </td>
    </tr>
    <tr>
      <td colspan="3"><p>&nbsp;
        </p>
        <p> 
          <input type="submit" name="Update" value="Update" />
          <input type="reset" name="reset" value="Reset" />
        </p></td>
    </tr>
  </table>
  </form>
                </div>    
             
            </div>
          </div>
      </div>
  </section>
  
  <!--Footer-->
  <footer id="footer">
  <p>Copyright <italic>FindMyUstaz</italic>, &copy; 2017</p>
  </footer>
  

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
  </body>
</html>
