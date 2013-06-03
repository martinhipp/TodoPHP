<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Todo</title>
	<link rel="stylesheet" href="<?php echo url('/css/master.css', false) ?>">
	<script src="<?php echo url('/js/jquery-2.0.1.min.js', false) ?>"></script>
</head>
<body>
	<div id="page">
		<h1>Todo</h1>
		<form id="todo" action="<?php echo url('/add') ?>" method="post">
			<input type="text" id="title" name="title" maxlength="50" placeholder="What needs to be done?" autofocus>
		</form>
		<?php if (count($todos)): ?>
			<ol id="todo-list">
				<?php foreach ($todos as $todo): ?>
					<li<?php echo $todo->completed_at ? ' class="done"' : '' ?>>
						<form method="post">
							<input type="hidden" name="id" value="<?php echo $todo->id ?>">
							<input type="checkbox" class="complete"<?php echo $todo->completed_at ? ' checked disabled' : '' ?>>
							<label><?php echo html($todo->title) ?></label>
							<a class="remove"></a>
						</form>
					</li>
				<?php endforeach ?>
			</ol>
			<a href="<?php echo url('/reset') ?>" id="reset">Reset (<?php echo count($todos) ?>)</a>
		<?php endif ?>
	</div>
	<script>
		$(function() {
			var completeUrl = '<?php echo url('/complete') ?>'
			var removeUrl = '<?php echo url('/remove') ?>'

			$('#title').on('keydown', function(e) {
				if (e.keyCode == 13 && $(this).val().length == 0) e.preventDefault()
			})

			$('.complete').on('click', function(e) {
				$(this).parents('form').first().attr('action', completeUrl).submit()
			})

			$('.remove').on('click', function(e) {
				$(this).parents('form').first().attr('action', removeUrl).submit()
			})
		});
	</script>
</body>
</html>
