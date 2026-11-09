<?php

  include 'connection.php';
  include 'navbar.php';

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
    <title>Approve request</title>
</head>
<style>
.srch{
   padding-left: 800px;
}
.form-control{
  width: 300px;
  height: 40px;
  background-color: black;
}
.sidenav {
  height: 100%;
  margin-top: 50px;
  width: 0;
  position: fixed;
  z-index: 1;
  top: 0;
  left: 0;
  background-color: #151212;
  overflow-x: hidden;
  transition: 0.5s;
  padding-top: 60px;
}

.sidenav a {
  padding: 8px 8px 8px 32px;
  text-decoration: none;
  font-size: 25px;
  color: #818181;
  display: block;
  transition: 0.3s;
}

.sidenav a:hover {
  color: #f1f1f1;
}

.sidenav .closebtn {
  position: absolute;
  top: 0;
  right: 25px;
  font-size: 36px;
  margin-left: 50px;
}

#main {
  transition: margin-left .5s;
  padding: 16px;
}

@media screen and (max-height: 450px) {
  .sidenav {padding-top: 15px;}
  .sidenav a {font-size: 18px;}
}
body{
    background-image:url('./images/g1.jpg');
    background-repeat: no-repeat;
   background-size: cover;
}
h2{
    color: white;
}
.container{
    height: 600px;
    background-color: black;
    opacity: .8;
    color:white;
}
.Approve{
    margin-left: 400px;
    margin-top: 40px;
}
</style>
<body>
     <!-- search bar    -->
<div id="mySidenav" class="sidenav">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
  <a href="books.php">Books</a>
  <a href="request.php">Book Request</a>
  <a href="issue_info">Issue Information</a>

</div>

<div id="main">
  
  <span style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776; open</span>
   <div class="container">
    <br>  <h3  style="text-align:center;">Approve Request</h3><br>
      <form  class="Approve"   action=""  method="post">
        <input type="text" class="form-control"  name="approve"  placeholder="Yes or No" required><br><br>
        <input type="text"  class="form-control"   name="issue"   placeholder="Issue Date yyyy-mm-dd" required><br><br>
        <input type="text" class="form-control"  name="return"   placeholder="Return Date yyyy-mm-dd"><br>
        <button class="btn btn-default"  type="submit"  name="submit">Approve</button>
      </form>
  </div>

</div>


<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
if(isset($_POST['submit']))
    {
           mysqli_query($db,"UPDATE `issue_book`
           SET `approve`='$_POST[approve]', `issue`='$_POST[issue]',
           `return`='$_POST[return]' WHERE username='$_SESSION[name]'
           AND bid='$_SESSION[bid]'");
           mysqli_query($db,"UPDATE books SET 
           quantity = quantity-1 WHERE bid='$_SESSION[bid]'; ");
           $res=mysqli_query($db,"SELECT quantity from books WHERE bid='$_SESSION[bid]' ");

           while($row=mysqli_fetch_assoc($res))
            {
                 if($row['quantity']==0){
                     mysqli_query($db,"UPDATE books SET
                      status='not-available' WHERE bid='$_SESSION[bid]' ");
                 }
            }
         



    }
    ?>


<?php

                '<script  type="text/javascript">
                     alert("update is successfull");
                     window.location.href = "request.php";
                </script>';

?>




<script>
function openNav() {
  document.getElementById("mySidenav").style.width = "500px";
  document.getElementById("main").style.marginLeft = "500px";
}

function closeNav() {
  document.getElementById("mySidenav").style.width = "0";
  document.getElementById("main").style.marginLeft= "0";
}
</script>

</body>
</html>