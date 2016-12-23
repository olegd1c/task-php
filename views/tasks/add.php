<form action="upload.php" method="post" id="my_form" enctype="multipart/form-data">
  <p>
    <label for="description">Описание</label>
      <input type="text" name="description" id="description" placeholder = "описание задачи">
  </p>
  <label for="avatar">Выберите файлы для загрузки:</label><br>
    <input type="file" name="myfile[]">
  <p>
  	<input type="submit" id="submit" value="Отправить">
  </p>
</form>