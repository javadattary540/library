<?php
  include "connection.php";
  include "navbar.php";
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
    <title>forget password</title>
</head>
<style>
 body{
  
    height: 650px;
    background-color:red;
    background-image: url('./images/pass.jpg');
    background-size: cover;
 }
 .wrapper{
    width:400px;
    height:370px;
    margin:0 auto;
     background-color: #2f4542;
     opacity: .9;
     color: white;
     padding: 27px 15px;
 }
</style>
<body>
    
<div class="wrapper">
<div style="text-align:center;">
  
   <h1 style="text-align: center;font-size: 35px;padding-bottom:10px;">Change your password</h1>
</div>

<form  method="post"  action="">
   <input type="text"   name="username" class="form-control"   placeholder="Username" required>
   <br>
   <input type="text"  name="email"   class="form-control"  placeholder="Email"  required>
   <br>
   <input type="text"  name="password"   class="form-control"  placeholder="New Password"  required>
   <br>
   <button class="btn btn-default"   type="submit"   name="submit">Update</button>
</form>



<?php
   
  if(isset($_POST['submit']))
   {
       $sql=mysqli_query
       ($db,"UPDATE admin SET password='$_POST[password]'
        WHERE username='$_POST[username]'  AND email='$_POST[email]' ;");
        if($sql){
          header("Location:admin_login.php");
         
         
        }
   }


?>


</div>
</body>
</html>