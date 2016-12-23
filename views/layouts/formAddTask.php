<form action="<?php echo '/tasks/add';
?>" method="post" id="formAddTask" enctype="multipart/form-data">
  <p>
  <label for="description">Описание</label>
  <input type="text" name="description" id="description" placeholder = "описание задачи" value = "<?php echo $currentTask['description'] ?>">
  </p>
  <label for="file">Выберите файлы для загрузки:</label><br>
  <input class="btn btn-primary top-buffer" type="file" style="height:40px" id="file" name="myfile[]" accept=".jpg,.gif,.png"  onchange="checkfile(this);" value = "<?php echo $currentTask['description'] ?>">
  <p>
  <input class="btn btn-success submit" type="submit" id="submit" value="Отправить">
  </p>
  <input type="hidden" name="user" id="user" class="descr" value= "<?php echo $currentTask['user'] ?>">
  <input type="hidden" name="id" id="id" class="descr" value= "<?php echo $currentTask['id'] ?>">
</form>