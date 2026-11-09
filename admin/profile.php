<?php

include "connection.php";
include "navbar.php";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
     <link rel="stylesheet"  href="./css/styles.css">
    <title>Profile admin</title>
        <style>
              .wrapper{
                  width: 500px;
                  margin: 0 auto;
                  color: #fff;

              }
        </style>
</head>
<body  style="background-color:green;">
    
<div class="container">
  <form   method="post"  action="">
     <button class="btn btn-default"  style="float:right;width:70px;"  name="submit1">
           Edit
     </button>

  </form>
  <div class="wrapper">
    <?php
    $q=mysqli_query($db,"SELECT * FROM `admin`
     WHERE username='{$_SESSION['login_user']}' "); 

     ?>
     <h2 style="text-align: center;">My Profile</h2>
     <?php
       $row=mysqli_fetch_assoc($q);
       
     ?>
     <div  style="text-align: center;">
        <b>Welcome,</b>
    
    <h4>
        <?php echo $_SESSION['login_user'];  ?>
    </h4>
    </div>
    <?php
           echo "<table   class='table table-bordered'>";
              echo "<tr>";
                  echo "<td>";
                     echo "<b>First Name: </b>";
                  echo "</td>";
                 echo "<td>";
                    echo $row['first'];
                  echo "</td>";
              echo "</tr>";  
                
              echo "<tr>";
                  echo "<td>";
                     echo "<b>Last Name: </b>";
                  echo "</td>";
                 echo "<td>";
                    echo $row['last'];
                  echo "</td>";
              echo "</tr>";  

              echo "<tr>";
                  echo "<td>";
                     echo "<b>UserName: </b>";
                  echo "</td>";
                 echo "<td>";
                    echo $row['username'];
                  echo "</td>";
              echo "</tr>";  
              
               echo "<tr>";
                  echo "<td>";
                     echo "<b>Password: </b>";
                  echo "</td>";
                 echo "<td>";
                    echo $row['password'];
                  echo "</td>";
              echo "</tr>";  

               echo "<tr>";
                  echo "<td>";
                     echo "<b>Email: </b>";
                  echo "</td>";
                 echo "<td>";
                    echo $row['email'];
                  echo "</td>";
              echo "</tr>";  


               echo "<tr>";
                  echo "<td>";
                     echo "<b>Contact: </b>";
                  echo "</td>";
                 echo "<td>";
                    echo $row['contact'];
                  echo "</td>";
              echo "</tr>";  

             
              

           echo "</table>";
    ?>
  </div>
</div>


</body>
</html>