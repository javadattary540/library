<?php
   include "connection.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet"  href="./css/styles.css">

       <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <title>Admin Registreration</title>

     <style>
      nav{
        float:right;
        word-spacing: 30px;
        padding:20px;
      }
      nav li{
        display: inline-block;
        line-height: 80px;
      }
       section{
         margin-top:20px;
       }
    </style>
</head>
<body>
  <?php
     include('navbar.php');
  ?>
<br><br><br>
<section>
  <div class="reg_img">
      <br>
      <div class="box1">
          <h1 style="text-align:center;font-size:25px;color:#fff;">
             User Regitration Form
          </h1>
         <form   name="registration"  action=""  method="post">
            
             <div class="login">
                   <input class="form-control" type="text"   name="first"  placeholder="FirstName" required><br>
                   <input class="form-control" type="text"   name="last"  placeholder="LastName" required><br>
                   <input class="form-control" type="text"   name="username"  placeholder="Username" required><br>
                   <input class="form-control" type="password"  name="password"  placeholder="Password"  required><br>
                   
                   <input class="form-control" type="text" name="email"  placeholder="Email" required><br>
                    <input class="form-control" type="text" name="contact"  placeholder="Phone No" required><br>
                   <input type="submit"  class="btn btn-success"  name="submit" value="Sign Up"
                   style="width:100%;height:30px;box-shadow:0px 0px 10px blue;">
             </div>
         </form>
         
      </div>
  </div> 
</section>

<?php

  if(isset($_POST['submit'])){

    $count=0;
    $sql="SELECT username FROM admin";
    $res=mysqli_query($db,$sql);

    while($row=mysqli_fetch_assoc($res))
      {
        if($row['username']==$_POST['username']){
           $count=$count+1;
        }
      }
    
       if($count == 0){

      
    $result=mysqli_query($db,"INSERT INTO `admin`(`first`, `last`, `username`,
     `password`,`email`, `contact`,`pic`) VALUES
     ('{$_POST['first']}','{$_POST['last']}','{$_POST['username']}',
     '{$_POST['password']}','{$_POST['email']}','{$_POST['contact']}')");

     if($result){
        header("Location: admin_login.php");
        exit();
     }else{
       echo "error:" . mysqli_error($db);
     }
      

      }
  }
  

?>


</body>
</html>