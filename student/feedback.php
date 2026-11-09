<?php
header('Content-Type: text/html; charset=utf-8');

include "connection.php";

$error   = '';
$notice  = '';
$oldtext = '';

/* ---------- ثبت نظر ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

    $oldtext = trim((string)($_POST['comment'] ?? ''));
    $len = function_exists('mb_strlen') ? mb_strlen($oldtext, 'UTF-8') : strlen($oldtext);

    if ($oldtext === '') {
        $error = 'لطفاً متن نظر را بنویسید.';
    } elseif ($len > 255) {
        $error = 'متن نظر نباید بیشتر از ۲۵۵ کاراکتر باشد.';
    } else {
        $stmt = mysqli_prepare($db, "INSERT INTO `comments` (`comment`) VALUES (?)");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $oldtext);
            $ok = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        } else {
            $ok = false;
        }

        if ($ok) {
            /* جلوگیری از ثبت تکراری با رفرش صفحه */
            header("Location: feedback.php?sent=1");
            exit;
        }
        $error = 'ثبت نظر انجام نشد، دوباره تلاش کنید.';
    }
}

if (isset($_GET['sent'])) {
    $notice = 'نظر شما با موفقیت ثبت شد. سپاس از همراهی شما!';
}

/* ---------- دریافت نظرات ---------- */
$comments = array();
$res = mysqli_query($db, "SELECT `id`, `comment` FROM `comments` ORDER BY `id` DESC");
if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        $comments[] = $row;
    }
}
$total = count($comments);

include "navbar.php";
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
    <title>نظرات</title>

    <style>
        :root{
            --gold:#f0c75e;
            --gold-2:#c9992b;
            --card:rgba(16,19,27,.66);
            --line:rgba(255,255,255,.14);
            --txt:#f1f4f9;
            --muted:#aab3c5;
        }

        html, body{ height:100%; }

        body.fb-page{
            background:
                linear-gradient(180deg, rgba(6,8,13,.74) 0%, rgba(6,8,13,.88) 55%, rgba(6,8,13,.96) 100%),
                url("./images/fe.jpg") center/cover fixed no-repeat;
            color:var(--txt);
            min-height:100vh;
            padding-bottom:60px;
        }

        .fb-wrap{
            width:min(940px, 92vw);
            margin:34px auto 0;
            animation:fbUp .55s ease both;
        }

        @keyframes fbUp{
            from{ opacity:0; transform:translateY(18px); }
            to  { opacity:1; transform:translateY(0); }
        }

        /* ---------- سربرگ ---------- */
        .fb-hero{
            text-align:center;
            margin-bottom:22px;
        }
        .fb-hero .fb-icon{
            width:64px; height:64px;
            margin:0 auto 14px;
            display:flex; align-items:center; justify-content:center;
            font-size:30px; line-height:1;
            border-radius:20px;
            background:linear-gradient(135deg, var(--gold), var(--gold-2));
            box-shadow:0 10px 26px rgba(201,153,43,.35);
        }
        .fb-hero h1{
            margin:0 0 8px;
            font-size:30px;
            font-weight:700;
            letter-spacing:-.2px;
        }
        .fb-hero p{
            margin:0;
            color:var(--muted);
            font-size:15px;
        }

        /* ---------- کارت فرم ---------- */
        .fb-card{
            background:var(--card);
            border:1px solid var(--line);
            border-radius:22px;
            padding:22px;
            -webkit-backdrop-filter:blur(16px) saturate(140%);
            backdrop-filter:blur(16px) saturate(140%);
            box-shadow:0 24px 60px rgba(0,0,0,.45);
        }

        .fb-input{
            width:100%;
            min-height:118px;
            resize:vertical;
            padding:16px 18px;
            border-radius:16px;
            border:1px solid var(--line);
            background:rgba(255,255,255,.05);
            color:var(--txt);
            font-family:inherit;
            font-size:16px;
            line-height:1.9;
            transition:border-color .2s, box-shadow .2s, background .2s;
            outline:none;
        }
        .fb-input::placeholder{ color:#8d97a9; }
        .fb-input:focus{
            border-color:rgba(240,199,94,.65);
            background:rgba(255,255,255,.08);
            box-shadow:0 0 0 4px rgba(240,199,94,.14);
        }

        .fb-bar{
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:12px;
            margin-top:14px;
            flex-wrap:wrap;
        }

        .fb-count{
            color:var(--muted);
            font-size:13px;
            font-variant-numeric:tabular-nums;
            transition:color .2s;
        }
        .fb-count.limit{ color:#ffb4a8; }

        .fb-btn{
            display:inline-flex;
            align-items:center;
            gap:8px;
            border:0;
            cursor:pointer;
            padding:12px 26px;
            border-radius:14px;
            font-family:inherit;
            font-size:16px;
            font-weight:700;
            color:#241a05;
            background:linear-gradient(135deg, var(--gold), var(--gold-2));
            box-shadow:0 12px 26px rgba(201,153,43,.32);
            transition:transform .18s, box-shadow .18s, filter .18s;
        }
        .fb-btn:hover{
            transform:translateY(-2px);
            filter:brightness(1.05);
            box-shadow:0 16px 32px rgba(201,153,43,.42);
        }
        .fb-btn:active{ transform:translateY(0); }
        .fb-btn span{ font-size:14px; }

        /* ---------- پیام ها ---------- */
        .fb-alert{
            display:flex;
            align-items:center;
            gap:10px;
            padding:12px 16px;
            border-radius:14px;
            font-size:14px;
            margin-bottom:16px;
            border:1px solid transparent;
            animation:fbUp .35s ease both;
        }
        .fb-alert.ok{
            background:rgba(52,199,123,.12);
            border-color:rgba(52,199,123,.4);
            color:#a8f0c6;
        }
        .fb-alert.err{
            background:rgba(255,99,99,.12);
            border-color:rgba(255,99,99,.4);
            color:#ffc0bb;
        }

        /* ---------- لیست نظرات ---------- */
        .fb-list-head{
            display:flex;
            align-items:center;
            gap:10px;
            margin:34px 4px 14px;
        }
        .fb-list-head h2{
            margin:0;
            font-size:20px;
            font-weight:700;
        }
        .fb-badge{
            padding:2px 10px;
            border-radius:999px;
            font-size:13px;
            color:#241a05;
            background:linear-gradient(135deg, var(--gold), var(--gold-2));
            font-variant-numeric:tabular-nums;
        }

        .fb-list{
            display:flex;
            flex-direction:column;
            gap:12px;
            max-height:420px;
            overflow-y:auto;
            padding:2px 2px 4px;
            scrollbar-width:thin;
            scrollbar-color:rgba(240,199,94,.5) transparent;
        }
        .fb-list::-webkit-scrollbar{ width:8px; }
        .fb-list::-webkit-scrollbar-thumb{
            background:rgba(240,199,94,.42);
            border-radius:99px;
        }
        .fb-list::-webkit-scrollbar-track{ background:transparent; }

        .fb-item{
            display:flex;
            gap:14px;
            align-items:flex-start;
            padding:16px 18px;
            border-radius:18px;
            background:rgba(255,255,255,.055);
            border:1px solid rgba(255,255,255,.09);
            transition:background .2s, border-color .2s, transform .2s;
        }
        .fb-item:hover{
            background:rgba(255,255,255,.09);
            border-color:rgba(240,199,94,.32);
            transform:translateX(-3px);
        }

        .fb-avatar{
            flex:0 0 auto;
            width:42px; height:42px;
            border-radius:14px;
            display:flex; align-items:center; justify-content:center;
            font-weight:700; font-size:17px;
            color:#241a05;
            background:linear-gradient(135deg, var(--gold), var(--gold-2));
        }

        .fb-body{ flex:1 1 auto; min-width:0; }

        .fb-meta{
            display:flex;
            align-items:center;
            gap:10px;
            margin-bottom:4px;
            font-size:12.5px;
            color:var(--muted);
        }
        .fb-meta strong{
            color:#dfe5ee;
            font-weight:700;
            font-size:14px;
        }
        .fb-id{
            color:rgba(240,199,94,.85);
            font-variant-numeric:tabular-nums;
        }

        .fb-text{
            font-size:16px;
            line-height:1.95;
            color:#e8edf5;
            overflow-wrap:anywhere;
        }

        .fb-empty{
            text-align:center;
            padding:38px 20px;
            border-radius:18px;
            border:1px dashed rgba(255,255,255,.16);
            background:rgba(255,255,255,.03);
            color:var(--muted);
        }
        .fb-empty .ico{
            font-size:34px;
            display:block;
            margin-bottom:10px;
            opacity:.85;
        }
        .fb-empty b{ color:#e8edf5; }

        @media (max-width:600px){
            .fb-wrap{ margin-top:22px; }
            .fb-hero h1{ font-size:24px; }
            .fb-card{ padding:16px; border-radius:18px; }
            .fb-item{ padding:14px; }
            .fb-btn{ width:100%; justify-content:center; }
        }
    </style>
</head>
<body class="fb-page">

<div class="fb-wrap">

    <div class="fb-hero">
        <div class="fb-icon">💬</div>
        <h1>نظرات شما</h1>
        <p>تجربه‌ات از کتابخانه را با ما به اشتراک بگذار</p>
    </div>

    <div class="fb-card">

        <?php if ($notice !== ''): ?>
            <div class="fb-alert ok"><span>✓</span><span><?php echo htmlspecialchars($notice, ENT_QUOTES, 'UTF-8'); ?></span></div>
        <?php endif; ?>

        <?php if ($error !== ''): ?>
            <div class="fb-alert err"><span>!</span><span><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></span></div>
        <?php endif; ?>

        <form method="post" action="">
            <textarea class="fb-input" name="comment" id="comment" maxlength="255"
                      placeholder="نظر خود را بنویسید…" required><?php
                echo htmlspecialchars($oldtext, ENT_QUOTES, 'UTF-8');
            ?></textarea>

            <div class="fb-bar">
                <span class="fb-count" id="countBox"><span id="count">0</span> / ۲۵۵</span>
                <button class="fb-btn" type="submit" name="submit">
                    ارسال نظر <span>➤</span>
                </button>
            </div>
        </form>

    </div>

    <div class="fb-list-head">
        <h2>آخرین نظرات</h2>
        <span class="fb-badge"><?php echo (int)$total; ?></span>
    </div>

    <?php if ($total === 0): ?>

        <div class="fb-empty">
            <span class="ico">🗒️</span>
            هنوز نظری ثبت نشده است — <b>اولین نفر باش!</b>
        </div>

    <?php else: ?>

        <div class="fb-list">
            <?php foreach ($comments as $c): ?>
                <div class="fb-item">
                    <div class="fb-avatar">ن</div>
                    <div class="fb-body">
                        <div class="fb-meta">
                            <strong>کاربر مهمان</strong>
                            <span class="fb-id">#<?php echo (int)$c['id']; ?></span>
                        </div>
                        <div class="fb-text"><?php echo nl2br(htmlspecialchars($c['comment'], ENT_QUOTES, 'UTF-8')); ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>

</div>

<script>
    (function () {
        var ta  = document.getElementById('comment');
        var out = document.getElementById('count');
        var box = document.getElementById('countBox');
        if (!ta || !out) return;

        function update() {
            out.textContent = ta.value.length;
            box.classList.toggle('limit', ta.value.length > 230);
        }

        ta.addEventListener('input', update);
        update();
    })();
</script>

</body>
</html>
