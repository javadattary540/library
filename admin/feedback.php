
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
    <style>
         body{
            background-image: url("./images/fe.jpg");
            background-size: cover;
            background-position: center;
         }
         .wrapper{
            width:900px;
            height:auto;
            background-color:black;
            opacity: .8;
            color:white;
            padding: 10px;
            margin: -20px auto;
         }
         .form-control{
            height: 70px;
            width: 60%;
         }
        
    </style>
    <title>Feedback</title>
</head>
<body>
 <div class="wrapper">   
 <h3>If you any suggessions please comment</h3>
 <form   method="post"    action="" style="">
    <input  class="form-control" type="text"   name="comment"  placeholder="Write something..."><br>
    <input  class="btn btn-default" type="submit"   name="submit"
      value="Comment"  style="width:100px;height:40px;">  
</form>

<br><br>

<div>
   <?php
     if(isset($_POST['submit'])){
      $sql="INSERT INTO `comments` (`comment`) VALUES('$_POST[comment]')";
      if(mysqli_query($db,$sql))
        {
         $q="SELECT * FROM `comments` ORDER BY `comments`.`id` DESC";
         $res=mysqli_query($db,$q);
         
         echo "<table class='table table-bordered'>";
         while($row=mysqli_fetch_assoc($res)){
             echo "<tr>";
                echo "<td style='color:white;'>";  echo $row['comment'];  echo "</td>";
             echo "</tr>";
         }

        }
        else{

           $q="SELECT * FROM `comments` ORDER BY `comments`.`id` DESC";
         $res=mysqli_query($db,$q);
         echo "<table class='table table-bordered'>";
         while($row=mysqli_fetch_assoc($res)){
             echo "<tr>";
                echo "<td>";  echo $row['comment'];  echo "</td>";
             echo "</tr>";
         }


        }
     }


   ?>
</div>



</div>
</body>
</html>