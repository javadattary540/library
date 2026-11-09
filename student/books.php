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
    <title>books</title>
</head>
<style>
/* ================= پنل جستجو ================= */
.search-panel{
  display: flex;
  flex-wrap: wrap;
  gap: 18px;
  margin: 18px;
  padding: 18px;
  background: #f5f8fa;
  border: 1px solid #e4eaf0;
  border-radius: 16px;
  box-shadow: 0 8px 24px rgba(0, 0, 0, .07);
}
.search-card{
  flex: 1 1 320px;
  background: #ffffff;
  border-radius: 12px;
  padding: 14px 16px 16px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, .06);
}
.search-label{
  display: block;
  margin: 0 0 9px;
  font-size: 14px;
  font-weight: bold;
  color: #33404d;
}
.search-label .glyphicon{
  color: #19b39a;
  margin-left: 6px;
  font-size: 13px;
}
.search-row{
  display: flex;
  gap: 10px;
}
.search-row .form-control{
  flex: 1;
  height: 42px;
  border-radius: 10px;
  border: 1px solid #dbe3ea;
  box-shadow: none;
  transition: border-color .2s, box-shadow .2s;
}
.search-row .form-control:focus{
  border-color: #3be3c4;
  box-shadow: 0 0 0 3px rgba(59, 227, 196, .18);
}
.search-btn{
  height: 42px;
  min-width: 54px;
  padding: 0 20px;
  border: 0;
  border-radius: 10px;
  color: #ffffff;
  font-weight: bold;
  font-size: 14px;
  background: linear-gradient(#3b8371, #256155);
  box-shadow: 0 4px 0 #163a32, 0 8px 16px rgba(0, 0, 0, .18);
  transition: transform .12s, box-shadow .12s, background .2s;
}
.search-btn:hover,
.search-btn:focus{
  color: #ffffff;
  background: linear-gradient(#48a08b, #2c7263);
  outline: none;
}
.search-btn:active{
  transform: translateY(3px);
  box-shadow: 0 1px 0 #163a32, 0 3px 8px rgba(0, 0, 0, .2);
}
.search-btn.request{
  background: linear-gradient(#ff9448, #ee6c1f);
  box-shadow: 0 4px 0 #a94b12, 0 8px 16px rgba(0, 0, 0, .18);
}
.search-btn.request:hover,
.search-btn.request:focus{
  background: linear-gradient(#ffa968, #fb7d2e);
}
.search-btn.request:active{
  box-shadow: 0 1px 0 #a94b12, 0 3px 8px rgba(0, 0, 0, .2);
}
/* ================= جدول کتاب‌ها ================= */
.books-table{
  width: calc(100% - 36px);
  margin: 0 18px 34px;
  background: #ffffff;
  border: 0;
  border-collapse: separate;
  border-spacing: 0;
  border-radius: 14px;
  overflow: hidden;
  box-shadow: 0 10px 28px rgba(0, 0, 0, .09);
}
.books-table > tbody > tr > th{
  background: linear-gradient(180deg, #2c3a4b 0%, #1d2733 100%);
  color: #ffffff;
  font-size: 13.5px;
  font-weight: 600;
  padding: 14px 12px;
  border: 0;
  text-align: center;
  white-space: nowrap;
}
.books-table > tbody > tr > td{
  padding: 12px;
  font-size: 14px;
  color: #33404d;
  border-top: 1px solid #eef2f6;
  border-right: 0;
  border-left: 0;
  text-align: center;
  vertical-align: middle;
}
.books-table > tbody > tr:nth-child(even) > td{
  background: #f8fbfc;
}
.books-table > tbody > tr:hover > td{
  background: #e9f8f4;
  color: #163a32;
}
.books-table > tbody > tr:last-child > td{
  border-bottom: 0;
}
@media (max-width: 767px){
  .books-table{
    width: calc(100% - 24px);
    margin: 0 12px 24px;
    font-size: 13px;
  }
  .books-table > tbody > tr > th,
  .books-table > tbody > tr > td{ padding: 9px 7px; }
}
h2{
  margin: 16px 18px 12px;
  font-size: 22px;
  color: #26313c;
}
@media (max-width: 640px){
  .search-row{ flex-direction: column; }
  .search-btn{ width: 100%; }
}

/* ================= سایدبار کشویی ================= */
#overlay{
  position: fixed;
  inset: 0;
  background: rgba(5, 10, 18, .62);
  -webkit-backdrop-filter: blur(3px);
  backdrop-filter: blur(3px);
  opacity: 0;
  visibility: hidden;
  transition: opacity .35s ease, visibility .35s ease;
  z-index: 1001;
}
#overlay.show{
  opacity: 1;
  visibility: visible;
}

.sidenav{
  position: fixed;
  top: 0;
  right: 0;
  height: 100%;
  width: 330px;
  max-width: 85vw;
  background: linear-gradient(180deg, #1b2430 0%, #0e141b 100%);
  box-shadow: -18px 0 45px rgba(0, 0, 0, .55);
  border-radius: 0 0 0 20px;
  transform: translateX(105%);
  transition: transform .45s cubic-bezier(.4, 0, .2, 1);
  z-index: 1002;
  padding: 0 0 18px;
  overflow-y: auto;
}
.sidenav.open{
  transform: translateX(0);
}

.sidenav-head{
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 20px 22px 16px;
  border-bottom: 1px solid rgba(255, 255, 255, .08);
  margin-bottom: 12px;
}
.sidenav-title{
  color: #3be3c4;
  font-size: 17px;
  font-weight: bold;
  letter-spacing: .3px;
}
.sidenav .closebtn{
  color: #cfd6dd;
  font-size: 30px;
  line-height: 1;
  text-decoration: none;
  padding: 0 4px;
  transition: color .2s, transform .2s;
}
.sidenav .closebtn:hover{
  color: #ff5a5a;
  transform: rotate(90deg);
}

.sidenav a.menu-item{
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 13px 22px;
  margin: 4px 12px;
  border-radius: 10px;
  color: #cfd6dd;
  font-size: 16px;
  text-decoration: none;
  transition: background .25s, color .25s, padding-right .25s;
}
.sidenav a.menu-item .glyphicon{
  font-size: 15px;
  color: #6f7c8a;
  transition: color .25s;
}
.sidenav a.menu-item:hover,
.sidenav a.menu-item:focus{
  background: rgba(59, 227, 196, .12);
  color: #ffffff;
  padding-right: 30px;
  text-decoration: none;
}
.sidenav a.menu-item:hover .glyphicon,
.sidenav a.menu-item:focus .glyphicon{
  color: #3be3c4;
}

/* دکمهٔ باز کردن منو */
.menu-btn{
  display: inline-flex;
  align-items: center;
  gap: 8px;
  margin: 18px 0 0 18px;
  padding: 10px 18px;
  border: 0;
  border-radius: 10px;
  background: linear-gradient(#2b3a4d, #1b2532);
  color: #ffffff;
  font-size: 15px;
  cursor: pointer;
  box-shadow: 0 6px 16px rgba(0, 0, 0, .3), inset 0 1px 0 rgba(255, 255, 255, .12);
  transition: background .2s, transform .12s;
}
.menu-btn:hover{ background: linear-gradient(#35485f, #223044); }
.menu-btn:active{ transform: translateY(2px); }

body.no-scroll{ overflow: hidden; }

@media screen and (max-height: 450px) {
  .sidenav{ padding-top: 6px; }
  .sidenav a.menu-item{ font-size: 14px; padding: 9px 22px; }
}
</style>
<body>
<!-- search bar    -->
<div id="overlay" onclick="closeNav()"></div>

<div id="mySidenav" class="sidenav">
  <div class="sidenav-head">
    <span class="sidenav-title">منوی کتابخانه</span>
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()" aria-label="بستن">&times;</a>
  </div>

  <a class="menu-item" href="books.php">
    <span class="glyphicon glyphicon-book"></span> کتاب‌ها
  </a>
  <a class="menu-item" href="request.php">
    <span class="glyphicon glyphicon-send"></span> درخواست کتاب
  </a>
  <a class="menu-item" href="issue_info.php">
    <span class="glyphicon glyphicon-list-alt"></span> اطلاعات امانت
  </a>
</div>

<div id="main">
  <button class="menu-btn" onclick="openNav()">&#9776; منو</button>
</div>





<div class="search-panel">

  <div class="search-card">
    <form method="post" name="form1" class="search-form">
      <label class="search-label">
        <span class="glyphicon glyphicon-search"></span> جستجوی کتاب بر اساس نام
      </label>
      <div class="search-row">
        <input type="text" class="form-control" name="search"
               placeholder="نام کتاب را وارد کنید…" required>
        <button type="submit" name="submit" class="search-btn" title="جستجو">
          <span class="glyphicon glyphicon-search"></span>
        </button>
      </div>
    </form>
  </div>

  <div class="search-card">
    <form method="post" name="form2" class="search-form">
      <label class="search-label">
        <span class="glyphicon glyphicon-tag"></span> درخواست کتاب بر اساس شناسه
      </label>
      <div class="search-row">
        <input type="text" class="form-control" name="bid"
               placeholder="شناسهٔ کتاب (ID) را وارد کنید…" required>
        <button type="submit" name="submit1" class="search-btn request">درخواست</button>
      </div>
    </form>
  </div>

</div>




   <h2>List Of Books</h2>

   <?php
    if(isset($_POST['submit'])){
       $q=mysqli_query($db,"SELECT * FROM books WHERE 
       name LIKE '%$_POST[search]%' ");
        
        if(mysqli_num_rows($q)==0){
         echo "Sorry! No book found!";
        }
        else{
       
            
     echo "<table class='table books-table'>";
     echo "<tr class='books-head'>";
        echo "<th>";  echo "ID";   echo "</th>";
        echo "<th>";  echo "Books-Name";  echo "</th>";
        echo "<th>";  echo "Authors Name";  echo "</th>";
        echo "<th>";  echo "Edition";  echo "</th>";
        echo "<th>";  echo "Status";  echo "</th>";
        echo "<th>";  echo "Quantity";  echo "</th>";
         echo "<th>";  echo "Department";  echo "</th>";
      echo "</tr>";
      while($row=mysqli_fetch_assoc($q))
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



        }//end else
       }
      else{
         $res=mysqli_query($db,"SELECT * FROM `books`;");
     
     echo "<table class='table books-table'>";
     echo "<tr class='books-head'>";
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

      if(isset($_POST['submit1']))
        {
          if(isset($_SESSION['login_user']))
            {
              mysqli_query($db,"INSERT INTO
               issue_book VALUES ('$_SESSION[login_user]','$_POST[bid]','','','');");
            }
            else{
              ?>
                   <script  type="text/javascript">
                      window.location="request.php";
                   </script>
              <?php
            }
        }

    
   ?>













<script>
var sidenav = document.getElementById("mySidenav");
var overlay = document.getElementById("overlay");

function openNav() {
  sidenav.classList.add("open");
  overlay.classList.add("show");
  document.body.classList.add("no-scroll");
}

function closeNav() {
  sidenav.classList.remove("open");
  overlay.classList.remove("show");
  document.body.classList.remove("no-scroll");
}

document.addEventListener("keydown", function (e) {
  if (e.key === "Escape") { closeNav(); }
});
</script>
</body>
</html>