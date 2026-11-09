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
    <title>Book request</title>
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
</div>
<div class="container">
<div class="srch">
   <form  method="post"  action=""   name="form1">
      <input type="text"   name="username"  class="form-control"  placeholder="Username" required><br>
      <input type="text"   name="bid"  class="form-control"  placeholder="BID"  required><br>
      <button class="btn btn-default"  type="submit"  name="submit">Submit</button>
   </form>
</div>
<h3  style="text-align:center";>Request of Book</h3>

<?php

if(isset($_SESSION['login_user']))
    {
        $sql="SELECT student.username,roll,books.bid,name,authors,edition,status 
        FROM student inner join issue_book ON student.username=issue_book.username
        inner join books ON issue_book.bid=books.bid WHERE issue_book.approve =''";
        $res=mysqli_query($db,$sql);

        if(mysqli_num_rows($res)==0){
         echo "<h2><b>";   
         echo "there is no pending request!";
          echo "</h2></b>";
        }
        else{
                echo "<table  class='table table-bordered  table-hover'>";
                echo "<tr  style='background-color:red;'>";
      
                 echo "<th>";  echo "Username";  echo "</th>";
                 echo "<th>";  echo "Roll No";  echo "</th>";
                echo "<th>";  echo "BID";  echo "</th>";
                echo "<th>";  echo "Book Name";  echo "</th>";
               echo "<th>";  echo "Authors Name";  echo "</th>";
               echo "<th>";  echo "Edition";  echo "</th>";
               echo "<th>";  echo "Status";  echo "</th>";
                echo "</tr>";
               while($row=mysqli_fetch_assoc($res))
                  {
                  echo "<tr>";
                    echo "<td>";  echo $row['username'];  echo "</td>";
                    echo "<td>";  echo $row['roll'];  echo "</td>";
                    echo "<td>";  echo $row['bid'];  echo "</td>";
                    echo "<td>";  echo $row['name'];  echo "</td>";
                      echo "<td>";  echo $row['authors'];  echo "</td>";
                        echo "<td>";  echo $row['edition'];  echo "</td>";
                          echo "<td>";  echo $row['status'];  echo "</td>";
                    echo "</tr>";
                      }
                     echo "</table>";



        }//end else
       }
      else{
         $res=mysqli_query($db,"SELECT * FROM `books`;");
     
     echo "<table  class='table table-bordered  table-hover'>";
     echo "<tr  style='background-color:red;'>";
        echo "<th>";  echo "ID";   echo "</th>";
        echo "<th>";  echo "Books-Name";  echo "</th>";
        echo "<th>";  echo "Authors Name";  echo "</th>";
        echo "<th>";  echo "Edition";  echo "</th>";
        echo "<th>";  echo "Status";  echo "</th>";
        echo "<th>";  echo "Quantity";  echo "</th>";
         echo "<th>";  echo "Department";  echo "</th>";
      echo "</tr>";
      while($row=mysqli_fetch_assoc($res))
        {
            echo "<tr>";
                    echo "<td>";  echo $row['bid'];  echo "</td>";
                    echo "<td>";  echo $row['name'];  echo "</td>";
                    echo "<td>";  echo $row['authors'];  echo "</td>";
                    echo "<td>";  echo $row['edition'];  echo "</td>";
                    echo "<td>";  echo $row['status'];  echo "</td>";
                    echo "<td>";  echo $row['quantity'];  echo "</td>";
                    echo "<td>";  echo $row['department'];  echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
   
        }
    
  if(isset($_POST['submit']))
    {
      $_SESSION['name']=$_POST['username'];
      $_SESSION['bid']=$_POST['bid'];

      ?>
           <script type="text/javascript">
               window.location="approve.php";
            </script>
      <?php

    }
 

?>

</div>


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