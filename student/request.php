<?php
header('Content-Type: text/html; charset=utf-8');

include "connection.php";
session_start();

if (!isset($_SESSION['login_user'])) {
    header("Location: student_login.php");
    exit;
}

$login = (string)$_SESSION['login_user'];

/* ---------- درخواست‌های دانشجو ---------- */
$stmt = mysqli_prepare(
    $db,
    "SELECT i.`bid`, i.`approve`, i.`issue`, i.`return`,
            b.`name` AS book_name, b.`authors` AS book_authors
       FROM `issue_book` i
       LEFT JOIN `books` b ON b.`bid` = i.`bid`
      WHERE i.`username` = ?
      ORDER BY i.`approve` DESC, i.`bid` DESC"
);
mysqli_stmt_bind_param($stmt, "s", $login);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

$requests = array();
while ($res && ($row = mysqli_fetch_assoc($res))) {
    $requests[] = $row;
}
mysqli_stmt_close($stmt);

/* ---------- آمار ---------- */
$pending = 0;
$onLoan  = 0;
$done    = 0;

foreach ($requests as $r) {
    $ret = trim((string)$r['return']);
    if ($ret !== '') {
        $done++;
    } elseif ($r['approve'] === 'Yes') {
        $onLoan++;
    } elseif ($r['approve'] !== 'No') {
        $pending++;
    }
}

$total = count($requests);
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/styles.css">
    <title>درخواست‌های من</title>

    <style>
        :root{
            --gold:#f0c75e;
            --gold-2:#c9992b;
            --bg:#0b0e14;
            --card:rgba(18,22,31,.75);
            --line:rgba(255,255,255,.12);
            --txt:#eef2f8;
            --muted:#a5aec0;
        }

        *{ box-sizing:border-box; }
        html, body{ margin:0; padding:0; }

        body.rq-page{
            background:
                radial-gradient(1100px 520px at 85% -10%, rgba(240,199,94,.10), transparent 60%),
                radial-gradient(900px 480px at 10% 110%, rgba(59,227,196,.07), transparent 60%),
                var(--bg);
            color:var(--txt);
            min-height:100vh;
            padding-bottom:60px;
        }

        /* ================= هدر ================= */
        .rq-top{
            position:sticky;
            top:0;
            z-index:50;
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:18px;
            padding:12px 26px;
            background:rgba(9,12,17,.78);
            border-bottom:1px solid var(--line);
            -webkit-backdrop-filter:blur(14px) saturate(140%);
            backdrop-filter:blur(14px) saturate(140%);
            flex-wrap:wrap;
        }
        .rq-brand{ display:flex; align-items:center; gap:11px; text-decoration:none; color:var(--txt); }
        .rq-brand img{
            width:44px; height:44px;
            border-radius:13px;
            object-fit:cover;
            border:1px solid rgba(240,199,94,.45);
            box-shadow:0 6px 18px rgba(0,0,0,.45);
        }
        .rq-brand b{ display:block; font-size:16px; line-height:1.3; }
        .rq-brand small{ color:var(--muted); font-size:11.5px; }

        .rq-nav{ display:flex; align-items:center; gap:4px; flex-wrap:wrap; }
        .rq-nav a{
            padding:9px 14px;
            border-radius:11px;
            color:#cfd6e3;
            font-size:14px;
            text-decoration:none;
            transition:background .2s, color .2s;
        }
        .rq-nav a:hover{ background:rgba(255,255,255,.07); color:#fff; text-decoration:none; }
        .rq-nav a.on{
            background:rgba(240,199,94,.13);
            color:#fff;
            box-shadow:inset 0 0 0 1px rgba(240,199,94,.35);
            text-decoration:none;
        }

        .rq-ghost{
            display:inline-flex;
            align-items:center;
            gap:7px;
            padding:9px 16px;
            border-radius:11px;
            border:1px solid var(--line);
            background:rgba(255,255,255,.05);
            color:#e6ebf3;
            font-size:13.5px;
            text-decoration:none;
            transition:background .2s, border-color .2s, color .2s;
        }
        .rq-ghost:hover{
            background:rgba(255,99,99,.16);
            border-color:rgba(255,99,99,.4);
            color:#fff;
            text-decoration:none;
        }

        /* ================= محتوا ================= */
        .rq-wrap{
            width:min(920px, 94vw);
            margin:30px auto 0;
        }

        .rq-head{
            display:flex;
            align-items:flex-end;
            justify-content:space-between;
            gap:14px;
            flex-wrap:wrap;
            margin-bottom:18px;
        }
        .rq-head h1{ margin:0; font-size:26px; }
        .rq-head p{ margin:7px 0 0; color:var(--muted); font-size:13.5px; }

        .rq-btn{
            display:inline-flex;
            align-items:center;
            gap:8px;
            padding:12px 22px;
            border-radius:13px;
            font-family:inherit;
            font-size:14.5px;
            font-weight:700;
            border:0;
            cursor:pointer;
            text-decoration:none;
            transition:transform .18s, box-shadow .18s, background .2s, filter .18s, color .2s;
        }
        .rq-btn.gold{
            color:#241a05;
            background:linear-gradient(135deg, var(--gold), var(--gold-2));
            box-shadow:0 12px 26px rgba(201,153,43,.32);
        }
        .rq-btn.gold:hover{
            transform:translateY(-2px);
            filter:brightness(1.05);
            color:#241a05;
            text-decoration:none;
        }
        .rq-btn.line{
            color:#e6ebf3;
            background:rgba(255,255,255,.05);
            border:1px solid var(--line);
        }
        .rq-btn.line:hover{ background:rgba(255,255,255,.12); color:#fff; text-decoration:none; }

        /* آمار */
        .rq-stats{
            display:grid;
            grid-template-columns:repeat(4, 1fr);
            gap:12px;
            margin-bottom:18px;
        }
        .rq-stat{
            display:flex;
            align-items:center;
            gap:12px;
            padding:14px 16px;
            border-radius:16px;
            background:var(--card);
            border:1px solid rgba(255,255,255,.09);
        }
        .rq-stat .ico{
            width:40px; height:40px;
            display:flex; align-items:center; justify-content:center;
            border-radius:12px;
            font-size:18px;
            background:rgba(240,199,94,.12);
            border:1px solid rgba(240,199,94,.25);
        }
        .rq-stat .n{
            font-size:21px;
            font-weight:800;
            line-height:1.2;
            font-variant-numeric:tabular-nums;
        }
        .rq-stat .t{ font-size:12px; color:var(--muted); }

        /* لیست */
        .rq-list{ display:flex; flex-direction:column; gap:12px; }

        .rq-item{
            display:flex;
            align-items:center;
            gap:16px;
            padding:16px 18px;
            border-radius:18px;
            background:var(--card);
            border:1px solid rgba(255,255,255,.09);
            transition:background .2s, border-color .2s, transform .2s;
        }
        .rq-item:hover{
            background:rgba(255,255,255,.075);
            border-color:rgba(240,199,94,.3);
            transform:translateY(-2px);
        }

        .rq-ico{
            flex:0 0 auto;
            width:46px; height:46px;
            display:flex; align-items:center; justify-content:center;
            border-radius:14px;
            font-size:20px;
            background:rgba(255,255,255,.06);
            border:1px solid var(--line);
        }

        .rq-main{ flex:1 1 auto; min-width:0; }
        .rq-title{
            font-size:15.5px;
            font-weight:700;
            color:#fff;
            margin-bottom:4px;
            overflow:hidden;
            text-overflow:ellipsis;
        }
        .rq-meta{
            display:flex;
            align-items:center;
            gap:12px;
            flex-wrap:wrap;
            font-size:12.5px;
            color:var(--muted);
        }
        .rq-meta .sep{ opacity:.4; }

        .rq-right{
            flex:0 0 auto;
            display:flex;
            flex-direction:column;
            align-items:flex-end;
            gap:6px;
        }

        .rq-badge{
            display:inline-flex;
            align-items:center;
            gap:6px;
            padding:5px 12px;
            border-radius:999px;
            font-size:12.5px;
            font-weight:700;
            white-space:nowrap;
        }
        .rq-badge.wait{ color:#ffe3a1; background:rgba(240,199,94,.13); border:1px solid rgba(240,199,94,.35); }
        .rq-badge.loan{ color:#a8f0c6; background:rgba(52,199,123,.13); border:1px solid rgba(52,199,123,.35); }
        .rq-badge.done{ color:#c8d3e4; background:rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.16); }
        .rq-badge.rej{ color:#ffc0bb; background:rgba(255,99,99,.13); border:1px solid rgba(255,99,99,.35); }

        .rq-date{ font-size:11.5px; color:#8f9aac; direction:ltr; }

        /* خالی */
        .rq-empty{
            text-align:center;
            padding:56px 22px;
            border-radius:20px;
            border:1px dashed rgba(255,255,255,.16);
            background:rgba(255,255,255,.03);
            color:var(--muted);
        }
        .rq-empty .ico{ font-size:40px; display:block; margin-bottom:12px; opacity:.85; }
        .rq-empty b{ color:#e8edf5; display:block; font-size:17px; margin-bottom:6px; }
        .rq-empty p{ margin:0 0 18px; font-size:13.5px; }

        @media (max-width:640px){
            .rq-top{ padding:10px 14px; gap:10px; }
            .rq-nav{ order:3; width:100%; }
            .rq-stats{ grid-template-columns:1fr; }
            .rq-item{ flex-wrap:wrap; }
            .rq-right{ width:100%; align-items:flex-start; }
            .rq-head h1{ font-size:21px; }
            .rq-btn{ width:100%; justify-content:center; }
        }
    </style>
</head>
<body class="rq-page">

<header class="rq-top">
    <a class="rq-brand" href="index.php">
        <img src="./images/logo.jpg" alt="لوگو">
        <span>
            <b>کتابخانهٔ آنلاین</b>
            <small>پنل دانشجو</small>
        </span>
    </a>

    <nav class="rq-nav">
        <a href="index.php">خانه</a>
        <a href="books.php">کتاب‌ها</a>
        <a class="on" href="request.php">درخواست‌ها</a>
        <a href="feedback.php">نظرات</a>
        <a href="profile.php">پروفایل</a>
    </nav>

    <a class="rq-ghost" href="logout.php">خروج</a>
</header>

<div class="rq-wrap">

    <div class="rq-head">
        <div>
            <h1>درخواست‌های من</h1>
            <p>وضعیت درخواست‌های امانت کتاب‌هایتان را اینجا ببینید.</p>
        </div>
        <a class="rq-btn gold" href="books.php">＋ درخواست کتاب جدید</a>
    </div>

    <div class="rq-stats">
        <div class="rq-stat">
            <div class="ico">⏳</div>
            <div>
                <div class="n"><?php echo (int)$pending; ?></div>
                <div class="t">در انتظار تأیید</div>
            </div>
        </div>

        <div class="rq-stat">
            <div class="ico">📖</div>
            <div>
                <div class="n"><?php echo (int)$onLoan; ?></div>
                <div class="t">در امانت</div>
            </div>
        </div>

        <div class="rq-stat">
            <div class="ico">✅</div>
            <div>
                <div class="n"><?php echo (int)$done; ?></div>
                <div class="t">تحویل داده شده</div>
            </div>
        </div>

        <div class="rq-stat">
            <div class="ico">📚</div>
            <div>
                <div class="n"><?php echo (int)$total; ?></div>
                <div class="t">کل درخواست‌ها</div>
            </div>
        </div>
    </div>

    <?php if ($total === 0): ?>

        <div class="rq-empty">
            <span class="ico">📭</span>
            <b>هنوز درخواستی ثبت نکرده‌اید</b>
            <p>از صفحهٔ کتاب‌ها می‌توانید کتاب موردنظرتان را درخواست کنید.</p>
            <a class="rq-btn gold" href="books.php">مشاهدهٔ کتاب‌ها</a>
        </div>

    <?php else: ?>

        <div class="rq-list">
            <?php foreach ($requests as $r): ?>
                <?php
                    $ret    = trim((string)$r['return']);
                    $issue  = trim((string)$r['issue']);
                    $status = 'wait';
                    $label  = 'در انتظار تأیید';

                    if ($ret !== '') {
                        $status = 'done';
                        $label  = 'تحویل داده شده';
                    } elseif ($r['approve'] === 'Yes') {
                        $status = 'loan';
                        $label  = 'در امانت';
                    } elseif ($r['approve'] === 'No') {
                        $status = 'rej';
                        $label  = 'رد شده';
                    }

                    $bookName = $r['book_name'] !== null ? $r['book_name'] : 'کتاب حذف‌شده';
                ?>
                <div class="rq-item">
                    <div class="rq-ico">📕</div>

                    <div class="rq-main">
                        <div class="rq-title"><?php echo htmlspecialchars($bookName, ENT_QUOTES, 'UTF-8'); ?></div>
                        <div class="rq-meta">
                            <span>شناسه: #<?php echo (int)$r['bid']; ?></span>
                            <?php if (!empty($r['book_authors'])): ?>
                                <span class="sep">•</span>
                                <span><?php echo htmlspecialchars($r['book_authors'], ENT_QUOTES, 'UTF-8'); ?></span>
                            <?php endif; ?>
                            <span class="sep">•</span>
                            <span>تاریخ امانت: <span class="rq-date"><?php echo $issue !== '' ? htmlspecialchars($issue, ENT_QUOTES, 'UTF-8') : '—'; ?></span></span>
                            <span class="sep">•</span>
                            <span>تاریخ بازگشت: <span class="rq-date"><?php echo $ret !== '' ? htmlspecialchars($ret, ENT_QUOTES, 'UTF-8') : '—'; ?></span></span>
                        </div>
                    </div>

                    <div class="rq-right">
                        <span class="rq-badge <?php echo $status; ?>">● <?php echo $label; ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>

</div>

</body>
</html>
