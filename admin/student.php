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
    <title>Information student</title>
</head>
<style>
.srch{
   padding-left: 1000px;
}

</style>
<body>
<!-- search bar    -->

<div  class="srch">
    <form   method="post"   class="navbar-form"   name="form1">
    
         <input type="text"  class="form-control"
           name="search"   placeholder="search username.."  required>
         <button type="submit"   name="submit"  
         class="btn btn-default"  style="background-color:red;">
              <span class="glyphicon glyphicon-search" style="font-size:18px;"></span>
         </button>
     
   </form>
</div>


   <h2>List Of Students</h2>

   <?php
    if(isset($_POST['submit'])){
       $q=mysqli_query($db,"SELECT * FROM `student` WHERE 
       username LIKE '%$_POST[search]%' ");
        
        if(mysqli_num_rows($q)==0){
         echo "Sorry! No student found!";
        }
        else{
       
            
     echo "<table  class='table table-bordered  table-hover'>";
     echo "<tr  style='background-color:grey;'>";
        echo "<th>";  echo "Firstname";   echo "</th>";
        echo "<th>";  echo "Lastname";  echo "</th>";
        echo "<th>";  echo "username";  echo "</th>";
        echo "<th>";  echo "Roll";  echo "</th>";
        echo "<th>";  echo "Email";  echo "</th>";
        echo "<th>";  echo "phone";  echo "</th>";
       
      echo "</tr>";
      while($row=mysqli_fetch_assoc($q))
        {
            echo "<tr>";
                  
                    echo "<td>";  echo $row['first'];  echo "</td>";
                    echo "<td>";  echo $row['last'];  echo "</td>";
                    echo "<td>";  echo $row['username'];  echo "</td>";
                    echo "<td>";  echo $row['password'];  echo "</td>";
                    echo "<td>";  echo $row['email'];  echo "</td>";
                    echo "<td>";  echo $row['contact'];  echo "</td>";
            echo "</tr>";
        }
        echo "</table>";



        }//end else
       }
      else{
         $res=mysqli_query($db,"SELECT * FROM `student`;");
     
     echo "<table  class='table table-bordered  table-hover'>";
     echo "<tr  style='background-color:red;'>";
         echo "<th>";  echo "Firstname";   echo "</th>";
        echo "<th>";  echo "Lastname";  echo "</th>";
        echo "<th>";  echo "username";  echo "</th>";
        echo "<th>";  echo "Roll";  echo "</th>";
        echo "<th>";  echo "Email";  echo "</th>";
        echo "<th>";  echo "phone";  echo "</th>";
      echo "</tr>";
      while($row=mysqli_fetch_assoc($res))
        {
            echo "<tr>";
                   echo "<td>";  echo $row['first'];  echo "</td>";
                    echo "<td>";  echo $row['last'];  echo "</td>";
                    echo "<td>";  echo $row['username'];  echo "</td>";
                    echo "<td>";  echo $row['password'];  echo "</td>";
                    echo "<td>";  echo $row['email'];  echo "</td>";
                    echo "<td>";  echo $row['contact'];  echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
      }
    
   ?>


</body>
</html>