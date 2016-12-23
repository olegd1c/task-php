<?php include ROOT . '/views/layouts/header.php';
?>
<section>
    <div class="container">
        <div class="row">
			<div class="list-group">
    		<h4 class="list-group-item-heading"><?php echo $taskItem['description'];
?></h4>
			<img src=<?php echo $taskItem['image'];
?> alt="Mountain View" style="width:320px;height:240px;">
			<p><input type="checkbox" value=<?php echo $taskItem['image'];
?> > </p>
			</div>
        </div>
    </div>
</section>
<?php include ROOT . '/views/layouts/footer.php';
?>