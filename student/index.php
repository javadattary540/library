<?php
   session_start();

?>
<!DOCTYPE html>
<html lang="fa"  dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet"  href="./css/styles.css">
    
    <title>سیستم مدیریت کتابخانه</title>
    <style>
         .navbar-black {
     background-color: #3d8f64;
    border: 0;
    border-radius: 0;
    margin-bottom: 0;
  }

  .navbar-black .navbar-brand,
  .navbar-black .navbar-text,
  .navbar-black .navbar-nav > li > a {
    color: #fff;
  }
  
    /* آیکون کنار لینک‌های نوار */
  .navbar-black .navbar-nav > li > a .glyphicon {
    margin-left: 6px;
    font-size: 13px;
    top: 1px;
  }

  /* ================= استایل هاور لینک‌های نوار ================= */
  .navbar-black .navbar-nav > li > a {
    position: relative;
    color: #ffffff;
    background-image: linear-gradient(#ffffff, #ffffff);
    background-repeat: no-repeat;
    background-position: center center;
    background-size: 0% 76%;
    border-radius: 8px;
    padding: 15px 16px;
    transition: background-size .3s ease, color .25s ease, letter-spacing .3s ease;
  }
  .navbar-black .navbar-nav > li > a:hover,
  .navbar-black .navbar-nav > li > a:focus {
    color: #053024 !important;
    background-color: transparent !important;
    background-size: 94% 76%;
  }
  .navbar-black .navbar-nav > li.active > a {
    color: #053024 !important;
    background-color: transparent !important;
    background-size: 94% 76%;
  }
    .navbar-black {
    background-color: #3be3c4;
    border: 0;
    border-radius: 0;
    margin-bottom: 0;
  }

  .navbar-black .navbar-brand,
  .navbar-black .navbar-text,
  .navbar-black .navbar-nav > li > a {
    color: #fff;
  }
  .navbar-black .navbar-toggle {
    float: right;                 /* چون لوگو رفته سمت چپ، دکمه همبرگری سمت راست */
    margin-right: 0;
    margin-left: 15px;
    border-color: #3a3a3a;
  }
  .navbar-black .navbar-toggle:hover,
  .navbar-black .navbar-toggle:focus {
    background-color: #1f1f1f;
  }
  .navbar-black .navbar-toggle .icon-bar {
    background-color: #fff;
  }

  /* ================= چیدمان نوبار: لوگو سمت چپ، منو سمت راست ================= */
  .navbar-black .navbar-brand { float: left; }
  .navbar-black .navbar-nav   { text-align: right; }

  /* لوگو کامل داخل نوار جا شود (ارتفاع نوار ۵۰px) */
  .navbar-black .navbar-brand {
    height: 50px;
    padding: 6px 15px;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .navbar-black .navbar-brand .navbar-logo {
    height: 38px;
    width: auto;
    display: block;
  }

  @media (min-width: 768px) {
    .navbar-black .navbar-header {
      float: left;              /* لوگو سمت چپ */
    }
    .navbar-black .navbar-brand {
      float: left;
    }
    .navbar-black .navbar-collapse,
    .navbar-black .navbar-nav,
    .navbar-black .navbar-nav > li {
      float: right;             /* منو از سمت راست */
    }
    .navbar-black .navbar-nav { margin: 0; }
  }

  /* ================= زیرمنوها ================= */
  .navbar-black .dropdown-menu {
    right: 0;
    left: auto;
    text-align: right;
    background-color: #111;
    border: 1px solid #333;
    border-radius: 0;
    box-shadow: none;
  }
  .navbar-black .dropdown-menu > li > a {
    color: #fff;
    padding: 8px 20px;
  }
  .navbar-black .dropdown-menu > li > a:hover,
  .navbar-black .dropdown-menu > li > a:focus {
    background-color: #145b4b;
    color: #a90f0f;
  }
  .navbar-black .dropdown-menu .divider { background-color: #333; }

  /* منوی بازشده در حالت موبایل */
  .navbar-black .navbar-nav .open .dropdown-menu {
    background-color: transparent;
    border: 0;
  }
  .navbar-black .navbar-nav .open .dropdown-menu > li > a { color: #fff; }

  @media (max-width: 767px) {
    .navbar-black .navbar-collapse {
      border-top: 1px solid #1f1f1f;
      box-shadow: none;
    }
  }

  /* ================= اسلایدر تمام صفحه (Bootstrap 3) ================= */
  .hero-slider {
    position: relative;
    height: calc(100vh - 50px);   /* تمام صفحه، منهای ارتفاع نوار */
    min-height: 420px;
    margin: 0;
    background: #05102E;
    overflow: hidden;
  }
  .hero-slider .carousel-inner,
  .hero-slider .carousel-inner > .item {
    height: 100%;
  }
  .hero-slider .carousel-inner > .item > img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;            /* عکس بدون کشیدگی کل کادر را پر میکند */
    object-position: center;
  }

  /* نقطه‌های پایین اسلایدر */
  .hero-slider .carousel-indicators {
    bottom: 20px;
    margin-bottom: 0;
  }
  .hero-slider .carousel-indicators li {
    width: 12px;
    height: 12px;
    margin: 0 5px;
    background: rgba(255,255,255,.55);
    border: 0;
    border-radius: 50%;
  }
  .hero-slider .carousel-indicators .active {
    width: 12px;
    height: 12px;
    margin: 0 5px;
    background: #3be3c4;
  }

  /* فلش‌های چپ و راست */
  .hero-slider .carousel-control {
    width: 7%;
    opacity: .55;
    background-image: none;
  }
  .hero-slider .carousel-control:hover,
  .hero-slider .carousel-control:focus {
    opacity: 1;
  }
  .hero-slider .carousel-control .glyphicon {
    font-size: 28px;
    top: 50%;
  }

  /* موبایل */
  @media (max-width: 767px) {
    .hero-slider { min-height: 320px; }
    .hero-slider .carousel-control { width: 12%; }
  }
      


  /* ================= نمایش لینک‌ها در عرض‌های متوسط (۶۰۰ تا ۷۶۷) ================= */
  @media (min-width: 600px) and (max-width: 767px) {
  .navbar-black .navbar-toggle { display: none !important; }
  .navbar-black .navbar-collapse.collapse {
    display: block !important; height: auto !important;
    overflow: visible !important; border-top: 0; box-shadow: none;
  }
  .navbar-black .navbar-header,
  .navbar-black .navbar-brand { float: left; }
  .navbar-black .navbar-collapse,
  .navbar-black .navbar-nav,
  .navbar-black .navbar-nav > li { float: right; }
  .navbar-black .navbar-nav > li > a { padding: 15px 8px; font-size: 16px; }
}

  /* ================= ستون‌های قوانین با چرخش (Flip) ================= */
  .rules-flip {
    height: auto;   /* خنثی کردن section{height:400px} در styles.css */
    padding: 60px 0 40px;
    background: #ffffff;
  }
  .rules-flip .rules-head {
    text-align: center;
    margin-bottom: 38px;
  }
  .rules-flip .rules-head h2 {
    margin: 0 0 12px;
    font-size: 30px;
    color: #0b1b33;
  }
  .rules-flip .rules-head p {
    margin: 0;
    font-size: 15px;
    color: #6b7681;
  }
  .rules-flip .rules-row {
    display: flex;
    flex-wrap: wrap;
  }
  .rules-flip .rules-row > [class*="col-"] {
    display: flex;
  }

  .flip-card {
    width: 100%;
    height: 340px;
    margin-bottom: 25px;
    perspective: 1200px;
    cursor: pointer;
    outline: none;
  }
  .flip-inner {
    position: relative;
    width: 100%;
    height: 100%;
    transform-style: preserve-3d;
    transition: transform .7s cubic-bezier(.2,.7,.2,1);
  }
  .flip-card:hover .flip-inner,
  .flip-card:focus .flip-inner {
    transform: rotateY(180deg);
  }
  .flip-face {
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 8px 22px rgba(0,0,0,.12);
  }

  /* روی کارت: عکس */
  .flip-front img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform .7s;
  }
  .flip-card:hover .flip-front img { transform: scale(1.06); }
  .flip-front:after {
    content: "";
    position: absolute;
    left: 0; right: 0; bottom: 0;
    height: 55%;
    background: linear-gradient(to top, rgba(5,16,46,.92), rgba(5,16,46,0));
  }
  .flip-caption {
    position: absolute;
    right: 0; left: 0; bottom: 0;
    padding: 0 20px 18px;
    text-align: right;
    color: #fff;
  }
  .flip-caption h3 {
    margin: 0 0 4px;
    font-size: 18px;
    color: #fff;
  }
  .flip-caption span {
    font-size: 12px;
    color: #9fe8da;
  }

  /* پشت کارت: متن قوانین */
  .flip-back {
    transform: rotateY(180deg);
    background: #0c2438;
    color: #fff;
    padding: 26px 22px;
    text-align: right;
  }
  .flip-back h3 {
    margin: 0 0 14px;
    font-size: 17px;
    color: #3be3c4;
  }
  .flip-back ul {
    margin: 0;
    padding: 0;
    list-style: none;
  }
  .flip-back li {
    position: relative;
    padding-right: 16px;
    margin-bottom: 9px;
    font-size: 13.5px;
    line-height: 1.85;
    color: #dbe7ee;
  }
  .flip-back li:before {
    content: "\2022";
    position: absolute;
    right: 0;
    top: 0;
    color: #3be3c4;
    font-weight: bold;
  }

  @media (max-width: 767px) {
    .flip-card { height: 300px; }
  }

  /* ================= فوتر ================= */
  .site-footer {
    background: #000000;
    color: #ffffff;
    height: 300px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding-top: 34px;
    margin-top: 60px;
    text-align: right;
  }
  .site-footer .footer-main { flex: 1; }
  .site-footer .footer-title {
    margin: 0 0 18px;
    font-size: 17px;
    color: #3be3c4;
  }
  .footer-social {
    list-style: none;
    margin: 0;
    padding: 0;
  }
  .footer-social li { margin-bottom: 12px; }
  .footer-social a {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    color: #ffffff;
    font-size: 14px;
    text-decoration: none;
    transition: color .2s;
  }
  .footer-social a:hover,
  .footer-social a:focus {
    color: #3be3c4;
    text-decoration: none;
  }
  .footer-social svg {
    width: 18px;
    height: 18px;
    fill: currentColor;
    flex: none;
  }
  .footer-mail-label {
    margin: 0 0 6px;
    font-size: 14px;
    color: #bdbdbd;
  }
  .footer-mail {
    display: inline-block;
    color: #ffffff;
    font-size: 15px;
    direction: ltr;
    unicode-bidi: embed;
    text-decoration: none;
    border-bottom: 1px dashed rgba(255,255,255,.35);
    padding-bottom: 2px;
    transition: color .2s, border-color .2s;
  }
  .footer-label-gap { margin-top: 16px; }
  .footer-phone { letter-spacing: .5px; }
  .footer-mail:hover,
  .footer-mail:focus {
    color: #3be3c4;
    border-color: #3be3c4;
    text-decoration: none;
  }
  .footer-bottom {
    border-top: 1px solid rgba(255,255,255,.15);
    padding: 14px 0;
    text-align: center;
    font-size: 12.5px;
    color: #b5b5b5;
  }
  @media (max-width: 767px) {
    .site-footer { height: auto; padding-bottom: 0; }
    .site-footer .footer-main { padding-bottom: 25px; }
  }

    </style>
</head>
<body dir="rtl">
   
    

    
    <?php
       if(isset($_SESSION['login_user']))
        {
        ?>    
       
      <nav class="navbar navbar-black">
  <div class="container">

    <!-- برند + دکمه همبرگری -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed"
              data-toggle="collapse" data-target="#menu-main" aria-expanded="false">
        <span class="sr-only">باز و بسته کردن منو</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">
        <img src="./images/logo.jpg" class="navbar-logo" alt="لوگو">
      </a>
    </div>

    <!-- آیتم‌های منو -->
    <div class="collapse navbar-collapse" id="menu-main">
      <ul class="nav navbar-nav">
        <li class="active"><a href="index.php"><span class="glyphicon glyphicon-home" aria-hidden="true"></span> صفحه نخست</a></li>
        <li><a href="logout.php"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> خروج کاربر</a></li>
        <li><a href="books.php"><span class="glyphicon glyphicon-book" aria-hidden="true"></span> کتاب ها</a></li>
        <li><a href="feedback.php"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span> نظرات و بازخوردها</a></li>
          <li><a href="../admin/admin_login.php"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span> پنل ادمین</a></li>
      </ul>
    </div>

  </div>
</nav>




   <?php
       }
       else{
        ?>
         
        <nav class="navbar navbar-black">
  <div class="container">

    <!-- برند + دکمه همبرگری -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed"
              data-toggle="collapse" data-target="#menu-main" aria-expanded="false">
        <span class="sr-only">باز و بسته کردن منو</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">
           <img src="./images/logo.jpg" class="navbar-logo" alt="لوگو">
      </a>
    </div>

    <!-- آیتم‌های منو -->
    <div class="collapse navbar-collapse" id="menu-main">
      <ul class="nav navbar-nav">
           <li class="active"><a href="index.php"><span class="glyphicon glyphicon-home" aria-hidden="true"></span> صفحه نخست</a></li>
        <li><a href="student_login.php"><span class="glyphicon glyphicon-log-in" aria-hidden="true"></span> ورود کاربر</a></li>
        <li><a href="books.php"><span class="glyphicon glyphicon-book" aria-hidden="true"></span> کتاب ها</a></li>
        <li><a href="feedback.php"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span> نظرات و بازخوردها</a></li>
          <li><a href="../admin/admin_login.php"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span> پنل ادمین</a></li>
      </ul>
    </div>

  </div>
</nav>



      <?php 
      }
        
    ?>

    <!-- ================= اسلایدر تمام صفحه ================= -->
    <div id="mainSlider" class="carousel slide hero-slider" data-ride="carousel" data-interval="5000" data-pause="hover">

      <!-- نقطه‌های پایین اسلایدر -->
      <ol class="carousel-indicators">
        <li data-target="#mainSlider" data-slide-to="0" class="active"></li>
        <li data-target="#mainSlider" data-slide-to="1"></li>
        <li data-target="#mainSlider" data-slide-to="2"></li>
        <li data-target="#mainSlider" data-slide-to="3"></li>
      </ol>

      <!-- ۴ عکس اسلایدر -->
      <div class="carousel-inner" role="listbox">
        <div class="item active">
          <img src="./images/l1.jpg" alt="سالن کتابخانه">
        </div>
        <div class="item">
          <img src="./images/b3.jpg" alt="سالن مطالعه">
        </div>
       
      </div>

      <!-- فلش‌های چپ و راست -->
      <a class="left carousel-control" href="#mainSlider" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">قبلی</span>
      </a>
      <a class="right carousel-control" href="#mainSlider" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">بعدی</span>
      </a>

    </div>

    </div>

        <!-- ================= قوانین و مقررات (سه ستون با چرخش) ================= -->
    <section class="rules-flip">
      <div class="container">
        <div class="rules-head">
          <h2>قوانین و مقررات</h2>
          <p>موس را روی هر تصویر ببرید تا قوانین همان بخش را ببینید.</p>
        </div>
        <div class="row rules-row">
          <div class="col-sm-4">
            <div class="flip-card" tabindex="0">
              <div class="flip-inner">
                <div class="flip-face flip-front">
                  <img src="./images/l3.jpg" alt="عضویت و ساعات کاری">
                  <div class="flip-caption">
                    <h3>عضویت و ساعات کاری</h3>
                    <span>برای دیدن قوانین، هاور کنید</span>
                  </div>
                </div>
                <div class="flip-face flip-back">
                  <h3>عضویت و ساعات کاری</h3>
                  <ul>
                    <li>ثبت‌نام حضوری با کارت شناسایی معتبر</li>
                    <li>شنبه تا چهارشنبه: ۸ صبح تا ۸ شب</li>
                    <li>پنجشنبه‌ها: ۸ صبح تا ۲ بعدازظهر</li>
                    <li>تمدید عضویت در ابتدای هر سال تحصیلی</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="flip-card" tabindex="0">
              <div class="flip-inner">
                <div class="flip-face flip-front">
                  <img src="./images/l4.jpg" alt="امانت کتاب">
                  <div class="flip-caption">
                    <h3>امانت کتاب</h3>
                    <span>برای دیدن قوانین، هاور کنید</span>
                  </div>
                </div>
                <div class="flip-face flip-back">
                  <h3>امانت کتاب</h3>
                  <ul>
                    <li>حداکثر ۳ کتاب به مدت ۱۴ روز</li>
                    <li>یک بار تمدید آنلاین مجاز است</li>
                    <li>تأخیر در بازگشت، تعلیق موقت عضویت</li>
                    <li>آسیب یا مفقودی کتاب، مشمول جریمه</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="flip-card" tabindex="0">
              <div class="flip-inner">
                <div class="flip-face flip-front">
                  <img src="./images/lib.jpg" alt="سالن مطالعه">
                  <div class="flip-caption">
                    <h3>سالن مطالعه</h3>
                    <span>برای دیدن قوانین، هاور کنید</span>
                  </div>
                </div>
                <div class="flip-face flip-back">
                  <h3>سالن مطالعه</h3>
                  <ul>
                    <li>رعایت سکوت و نظم در تمام سالن‌ها</li>
                    <li>خوردن و آشامیدن ممنوع است</li>
                    <li>نگه‌داشتن جا با وسایل شخصی مجاز نیست</li>
                    <li>مکالمهٔ تلفنی در محوطهٔ بیرونی</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>


   <br>
   <br>
    

    <!-- ================= فوتر ================= -->
    <footer class="site-footer">
      <div class="container footer-main">
        <div class="row">

          <div class="col-sm-6">
            <h4 class="footer-title">شبکه‌های اجتماعی</h4>
            <ul class="footer-social">
              <li>
                <a href="https://t.me/" target="_blank" rel="noopener">
                  <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>
                  تلگرام
                </a>
              </li>
              <li>
                <a href="https://instagram.com/" target="_blank" rel="noopener">
                  <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 1 0 0-12.324zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405a1.441 1.441 0 0 1-2.88 0 1.44 1.44 0 0 1 2.88 0z"/></svg>
                  اینستاگرام
                </a>
              </li>
              <li>
                <a href="https://twitter.com/" target="_blank" rel="noopener">
                  <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M23.953 4.57a10 10 0 0 1-2.825.775 4.958 4.958 0 0 0 2.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 0 0-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 0 0-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 0 1-2.228-.616v.06a4.923 4.923 0 0 0 3.946 4.827 4.996 4.996 0 0 1-2.212.085 4.936 4.936 0 0 0 4.604 3.417 9.867 9.867 0 0 1-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 0 0 7.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0 0 24 4.59z"/></svg>
                  توییتر
                </a>
              </li>
              <li>
                <a href="https://wa.me/" target="_blank" rel="noopener">
                  <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                  واتساپ
                </a>
              </li>
            </ul>
          </div>

          <div class="col-sm-6">
            <h4 class="footer-title">ارتباط با ما</h4>
            <p class="footer-mail-label">ایمیل:</p>
            <a class="footer-mail" href="mailto:javadatttar1364@gmail.com">javadatttar1364@gmail.com</a>
            <p class="footer-mail-label footer-label-gap">تلفن تماس:</p>
            <a class="footer-mail footer-phone" href="tel:+989344431676" dir="ltr">09344431676</a>
          </div>

        </div>
      </div>

      <div class="footer-bottom">
        <div class="container">© ۱۴۰۴ کتابخانه — تمامی حقوق محفوظ است.</div>
      </div>
    </footer>
<script src="../js/scripts.js"></script>
</body>
</html>