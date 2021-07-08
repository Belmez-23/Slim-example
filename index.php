<?php
header('Cache-control: private, max-age=0');
$day = date('DD');
//var_dump($_SERVER);
//var_dump($_GET);
$users = ['Кефир','Сыр','Камень','Пиво','Рыба'];
?>

<div>
    <?php if ($day == "Wed") : ?>
    It is Wednesday, my dudes
    <?php endif; ?>
    <ul>
        <?php foreach ($users as $user) : ?>
            <li><?= htmlspecialchars($user) ?></li>
        <?php endforeach; ?>
    </ul>
</div>