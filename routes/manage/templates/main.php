<!DOCTYPE html>
<html class="no-js" lang="ru"> 
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Админка</title>
	<link rel="dns-prefetch" href="//fonts.googleapis.com">

	<meta name="viewport" content="width=660" />

	<link href='//fonts.googleapis.com/css?family=Roboto+Condensed:300italic,400italic,400,300&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="//thisorthat.ru/assets/css/normalize.css">
	<link rel="stylesheet" href="//thisorthat.ru/manage/assets/css/style.css?01">

</head>
<body>
	<section class="main">
<?php
	$items = $this->get_new_items(150);
    $count = $this->get_count();

	if(count($items) < 1) :
?>
	<h2>Список вопросов пуст</h2>
<?php
	else :
?>
	<h2>Осталось вопросов: <?= $count; ?></h2> 
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
			<button class="decline" data-status="2">Отклонить</button> 
			<button class="remove" data-status="3">Удалить</button>
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
