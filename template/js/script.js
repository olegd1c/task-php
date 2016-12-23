function doneAjax(clicked_id) {

	//console.log(clicked_id);
	var url_done = '/tasks/updateDone/' + clicked_id +'/1';
	//var reload_tasks = <?php include ROOT . '/views/layouts/listTask.php';?>
	//console.log(url_done);

	$.ajax({
           type: "POST",
		   url: url_done,
           data:{action:'call_this'},
           success:function(html) {
				console.log(html);
			   $("#listTask").empty();
               $("#listTask").append(html);
			   //location.reload();
			   //$( "#listTask" ).load( "ROOT .'/views/layouts/listTask.php'" );
           }
      });
 }

function addTask() {
	var url = '/tasks/add';
	//console.log($("#formAddTask").serialize());

	$.ajax({
           type: "POST",
		   url: url,
           data:$("#formAddTask").serialize(),
           success:function(html) {
			   //alert(html);
			   //console.log($("#formAddTask").serialize());
			   //console.log('addTask OK');
			   location.reload();
           }

      });


 }

function editTask(clicked_id){
	console.log(clicked_id);

	var url = "/tasks/edit/"+clicked_id;
	//console.log(url);

	$.ajax({
           type: "POST",
		   url: url,
           data:{action:'call_this'},
           success:function(html) {
			   //location.reload();
			   //$( "#listTask" ).load( "ROOT .'/views/layouts/listTask.php'" );
			   $("#block-body").empty();
               $("#block-body").append(html);

           }
      });

	//window.location.replace(url);

}

function validateForm() {
    var x = document.forms["myForm"]["fname"].value;
    if (x == "") {
        alert("Name must be filled out");
        return false;
    }
}

function checkfile(sender) {
    var validExts = new Array(".jpg", ".png", ".gif");
    var fileExt = sender.value;
    fileExt = fileExt.substring(fileExt.lastIndexOf('.'));
    if (validExts.indexOf(fileExt) < 0) {
      alert("Ошибка выбора файла, разрешены типы " +
               validExts.toString());
		sender.value ="";
      return false;
    }
	else {
		resize(sender.id);
		return true;
	}
}


function resize(id){
	var maxWidth = 320; // Max width for the image
    var maxHeight = 240;    // Max height for the image
    var ratio = 0;  // Used for aspect ratio
    var width = $(this).width();    // Current image width
    var height = $(this).height();  // Current image height

    // Check if the current width is larger than the max
    if(width > maxWidth){
        ratio = maxWidth / width;   // get ratio for scaling image
        $(this).css("width", maxWidth); // Set new width
        $(this).css("height", height * ratio);  // Scale height based on ratio
        height = height * ratio;    // Reset height to match scaled image
    }

    // Check if current height is larger than max
    if(height > maxHeight){
        ratio = maxHeight / height; // get ratio for scaling image
        $(this).css("height", maxHeight);   // Set new height
        $(this).css("width", width * ratio);    // Scale width based on ratio
        width = width * ratio;    // Reset width to match scaled image
    }

	$(this).attr("width", width);
	$(this).attr("height", height);

	var height = $(this).height();
    var width = $(this).width();
	console.log('height after: '+height);
	console.log('width after: '+width);

}


/*
function clickDone1($value){
	console.log($value);
	<?php Tasks::setChekedDone($value); var_dump($_SESSION)?>
}
*/

	function clickDone(clicked_id) {
		//console.log(clicked_id);
		//var value = $(clicked_id).val();//attr('value');

		var value = $("input[name=checkDone]:checked").attr('xmlvalue');
		var url = '/tasks/chekeddDone/'+value;

		console.log(url);

		$.ajax( {
				type: 'POST',
				url: url,
				data: '',
				success: function(data) {

					//console.log(data);
				$("#listTask").empty();
				
					
					if(data!=null){
               		$("#listTask").append(data);
				}
					}
			} );
	}


/*
$('#formAddTask').submit(function(event) {
	console.log('formAddTask mySubmit');
	resize("#file");

	event.preventDefault(); //this will prevent the default submit

  // your code here

 //$(this).unbind('submit').submit(); // continue the submit unbind preventDefault
})
*/