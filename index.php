<?php
header('Cache-control: private, max-age=0');
$day = date('DD');
//var_dump($_SERVER);
//var_dump($_GET);  ?>
<h1>Main page</h1>
<a href="/users">Страница пользователей</a>
<br><a href="/courses">Страница курсов</a>
<div>
    <?php if ($day == "Wed") : ?>
    It is Wednesday, my dudes
    <?php endif; ?>

</div>