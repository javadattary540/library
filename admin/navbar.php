 <?php
   session_start();
 ?>
 <nav  class="navbar navbar-inverse" style="width:100%">
      <div class="container-fluid">
         <div class="navbar-header">
              <a  class="navbar-brand active" style="color:gold;">ONLINE LIBRARY MANAGMENT SYSTEM</a>
         </div>
  
   
      <ul class="nav navbar-nav">
             <li><a href="index.php">HOME</a></li>
             <li><a href="books.php">BOOKS</a></li>
            
             <li><a href="feedback.php">FEEDBACK</a></li>
      </ul>
      <?php
         if(isset($_SESSION['login_user']))
            {
               ?>
           <ul class="nav navbar-nav"> 
            <li>
               <a href="student.php">Student-Information</a>
            </li> 
           </ul>
         <ul  class="nav navbar-nav   navbar-right">
            <li>
                 <a href="">
                     <div style="color:red;">
                  <?php
               echo "welcome"   . $_SESSION['login_user'];
               ?>
              </div>
                 </a>
           </li>
             <li>
               <a href="logout.php">
               <span  class="glyphicon  glyphicon-log-out">LOGOUT</span>
              </a>
            </li>
           
         </ul>
               <?php
         }else
         {
            ?>
           <ul  class="nav navbar-nav   navbar-right">
             <li>
               <a href="admin_login.php">
               <span class="glyphicon  glyphicon-log-in">LOGIN</span></a>
            </li>
           
             <li><a href="registration.php">
               <span class="glyphicon glyphicon-user">SIGNUP</span></a>
            </li>
           </ul>
           <?php
         }
      ?>
            
            </div>
   </nav>