<?php
header('Cache-control: private, max-age=0');

// СЕгодня среда?
$day = date('DD');
if($day = "Wednesday"){
    $return = "<p>It is ".date('D').", my dudes</p>";
}
//var_dump($_SERVER);
//var_dump($_GET);
$users = ['Кефир','Сыр','Камень','Пиво','Рыба'];
?>

<div>
    <?php if ($day = "Wed") : ?>
    It is Wednesday, my dudes
    <?php endif; ?>
    <ul>
        <?php foreach ($users as $user) : ?>
            <li><?= htmlspecialchars($user) ?></li>
        <?php endforeach; ?>
    </ul>
</div>