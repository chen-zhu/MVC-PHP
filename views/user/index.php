<h1>User</h1>

<form method="post" action="<?php echo URL . 'user/create' ?>">
	<label>Login</label><input type="text" name="login"/><br>
	<label>Password</label><input type="password" name="password"/><br>
	<label>Role</label>
	<select id="role" name="role">
		<option valie="defualt">Default</option>
		<option value="admin">Admin</option>
	</select><br>
	<label>&nbsp;</label><input type="submit"/>
</form>

<table>
<?php
foreach($this->userList as $key => $value){
	echo '<tr>'
			. '<td>' . $value['id'] . '</td>'
			. '<td>' . $value['login'] . '</td>'
			. '<td>' . $value['role'] . '</td>'
			. '<td>'
				. '<a href="' . URL . 'user/edit/' . $value['id'] . '">Edit</a> - '
				. '<a href="' . URL . 'user/delete/' . $value['id'] . '">Delete</a></td>'
			. '<tr>';
}
?>
</table>