<?php
header('Cache-control: private, max-age=0');
$day = date('DD');
//var_dump($_SERVER);
//echo ($_COOKIE["id8797979779"]);
//setcookie("id8797979779", 'Username', time()+3600*24);  // срок действия 24 часa
//var_dump($_SESSION);
?>
<h1>Main page</h1>
<a href="/users">Страница пользователей</a>
<br><a href="/courses">Страница курсов</a>
<div>
    <?php if ($day == "Wed") : ?>
    It is Wednesday, my dudes
    <?php endif; ?>
</div>
