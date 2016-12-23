<?php include ROOT . '/views/layouts/header.php';
?>
<section>
<div class="container">
       <div class="row">
		<div id="block-body"> 
		 <?php if (User::isGuest()): ?>
		   <div class="col-sm-12">
			   <h4> Авторизируйтесь пожалуйста </h4>
		   </div>
		<?php else: ?>
           <div class="col-sm-3">
			   <h2>Новая задача</h2>
			   <div id="formAddTask">
				   <?php $currentTask = Tasks::defTask();
include ROOT . '/views/layouts/formAddTask.php';
?>
				</div>
			</div>
	  		<div class="col-sm-9">
				<div id="listTask">
					<?php include ROOT . '/views/layouts/listTask.php';
?>
			    </div>
		<?php endif;
?>
				</div>
	   </div>
  </div>
</div>
</section>
<?php include ROOT . '/views/layouts/footer.php';
?>