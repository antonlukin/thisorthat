<!DOCTYPE html>
<html class="no-js" lang="ru"> 
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>VIP Админка</title>
	<link rel="dns-prefetch" href="//fonts.googleapis.com">

	<meta name="viewport" content="width=660" />

	<link href='//fonts.googleapis.com/css?family=Roboto+Condensed:300italic,400italic,400,300&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="//thisorthat.ru/assets/css/normalize.css">
	<link rel="stylesheet" href="//thisorthat.ru/manage/assets/css/style.css?01">

</head>
<body class="vip">
	<section class="main">
<?php
	$items = $this->get_vip_items(150);
    $count = $this->get_vip_count();

	if(count($items) < 1) :
?>
	<h2>Список вопросов пуст</h2>
<?php
	else :
?>
	<h2>VIP админка: <?= $count; ?></h2> 
<?php
	endif;


	foreach($items as $item) :
?>
	<div class="q" data-id="<?= $item['id'] ?>">
		<p>Добавил пользователь: <strong><?= $item['user'] ?></strong></p>
		<div>
			<textarea class="left"><?= $item['left_text'] ?></textarea>
			<textarea class="right"><?= $item['right_text'] ?></textarea>
		</div>
		
		<div class="buttons">
			<button class="approve" data-status="1">Добавить</button>
			<button class="decline" data-status="2">Без причины</button> 
			<button class="remove" data-status="-1">Удалить</button>
		</div>
		<div class="buttons">
			<button class="decline" data-status="3">Клон</button>
			<button class="decline" data-status="4">Малоизвестный факт</button>
			<button class="decline" data-status="5">Цензура</button> 
		</div> 
	</div>	
<?php
	endforeach; 
?>
	</section>
	<script src="//thisorthat.ru/assets/js/jquery.min.js"></script>
	<script src="//thisorthat.ru/manage/assets/js/main.js"></script>
</body>
</html>
