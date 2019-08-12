<h1>Notes</h1>

<form method="post" action="<?php echo URL . 'note/create' ?>">
	<label>Title</label><input type="text" name="title"/><br>
	<label>Content</label><textarea name="content"></textarea><br>
	<label>&nbsp;</label><input type="submit"/>
</form>

<table>
<?php
foreach($this->noteList as $key => $value){
	echo '<tr>'
			. '<td>' . $value['title'] . '</td>'
			. '<td>' . $value['date_added'] . '</td>'
			. '<td>'
				. '<a href="' . URL . 'note/edit/' . $value['noteid'] . '">Edit</a> - '
				. '<a class="delete" href="' . URL . 'note/delete/' . $value['noteid'] . '">Delete</a></td>'
			. '<tr>';
}
?>
</table>

<script>
$(function(){
	$('.delete').on('click', function(e){
		var c = confirm("are you sure you want to delete?");
		if(c === false){
			return false; //stop delete action!
		}
	});
});
</script>