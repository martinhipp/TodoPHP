<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Todo</title>
	<link rel="stylesheet" href="<?php echo url('/css/master.css', false) ?>">
</head>
<body data-url="<?php echo url() ?>">
	<div id="page">
		<h1>Todo List</h1>
		<input type="text" id="title" name="title" maxlength="50" placeholder="What needs to be done?" autofocus>
		<ol id="todo-list">
			<?php foreach ($todos as $todo): ?>
				<li data-id="<?php echo $todo->id ?>" <?php echo $todo->completed ? 'class="completed"' : '' ?>>
					<input type="checkbox" class="toggle" <?php echo $todo->completed ? 'checked' : '' ?>>
					<label><?php echo html($todo->title) ?></label>
					<a class="remove">&times;</a>
				</li>
			<?php endforeach ?>
		</ol>
		<a id="clear-completed">Clear <span class="count"><?php echo $completed ?></span> completed item(s)</a>
	</div>
	<script type="text/template" id="todo-template">
		<input type="checkbox" class="toggle">
		<label><%- title %></label>
		<a class="remove"></a>
	</script>
	<script src="<?php echo url('/js/zepto.min.js', false) ?>"></script>
	<script src="<?php echo url('/js/underscore.min.js', false) ?>"></script>
	<script src="<?php echo url('/js/todos.js', false) ?>"></script>
</body>
</html>
