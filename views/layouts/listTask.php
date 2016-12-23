<div class="list-group">
<h2>Список задач</h2>
<p><b>Фильтр:</b><Br>
	<input type="radio" name="checkDone" value="0" data-value="0" id="checkDone_1" xmltag="CustomerType" xmlvalue="0" <?php if(!Tasks::isCheckDone()) echo "checked";
?> onclick="clickDone(this.id)"> Все
    <input type="radio" name="checkDone" value="1" data-value="1" id="checkDone_2" xmltag="CustomerType" xmlvalue="1" <?php if(Tasks::isCheckDone()) echo "checked";
?> onclick="clickDone(this.id)"> Выполненные </p>
    <?php foreach ($tasksList as $taskItem): ?>
    <div class="container">
        <div class="row top-buffer">
            <div class="col-lg-4 col-xs-4 col-sm-4 col-md-4">
                <a href="/tasks/<?php echo $taskItem['id'];
?>">
                    <img class="pull-left img-circle" src=<?php echo $taskItem['image'];
?> alt="Mountain View"
                    style="width:320px;height:240px;" >
                </a>
            </div>
            <div class="col-lg-8 col-xs-8 col-sm-8 col-md-8">
                <a href="/tasks/<?php echo $taskItem['id'];
?>">
                    <h4 class="list-group-item-heading text-left"><?php echo $taskItem['description'];
?></h4>
                    <span>Статус: <?php echo $taskItem['status'];
?></span>
                </a>
                <?php if (User::isAdmin() && $taskItem['done']!="1"): ?>
					<br><button name = "task" type="button" data-id = <?php echo $taskItem['id'];
?> id =<?php echo $taskItem['id'];
?> class="btn btn-success" onclick = "doneAjax(this.id)">Выполнить</button>
						<button type="button" id = <?php echo $taskItem['id'];
?> class="btn btn-info" onclick ="editTask(this.id)">Редактировать</button>
				<?php endif;
?>
            </div>
        </div>
    </div>
<?php endforeach;
?>
		<div class="row">
    		<div class="span11 offset1">
    			<div class="pagination pagination-small">
					<?php echo $pages;
?>
				</div>
    		</div>
    	</div>
    </div>