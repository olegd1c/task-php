<h2>Список задач</h2>
<div class="list-group">
	<?php foreach ($tasksList  as $taskItem) : ?>
		<a href="/tasks/view/<?php echo $taskItem['id'] ?>" class="list-group-item">
		<h4 class="list-group-item-heading"><?php echo $taskItem['description'] ?></h4>
		</a>
	<?php endforeach;
?>
</div>