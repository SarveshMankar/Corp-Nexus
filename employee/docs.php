<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Users / Profile - Richtech</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="../assets/img/favicon.png" rel="icon">
  <link href="../assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="../assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="../assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="../assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link rel="stylesheet" href="../assets/css/style.css?v=<?php echo time(); ?>">


</head>
<?php
error_reporting(0);
?>
<body>
	<?php

		session_start();
    	include '../imports/nav-user.php';        

		if(isset($_SESSION['uname']) and $_SESSION['erole']=="emp"){
			$u=$_SESSION['uname'];
            include '../imports/config.php';
            $conn=mysqli_connect($server_name,$username,$password,$database_name);
            $sql_query = "SELECT * from empt where uname='$u'";
            $records = mysqli_query($conn,$sql_query);

			$empid="";
			$ename="";
			$email="";
			$mobile="";
			$dob="";
			$address="";
			$photo="";
			$cv="";
			$bio="";
			$github="";
			$twitter="";
			$linkedin="";
			$insta="";

			while($data = mysqli_fetch_assoc($records)){
				$empid=$data['empid'];
				$ename=$data['ename'];
				$email=$data['email'];
				$mobile=$data['mobile'];
				$dob=$data['dob'];
				$address=$data['eaddress'];
				$photo=$data['pphoto'];
				$cv=$data['cv'];
				$bio=$data['bio'];
				$github=$data['github'];
				$twitter=$data['twitter'];
				$linkedin=$data['linkedin'];
				$insta=$data['insta'];
			}


			if(true){      
	?>

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Project</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item">Users</li>
          <li class="breadcrumb-item active">Project</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <div class="card jumbotron container">
        <br>
            <?php
                $ee=$_SESSION['euid'];
                include '../imports/config.php';

                //SELECT * from clientt where Id in (SELECT pid from workt where empid='$ee' and wstatus='Completed')
                
                echo '<div class="row">';

                echo '<div class="col-md-12">';
                $sql_query = "SELECT * from reportt where accessp='public'";
                $records = mysqli_query($conn,$sql_query);
                echo "<h4>Completed Projects</h4>";
                echo '<table class="table table-hover">';
                    echo '<thead class="thead-dark">';
                        echo '<tr>';
                        echo '<th scope="col">#</th>';
                        echo '<th scope="col">Company Name</th>';
                        echo '<th scope="col">Project Name</th>';
                        echo '<th scope="col">Description</th>';
                        echo '<th scope="col">File</th>';
                        echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';

                $n=1;
                while ($data = mysqli_fetch_assoc($records)){

                    $sql="SELECT * from clientt where Id='$data[pid]'";
                    $records1 = mysqli_query($conn,$sql);
                    $data1 = mysqli_fetch_assoc($records1);
                    $cname=$data1['cname'];
                    $pname=$data1['pname'];

                    $descrip=$data['descrip'];

                    echo '<tr>
                        <th scope="row">'.$n.'</th>
                        <td>'.$cname.'</td>
                        <td>'.$pname.'</td>
                        <td>'.$descrip.'</td>
                        <td>'.'<form action="" method="get"><input type="hidden" name="id" value="'.$data['pid'].'"><input type="submit" name="download" value="Download"></form>'.'</td>
                        </tr>';
                    $n+=1;
                }
                echo '</tbody>
                    </table>';
                echo '</div>';
                
                echo '</div>';
            ?>
        <br>
    </div>

    <?php
        if(isset($_GET['id'])){
            include '../imports/config.php';
            $id = $_GET['id'];
            //echo $id;
            
            $sql = "SELECT * FROM reportt WHERE pid=$id";
            $result = mysqli_query($conn, $sql);

            $file = mysqli_fetch_assoc($result);
            $filepath = $file['file'];

            header('Content-Type: application/txt');
            header('Content-Disposition: attachment; filename="'.$filepath.'"');
            readfile($filepath);           
            
        }
    ?>

  </main><!-- End #main -->


  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="../assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/vendor/chart.js/chart.min.js"></script>
  <script src="../assets/vendor/echarts/echarts.min.js"></script>
  <script src="../assets/vendor/quill/quill.min.js"></script>
  <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="../assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="../assets/vendor/php-email-form/validate.js"></script>


  <script src="../assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/vendor/chart.js/chart.min.js"></script>
  <script src="../assets/vendor/echarts/echarts.min.js"></script>
  <script src="../assets/vendor/quill/quill.min.js"></script>
  <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="../assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="../assets/vendor/php-email-form/validate.js"></script>
  <script src="../assets/js/main.js"></script>

  <!-- Template Main JS File -->
  <script src="../assets/js/main.js"></script>
	<?php
			}
		}
		else{
			ob_start();
            header('Location: '.'../');
            ob_end_flush();
            die();
		}
	?>
</body>

</html>