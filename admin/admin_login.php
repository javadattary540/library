<?php
header('Content-Type: text/html; charset=utf-8');

include "connection.php";
session_start();

$error   = '';
$notice  = '';
$olduser = '';

/* ---------- توکن CSRF ---------- */
if (empty($_SESSION['csrf_login'])) {
    $_SESSION['csrf_login'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf_login'];

/* ---------- محدودیت تلاش ورود ---------- */
if (!isset($_SESSION['login_attempts']))     $_SESSION['login_attempts'] = 0;
if (!isset($_SESSION['login_locked_until'])) $_SESSION['login_locked_until'] = 0;

$MAX_TRIES   = 5;
$LOCK_SECONDS = 60;

/* ---------- پردازش فرم ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

    $olduser = trim((string)($_POST['username'] ?? ''));
    $pass    = (string)($_POST['password'] ?? '');
    $token   = (string)($_POST['csrf'] ?? '');

    if (!hash_equals($csrf, $token)) {
        $error = 'درخواست نامعتبر است. لطفاً صفحه را دوباره بارگذاری کنید.';
    } elseif (time() < (int)$_SESSION['login_locked_until']) {
        $left  = (int)$_SESSION['login_locked_until'] - time();
        $error = 'تعداد تلاش‌های ناموفق زیاد بود. ' . $left . ' ثانیه دیگر دوباره تلاش کنید.';
    } elseif ($olduser === '' || $pass === '') {
        $error = 'نام کاربری و رمز عبور را وارد کنید.';
    } else {
        $stmt = mysqli_prepare($db, "SELECT `id`, `username`, `password` FROM `admin` WHERE `username` = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, "s", $olduser);
        mysqli_stmt_execute($stmt);
        $res  = mysqli_stmt_get_result($stmt);
        $user = $res ? mysqli_fetch_assoc($res) : null;
        mysqli_stmt_close($stmt);

        $ok = false;

        if ($user) {
            $stored = (string)$user['password'];

            /* ۱) رمز هش‌شده  ۲) سازگاری با رمزهای قدیمیِ متن ساده */
            if (password_verify($pass, $stored)) {
                $ok = true;
            } elseif (hash_equals($stored, $pass)) {
                $ok = true;

                /* ارتقای خودکار رمز قدیمی به hash امن */
                $newHash = password_hash($pass, PASSWORD_DEFAULT);
                $up = mysqli_prepare($db, "UPDATE `admin` SET `password` = ? WHERE `id` = ?");
                if ($up) {
                    mysqli_stmt_bind_param($up, "si", $newHash, $user['id']);
                    mysqli_stmt_execute($up);
                    mysqli_stmt_close($up);
                }
            }
        }

        if ($ok) {
            session_regenerate_id(true);
            $_SESSION['login_user'] = $user['username'];
            $_SESSION['admin_id']   = (int)$user['id'];
            $_SESSION['login_attempts']     = 0;
            $_SESSION['login_locked_until'] = 0;
            $_SESSION['csrf_login'] = bin2hex(random_bytes(32));

            header("Location: index.php");
            exit;
        }

        $_SESSION['login_attempts']++;
        if ($_SESSION['login_attempts'] >= $MAX_TRIES) {
            $_SESSION['login_attempts'] = 0;
            $_SESSION['login_locked_until'] = time() + $LOCK_SECONDS;
            $error = 'تعداد تلاش‌های ناموفق زیاد بود. حساب به مدت ' . $LOCK_SECONDS . ' ثانیه قفل شد.';
        } else {
            $left  = $MAX_TRIES - (int)$_SESSION['login_attempts'];
            $error = 'نام کاربری یا رمز عبور درست نیست. (' . $left . ' تلاش باقی مانده)';
        }
    }

    /* توکن تازه برای رندر بعدی */
    $_SESSION['csrf_login'] = bin2hex(random_bytes(32));
    $csrf = $_SESSION['csrf_login'];
}

if (isset($_GET['out'])) {
    $notice = 'با موفقیت از حساب خارج شدید.';
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="./css/styles.css">
    <title>ورود مدیر</title>

    <style>
        :root{
            --gold:#f0c75e;
            --gold-2:#c9992b;
            --line:rgba(255,255,255,.14);
            --txt:#f2f5fa;
            --muted:#a9b2c4;
        }

        html, body{ height:100%; }

        body.al-page{
            background:
                linear-gradient(180deg, rgba(7,9,14,.78) 0%, rgba(7,9,14,.9) 60%, rgba(7,9,14,.97) 100%),
                url("./images/b3.jpg") center/cover fixed no-repeat;
            color:var(--txt);
            display:flex;
            align-items:center;
            justify-content:center;
            min-height:100vh;
            padding:26px 16px;
            margin:0;
        }

        .al-card{
            width:min(430px, 100%);
            background:rgba(16,19,27,.66);
            border:1px solid var(--line);
            border-radius:24px;
            padding:34px 30px 26px;
            -webkit-backdrop-filter:blur(16px) saturate(140%);
            backdrop-filter:blur(16px) saturate(140%);
            box-shadow:0 28px 70px rgba(0,0,0,.55);
            position:relative;
            overflow:hidden;
            animation:alUp .5s ease both;
        }
        .al-card::before{
            content:"";
            position:absolute;
            top:0; right:0; left:0;
            height:4px;
            background:linear-gradient(90deg, var(--gold-2), var(--gold), var(--gold-2));
        }

        @keyframes alUp{
            from{ opacity:0; transform:translateY(16px) scale(.99); }
            to  { opacity:1; transform:translateY(0) scale(1); }
        }

        .al-icon{
            width:58px; height:58px;
            margin:0 auto 16px;
            display:flex; align-items:center; justify-content:center;
            font-size:26px;
            border-radius:18px;
            background:linear-gradient(135deg, var(--gold), var(--gold-2));
            box-shadow:0 12px 26px rgba(201,153,43,.35);
        }

        .al-title{
            text-align:center;
            font-size:23px;
            font-weight:700;
            margin:0 0 6px;
        }
        .al-sub{
            text-align:center;
            color:var(--muted);
            font-size:13.5px;
            margin:0 0 22px;
        }

        .al-field{ margin-bottom:14px; }
        .al-label{
            display:block;
            font-size:13px;
            color:var(--muted);
            margin:0 2px 7px;
        }

        .al-input{
            width:100%;
            height:48px;
            padding:0 44px 0 16px;
            border-radius:13px;
            border:1px solid var(--line);
            background:rgba(255,255,255,.055);
            color:var(--txt);
            font-family:inherit;
            font-size:15px;
            outline:none;
            transition:border-color .2s, box-shadow .2s, background .2s;
        }
        .al-input::placeholder{ color:#8b95a7; }
        .al-input:focus{
            border-color:rgba(240,199,94,.65);
            background:rgba(255,255,255,.08);
            box-shadow:0 0 0 4px rgba(240,199,94,.14);
        }

        .al-input-wrap{ position:relative; }
        .al-eye{
            position:absolute;
            top:50%;
            left:8px;
            transform:translateY(-50%);
            width:34px; height:34px;
            display:flex; align-items:center; justify-content:center;
            border:0;
            border-radius:9px;
            background:transparent;
            color:#95a0b3;
            cursor:pointer;
            font-size:15px;
            transition:background .2s, color .2s;
        }
        .al-eye:hover{ background:rgba(255,255,255,.08); color:var(--gold); }

        .al-btn{
            width:100%;
            height:50px;
            margin-top:8px;
            border:0;
            border-radius:14px;
            cursor:pointer;
            font-family:inherit;
            font-size:16px;
            font-weight:700;
            color:#241a05;
            background:linear-gradient(135deg, var(--gold), var(--gold-2));
            box-shadow:0 12px 26px rgba(201,153,43,.32);
            transition:transform .18s, box-shadow .18s, filter .18s;
        }
        .al-btn:hover{
            transform:translateY(-2px);
            filter:brightness(1.05);
            box-shadow:0 16px 32px rgba(201,153,43,.42);
        }
        .al-btn:active{ transform:translateY(0); }

        .al-alert{
            display:flex;
            align-items:flex-start;
            gap:9px;
            padding:11px 14px;
            border-radius:12px;
            font-size:13.5px;
            line-height:1.8;
            margin-bottom:16px;
            border:1px solid transparent;
            animation:alUp .3s ease both;
        }
        .al-alert.err{
            background:rgba(255,99,99,.12);
            border-color:rgba(255,99,99,.4);
            color:#ffc0bb;
        }
        .al-alert.ok{
            background:rgba(52,199,123,.12);
            border-color:rgba(52,199,123,.4);
            color:#a8f0c6;
        }

        .al-links{
            margin-top:20px;
            padding-top:16px;
            border-top:1px solid rgba(255,255,255,.08);
            display:flex;
            flex-direction:column;
            gap:10px;
            font-size:13.5px;
        }
        .al-links a{
            color:var(--gold);
            text-decoration:none;
            transition:color .2s;
        }
        .al-links a:hover{ color:#ffe9a8; text-decoration:none; }
        .al-links .row-line{
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:10px;
            color:var(--muted);
            flex-wrap:wrap;
        }
        .al-links .chip{
            display:inline-flex;
            align-items:center;
            gap:6px;
            padding:7px 14px;
            border-radius:10px;
            border:1px solid rgba(255,255,255,.12);
            background:rgba(255,255,255,.05);
            color:#dfe5ee;
            font-size:13px;
        }
        .al-links .chip:hover{
            background:rgba(240,199,94,.14);
            border-color:rgba(240,199,94,.4);
            color:#fff;
        }

        @media (max-width:420px){
            .al-card{ padding:26px 20px 20px; border-radius:20px; }
            .al-title{ font-size:20px; }
        }
    </style>
</head>
<body class="al-page">

<div class="al-card">

    <div class="al-icon">🔐</div>
    <h1 class="al-title">ورود مدیران</h1>
    <p class="al-sub">برای مدیریت کتابخانه وارد حساب خود شوید</p>

    <?php if ($notice !== ''): ?>
        <div class="al-alert ok"><span>✓</span><span><?php echo htmlspecialchars($notice, ENT_QUOTES, 'UTF-8'); ?></span></div>
    <?php endif; ?>

    <?php if ($error !== ''): ?>
        <div class="al-alert err"><span>!</span><span><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></span></div>
    <?php endif; ?>

    <form name="login" action="" method="post" autocomplete="off">
        <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8'); ?>">

        <div class="al-field">
            <label class="al-label" for="username">نام کاربری</label>
            <div class="al-input-wrap">
                <input class="al-input" id="username" type="text" name="username"
                       value="<?php echo htmlspecialchars($olduser, ENT_QUOTES, 'UTF-8'); ?>"
                       placeholder="نام کاربری خود را وارد کنید" required>
            </div>
        </div>

        <div class="al-field">
            <label class="al-label" for="password">رمز عبور</label>
            <div class="al-input-wrap">
                <input class="al-input" id="password" type="password" name="password"
                       placeholder="رمز عبور خود را وارد کنید" required>
                <button type="button" class="al-eye" id="eye" aria-label="نمایش رمز">👁</button>
            </div>
        </div>

        <button type="submit" class="al-btn" name="submit">ورود به پنل</button>
    </form>

    <div class="al-links">
        <a href="update_password.php">رمز عبور را فراموش کرده‌اید؟</a>

        <div class="row-line">
            <span>حساب ندارید؟</span>
            <a class="chip" href="registration.php">ثبت‌نام مدیر جدید</a>
        </div>

        <div class="row-line">
            <span>ورود دانشجو</span>
            <a class="chip" href="../student/student_login.php">صفحهٔ ورود دانشجو</a>
        </div>

        <a class="chip" href="index.php" style="justify-content:center;">بازگشت به خانه</a>
    </div>

</div>

<script>
    (function () {
        var field = document.getElementById('password');
        var eye   = document.getElementById('eye');
        if (!field || !eye) return;

        eye.addEventListener('click', function () {
            var show = field.type === 'password';
            field.type = show ? 'text' : 'password';
            eye.textContent = show ? '🙈' : '👁';
        });
    })();
</script>

</body>
</html>
