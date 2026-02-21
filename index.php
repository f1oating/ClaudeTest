<?php
require_once 'db.php';

session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$db = get_db();
$result = $db->query('SELECT * FROM posts ORDER BY created_at DESC');
$posts = [];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $posts[] = $row;
}
$db->close();

function h(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function time_ago(string $datetime): string {
    $dt = DateTime::createFromFormat('Y-m-d H:i:s', $datetime);
    if ($dt === false) {
        return $datetime;
    }
    $diff = time() - $dt->getTimestamp();
    if ($diff < 60) return 'только что';
    if ($diff < 3600) return floor($diff / 60) . ' мин. назад';
    if ($diff < 86400) return floor($diff / 3600) . ' ч. назад';
    return $dt->format('d M Y в H:i');
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Иван Петров | ВКонтакте</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- ======================================================
     HEADER
     ====================================================== -->
<div id="header">
    <div class="logo">В<span>К</span>онтакте</div>
    <nav id="header-nav">
        <a href="#" class="active">Моя страница</a>
        <a href="#">Новости</a>
        <a href="#">Сообщения</a>
        <a href="#">Друзья</a>
        <a href="#">Фотографии</a>
        <a href="#">Музыка</a>
        <a href="#">Видео</a>
        <a href="#">Группы</a>
    </nav>
    <div id="header-search">
        <input type="text" placeholder="Поиск по ВКонтакте">
        <button type="button">Найти</button>
    </div>
    <div id="header-user">
        <div style="width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,#b0c8e0,#d4e4f4);border:2px solid rgba(255,255,255,0.4);display:flex;align-items:center;justify-content:center;font-size:14px;">👤</div>
        Иван Петров
    </div>
</div>

<!-- ======================================================
     MAIN PAGE
     ====================================================== -->
<div id="page">

    <!-- ---- LEFT SIDEBAR --------------------------------- -->
    <div id="sidebar-left">
        <!-- Avatar + name -->
        <div class="profile-box">
            <div class="avatar-placeholder"></div>
            <div class="profile-name">Иван Петров</div>
            <div class="profile-status">
                <span class="online-badge"></span>онлайн
            </div>
        </div>

        <!-- Navigation -->
        <nav class="sidebar-nav">
            <a href="#" class="active">Моя страница</a>
            <a href="#">Новости</a>
            <a href="#">
                Сообщения
                <span class="nav-count">3</span>
            </a>
            <a href="#">
                Друзья
                <span class="nav-count">12</span>
            </a>
            <a href="#">Фотографии</a>
            <a href="#">Музыка</a>
            <a href="#">Видео</a>
            <a href="#">Группы</a>
            <a href="#">Настройки</a>
            <a href="#">Выйти</a>
        </nav>
    </div>

    <!-- ---- CENTER CONTENT ------------------------------- -->
    <div id="content">
        <!-- Profile header strip -->
        <div class="profile-header-strip">
            <div>
                <div class="big-name">Иван Петров</div>
                <div class="info-row">
                    Город: <span>Москва</span> &nbsp;·&nbsp;
                    Возраст: <span>23 года</span> &nbsp;·&nbsp;
                    ВУЗ: <span>МГУ</span>
                </div>
            </div>
            <div class="profile-actions">
                <button class="btn btn-blue">Добавить в друзья</button>
                <button class="btn btn-gray">Написать сообщение</button>
            </div>
        </div>

        <!-- Tabs -->
        <div class="tabs">
            <a href="#" class="active">Стена</a>
            <a href="#">Информация</a>
            <a href="#">Фотографии</a>
            <a href="#">Видео</a>
            <a href="#">Аудиозаписи</a>
            <a href="#">Заметки</a>
        </div>

        <!-- Wall block -->
        <div class="block">
            <div class="block-title">
                Стена
                <a href="#">все записи</a>
            </div>

            <!-- Write on wall form -->
            <div class="wall-form">
                <form action="post_wall.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= h($_SESSION['csrf_token']) ?>">
                    <textarea name="text" placeholder="Напишите что-нибудь на стене..."></textarea>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-blue">Отправить</button>
                    </div>
                </form>
            </div>

            <!-- Posts -->
            <?php if (empty($posts)): ?>
                <div class="wall-empty">На стене пока нет записей</div>
            <?php else: ?>
                <?php foreach ($posts as $post): ?>
                <div class="wall-post">
                    <div class="post-avatar">👤</div>
                    <div class="post-body">
                        <div class="post-author"><?= h($post['author']) ?></div>
                        <div class="post-text"><?= nl2br(h($post['text'])) ?></div>
                        <div class="post-meta">
                            <span><?= h(time_ago($post['created_at'])) ?></span>
                            <a href="#">Комментировать</a>
                            <a href="#">Мне нравится</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- ---- RIGHT SIDEBAR -------------------------------- -->
    <div id="sidebar-right">

        <!-- Friends -->
        <div class="mini-block">
            <div class="mini-block-title">
                Друзья (47)
                <a href="#">все</a>
            </div>
            <div class="friends-list">
                <?php
                $friends = [
                    ['👩', 'Анна'],['👨', 'Сергей'],['👩', 'Мария'],
                    ['👦', 'Алёша'],['👧', 'Катя'],  ['👨', 'Дима'],
                ];
                foreach ($friends as $f): ?>
                <div class="friend-item">
                    <div class="friend-avatar"><?= $f[0] ?></div>
                    <div class="friend-name"><?= $f[1] ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Photos -->
        <div class="mini-block">
            <div class="mini-block-title">
                Фотографии
                <a href="#">все</a>
            </div>
            <div class="photos-grid">
                <div class="photo-thumb">🌅</div>
                <div class="photo-thumb">🏙️</div>
                <div class="photo-thumb">🌊</div>
                <div class="photo-thumb">🌿</div>
                <div class="photo-thumb">🎉</div>
                <div class="photo-thumb">🐾</div>
            </div>
        </div>

        <!-- Groups -->
        <div class="mini-block">
            <div class="mini-block-title">
                Группы (8)
                <a href="#">все</a>
            </div>
            <div class="groups-list">
                <?php
                $groups = [
                    ['🎵', 'Музыка без границ'],
                    ['📚', 'Книги и чтение'],
                    ['⚽', 'Спорт и здоровье'],
                ];
                foreach ($groups as $g): ?>
                <div class="group-item">
                    <div class="group-icon"><?= $g[0] ?></div>
                    <div class="group-name"><?= $g[1] ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div><!-- #sidebar-right -->

</div><!-- #page -->

<div id="footer">
    &copy; 2008–<?= date('Y') ?> ВКонтакте &nbsp;·&nbsp;
    <a href="#">Помощь</a> &nbsp;·&nbsp;
    <a href="#">Правила</a> &nbsp;·&nbsp;
    <a href="#">Реклама</a> &nbsp;·&nbsp;
    <a href="#">Разработчикам</a>
</div>

</body>
</html>
