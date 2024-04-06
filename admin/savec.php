<?php

    session_start();
    if ($_SESSION['erole']=="admin"){
        if (isset($_GET['data'])){
            include '../imports/config.php';
            $formData = json_decode($_GET['data'], true);

            $cname=$formData['cname'];
            $pname=$formData['pname'];
            $mobile=$formData['mobile'];
            $email=$formData['email'];
            $pdate=$formData['pdate'];
            $ddate=$formData['ddate'];
            $descrip=$formData['descrip'];
            $status="Pending";
            $newDate = date("Y-m-d", strtotime($pdate));
            $pdate=$newDate;
            $tags=$formData['tags'];

            echo "<pre>";
            print_r($formData);
            echo "</pre>";

            echo $tags;

            if ($ddate!=""){
                $newDate = date("Y-m-d", strtotime($ddate));
                $ddate=$newDate;
                $sql_query = "INSERT into clientt (cname,pname,mobile,email,pdate,ddate,descrip,tags,pstatus) VALUES 
                            ('$cname','$pname','$mobile','$email','$pdate','$ddate','$descrip','$tags','$status')";
            }else{
                $ddate="";
                $sql_query = "INSERT into clientt (cname,pname,mobile,email,pdate,descrip,tags,pstatus) VALUES 
                            ('$cname','$pname','$mobile','$email','$pdate','$descrip','$tags','$status')";
            }
            
            $conn=mysqli_connect($server_name,$username,$password,$database_name);
            
            mysqli_query($conn,$sql_query);

            $nmsg = "New Client $cname Added! Project: $pname";
            $sql_query = "INSERT into notift (euid,ttype,nmsg,ddate) VALUES ('admin','Client','$nmsg','$pdate')";
            
            if(mysqli_query($conn,$sql_query)){
                echo "New record created successfully";
                // header('Location: '.'view_cli.php');
            }
            else{
                echo "Error: ".$sql_query."<br>".mysqli_error($conn);
            }

            mysqli_close($conn);
        }
    }

?>


<!-- Array
(
    [query] => 
    [cname] => ADS
    [pname] => ADS
    [mobile] => 7845129630
    [email] => ADS@gmail.com
    [pdate] => 2024-04-03
    [ddate] => 2024-04-26
    [tags] => react, react-router-dom, axios, styled-components, node, express, mongoose, bcryptjs, jsonwebtoken, nginx, gunicorn, docker, docker-compose, mongodb, nestjs, grpc, aws, google-cloud
    [descrip] => Developing a comprehensive booking platform for travel agencies with real-time availability and secure transactions.
) -->