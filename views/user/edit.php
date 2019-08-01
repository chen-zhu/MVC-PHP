<h1>User: Edit Info</h1>

<form method="post" action="<?php echo URL . 'user/editSave/' . $this->user['id']; ?>">
	<label>Login</label><input type="text" name="login" value="<?php echo $this->user['login']; ?>"/><br>
	<label>Password</label><input type="password" name="password"/><br>
	<label>Role</label>
	<select id="role" name="role">
		<option valie="defualt" <?php echo $this->user['role'] == 'default' ? 'selected' : ''; ?>>Default</option>
		<option value="admin" <?php echo $this->user['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
		<option value="owner" <?php echo $this->user['role'] == 'owner' ? 'selected' : ''; ?>>Owner</option>
	</select><br>
	<label>&nbsp;</label><input type="submit"/>
</form>
