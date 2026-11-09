 <?php
   session_start();
 ?>
 <nav  class="navbar navbar-inverse" style="width:100%; word-spacing:normal; letter-spacing:normal;">
      <div class="container-fluid">
         <div class="navbar-header">
              <a  class="navbar-brand active" style="color:gold;">ONLINE LIBRARY MANAGMENT SYSTEM</a>
         </div>
  
   
      <ul class="nav navbar-nav">
             <li><a href="index.php">صفحه نخست</a></li>
             <li><a href="books.php">کتاب ها</a></li>
            
             <li><a href="feedback.php">نظرات</a></li>
      </ul>
      <?php
         if(isset($_SESSION['login_user']))
            {
               ?>
              
       <ul class="nav navbar-nav">
        <li>
           <a href="profile.php">
           پروفایل کاربر
           </a>
        </li>
       </ul>
         <ul  class="nav navbar-nav   navbar-right">
            <li>
                 <a href="">
                     <div style="color:red;">
                  <?php
                  echo "<img class='img-circle profile_img' height=30 
                  width=30 src='images/".$_SESSION['pic']."'>";

                   echo " "   . $_SESSION['login_user'];
               ?>
              </div>
                 </a>
           </li>
             <li><a href="logout.php">
               <span  class="glyphicon  glyphicon-log-out">خروج کاربر</span></a></li>
         </ul>
               <?php
         }else
         {
            ?>
           <ul  class="nav navbar-nav   navbar-right">
             <li>
               <a href="student_login.php">
               <span class="glyphicon  glyphicon-log-in">ورود</span></a>
            </li>
           
             <li><a href="registration.php">
               <span class="glyphicon glyphicon-user">ثبت نام</span></a>
            </li>
           </ul>
           <?php
         }
      ?>
            
            </div>
   </nav>