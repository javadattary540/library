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
    <title>فراموشی رمزعبور دانش اموز</title>
</head>
<style>
 body{
  
    height: 650px;
    background-color: grey;
    background-image: url('./images/pass.jpg');
    background-size: cover;
 }
 /* ================= دکمهٔ Update تمام‌عرض و سه‌بعدی (نارنجی) ================= */
 .wrapper .update-btn{
    display: block;
    width: 100%;
    height: 46px;
    margin-top: 6px;
    border: 0;
    border-radius: 10px;
    font-size: 16px;
    font-weight: bold;
    letter-spacing: .5px;
    color: #ffffff;
    background: linear-gradient(#ffb257, #f97316);
    text-shadow: 0 1px 1px rgba(0, 0, 0, .3);
    box-shadow: 0 7px 0 #b45309,
                0 12px 22px rgba(0, 0, 0, .45),
                inset 0 1px 0 rgba(255, 255, 255, .6);
    transition: transform .15s ease, box-shadow .15s ease, background .2s ease;
 }
 .wrapper .update-btn:hover,
 .wrapper .update-btn:focus{
    color: #ffffff;
    background: linear-gradient(#ffc078, #fb923c);
    outline: none;
 }
 .wrapper .update-btn:active{
    transform: translateY(6px);
    box-shadow: 0 1px 0 #b45309,
                0 3px 8px rgba(0, 0, 0, .35);
 }

 /* چپ‌چین کردن متن و placeholderها */
 .wrapper .form-control{
    direction: ltr;
    text-align: left;
 }
 .wrapper .form-control::placeholder{
    text-align: left;
    opacity: .8;
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
  
   <h1 style="text-align: center;font-size: 35px;padding-bottom:10px;">پسوردتان را تغییر دهید</h1>
</div>

<form  method="post"  action="">
   <input type="text"   name="username" class="form-control"   placeholder="Username" required>
   <br>
   <input type="text"  name="email"   class="form-control"  placeholder="Email"  required>
   <br>
   <input type="text"  name="password"   class="form-control"  placeholder="New Password"  required>
   <br>
   <button class="btn btn-default update-btn" type="submit" name="submit">Update</button>
</form>



<?php
   
  if(isset($_POST['submit']))
   {
       $sql=mysqli_query
       ($db,"UPDATE student SET password='$_POST[password]'
        WHERE username='$_POST[username]'  AND email='$_POST[email]' ;");
        if($sql){
          header("Location:student_login.php");
         
         
        }
   }


?>


</div>
</body>
</html>