<?php

   include "connection.php";
   session_start();

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

    <title>صفحه ورود </title>
        <style>
       section{
         margin-top:-20px;
       }

       /* ================= پس‌زمینهٔ متحرک: دانش‌آموزی که کتاب برمی‌دارد ================= */
       .reg_img {
         position: relative;
         overflow: hidden;
         background: #0b1b33;
       }

       .reg_img::before {
         content: "";
         position: absolute;
         top: -6%; left: -6%; right: -6%; bottom: -6%;
         background-image: url('./images/student-book.jpg');
         background-size: cover;
         background-position: 50% 38%;
         transform-origin: 62% 26%;      /* زوم به سمت دست و کتاب */
         z-index: 0;
         pointer-events: none;
         animation: bookReach 20s ease-in-out infinite alternate;
       }

       /* ویدیوی پس‌زمینه (اگر فایل موجود باشد، روی عکس پخش می‌شود) */
       .reg_img .bg-video {
         position: absolute;
         top: 0; left: 0;
         width: 100%;
         height: 100%;
         object-fit: cover;
         z-index: 0;
         pointer-events: none;
       }

       /* چپ‌چین کردن متن و placeholderهای فرم */
       .reg_img .form-control {
         direction: ltr;
         text-align: left;
       }
       .reg_img .form-control::placeholder {
         text-align: left;
         opacity: .8;
       }
       .reg_img .form-control::-webkit-input-placeholder {
         text-align: left;
       }
       /* لینک Forget password: ۲۰px بالا و ۲۰px چپ + هاور طلایی */
       .reg_img .forget-link {
         display: inline-block;
         color: #ffffff;
         transform: translate(-50px, -20px);
         transition: color .25s ease, text-shadow .25s ease;
       }
       .reg_img .forget-link:hover,
       .reg_img .forget-link:focus {
         color: gold;
         text-shadow: 0 0 10px rgba(255, 215, 0, .45);
       }

       /* ================= دکمهٔ لاگین سه‌بعدی ================= */
       .reg_img .login-btn {
         width: 100%;
         height: 46px;
         margin-top: 4px;
         border: 0;
         border-radius: 10px;
         font-size: 16px;
         font-weight: bold;
         letter-spacing: .5px;
         color: #ffffff;
         background: linear-gradient(#63e08f, #16a34a);
         text-shadow: 0 1px 0 rgba(255, 255, 255, .45);
         box-shadow: 0 7px 0 #0f7a37,
                     0 12px 22px rgba(0, 0, 0, .45),
                     inset 0 1px 0 rgba(255, 255, 255, .75);
         transition: transform .12s ease, box-shadow .12s ease, background .2s ease;
       }
       .reg_img .login-btn:hover,
       .reg_img .login-btn:focus {
         background: linear-gradient(#7deda6, #22c55e);
         outline: none;
       }
       .reg_img .login-btn:active {
         transform: translateY(6px);
         box-shadow: 0 1px 0 #0f7a37,
                     0 3px 8px rgba(0, 0, 0, .35),
                     inset 0 1px 0 rgba(255, 255, 255, .5);
       }

       /* جابه‌جایی عبارت «تازه وارد شدی» ۳۰ پیکسل به چپ */
       .reg_img .welcome-msg {
         display: inline-block;
         transform: translateX(-30px);
       }

       /* ================= دکمهٔ «ثبت نام کن» سه‌بعدی ================= */
       .reg_img .signup-btn {
         display: inline-block;
         padding: 10px 20px;
         border-radius: 14px;
         font-weight: bold;
         color: #ffffff;
         text-decoration: none;
         background: linear-gradient(#ff5a5a, #d40000);
         text-shadow: 0 1px 1px rgba(0, 0, 0, .35);
         box-shadow: 0 6px 0 #8f0000,
                     0 10px 20px rgba(0, 0, 0, .45),
                     inset 0 1px 0 rgba(255, 255, 255, .35);
         margin-right: 30px;
         transition: transform .15s ease, box-shadow .15s ease, background .2s ease;
       }
       .reg_img .signup-btn:hover,
       .reg_img .signup-btn:focus {
         color: #ffffff;
         text-decoration: none;
         background: linear-gradient(#ff6f6f, #e00000);
       }
       .reg_img .signup-btn:active {
         transform: translateY(5px);
         box-shadow: 0 1px 0 #8f0000,
                     0 3px 8px rgba(0, 0, 0, .40);
       }

       /* ================= دکمهٔ «بازگشت به صفحه اصلی» سه‌بعدی ================= */
       .reg_img .home-btn {
         display: block;
         width: 100%;
         padding: 13px 16px;
         text-align: center;
         border-radius: 10px;
         font-weight: bold;
         font-size: 15px;
         color: #3a2400;
         text-decoration: none;
         background: linear-gradient(#ffd27f, #f0a30a);
         text-shadow: 0 1px 0 rgba(255, 255, 255, .45);
         box-shadow: 0 6px 0 #b97600,
                     0 10px 20px rgba(0, 0, 0, .45),
                     inset 0 1px 0 rgba(255, 255, 255, .5);
         transition: transform .15s ease, box-shadow .15s ease, background .2s ease;
       }
       .reg_img .home-btn:hover,
       .reg_img .home-btn:focus {
         color: #3a2400;
         text-decoration: none;
         background: linear-gradient(#ffdd9c, #ffb52e);
       }
       .reg_img .home-btn:active {
         transform: translateY(5px);
         box-shadow: 0 1px 0 #b97600,
                     0 3px 8px rgba(0, 0, 0, .4);
       }

       /* خوانایی فرم روی عکس روشن */
       .reg_img .box1 {
         background-color: rgba(8, 14, 20, .82);
         opacity: 1;
         border-radius: 14px;
         box-shadow: 0 18px 50px rgba(0, 0, 0, .45);
       }
       /* فرم ورود بالای پس‌زمینه بماند */
       .reg_img > * {
         position: relative;
         z-index: 2;
       }

       /* حرکت آرام دوربین به سمت کتاب */
       @keyframes bookReach {
         0%   { transform: scale(1.02) translate3d(0, 0, 0); }
         100% { transform: scale(1.24) translate3d(-1%, -1%, 0); }
       }

       /* اعتبار عکس (لایسنس CC BY 2.0) */
       .bg-credit {
         position: absolute;
         left: 10px;
         bottom: 8px;
         z-index: 3;
         font-size: 11px;
         color: rgba(255, 255, 255, .55);
         direction: ltr;
       }
       .bg-credit a {
         color: rgba(255, 255, 255, .6);
         text-decoration: underline;
       }

       @media (prefers-reduced-motion: reduce) {
         .reg_img::before { animation: none; }
       }
   </style>
</head>
<body>
 


<section>
  <div class="reg_img">
      <!-- ویدیوی پس‌زمینه: فایل را در student/videos/student-book.mp4 یا .webm بگذارید -->
      <video class="bg-video" autoplay muted loop playsinline preload="none">
        <source src="./videos/student-book.webm" type="video/webm">
        <source src="./videos/student-book.mp4" type="video/mp4">
      </video>
      <br><br><br>
      <div class="box1">
          <h1 style="text-align:center;font-size:25px;color:#fff;">
             User Login Form
          </h1><br>
         <form   name="login"  action=""  method="post">
           
             <div class="login">
                   <input  class="form-control"  type="text"   name="username"  placeholder="Username" required><br><br>
                   <input   class="form-control" type="password"  name="password"  placeholder="Password"  required><br><br>
                   <input type="submit" class="btn btn-default login-btn" name="submit" value="Login">
             </div>
        
         <br>
         <p  style="color:white;padding:10px 50px;">
            <br><br>
            <a href="update_password.php" class="forget-link">Forget password?</a> <br>
            <span class="welcome-msg">تازه وارد شدی</span> <a class="signup-btn" href="registration.php">ثبت نام کن</a>
           <br><br><br>
           <a class="home-btn" href="index.php">بازگشت به صفحه اصلی</a>
         </p>
          </form>
      </div>
      <div class="bg-credit">
        Photo: <a href="https://commons.wikimedia.org/wiki/File:Person_reaching_for_a_book_on_a_wooden_shelf_in_a_cozy_reading_space.jpg" target="_blank" rel="noopener">Shixart1985</a> / CC BY 2.0
      </div>
  </div> 
</section>

<?php
   if(isset($_POST['submit'])){
     $count=0;
     $res=mysqli_query
     ($db,"SELECT * FROM `student`  WHERE username='$_POST[username]'
      AND password='$_POST[password]';");
      $row=mysqli_fetch_assoc($res);
      $count=mysqli_num_rows($res);
     
     if($count ==0){
        echo '<div  class="alert alert-danger fade-in" 
        style="background-color:orange;position:absolute;top:2%;left:20%;width:700px;">
                the username and password dos not match!!;
              </div>';
 
     }
     else{
      $_SESSION['login_user'] = $_POST['username'];
      $_SESSION['pic'] = $row['pic'];
        echo '<script>window.location="index.php";</script>';
     }
   }

?>





</body>
</html>