<html>
	<head>
	</head>

	<body>
		<h1>MENU</h1>
		<?=$game->getMenu()->showMenu()?>
		<br/>

		<h1>SCORE</h1>
		Pontuação: <?=$game->getScore()->getPoints()?>
		<br/>

		<h1>GOAL</h1>
		Seu objetivo é: <?=$game->getGoal()->getDescription()?>

		<?php foreach((array)$game->getGoal()->getSteps() as $step): ?>

			<p>
			<?php if ($step['accomplished']): ?>
				<strike><?=$step['step']?></strike>
			<?php else: ?>
				<?=$step['step']?>
			<?php endif; ?>
			</p>
		<?php endforeach; ?>
		<br/>
		Total Concluído: <?=$game->getGoal()->getPercentage()?>%

	</body>
</html>