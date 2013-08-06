$(function() {
	var url = $('body').data('url');
	var template = _.template($('#todo-template').html());

	function getCompletedTodoCount() {
		return $('li.completed').length;
	}

	function updateCompletedTodoCount() {
		var completed = getCompletedTodoCount();
		$('#clear-completed .count').text(completed);
	}

	$('#title').keypress(function(e) {
		var $this = $(this), title = $this.val();

		if (e.which === 13 && title.length > 0) {
			$.ajax({
				type: 'post',
				url: url + '/add',
				data: {title: title},
				success: function(response) {
					if (response.success) {
						$('<li>').data('id', response.id).html(template({title: title})).appendTo('#todo-list');

						$this.val('').blur();
					}
				},
				dataType: 'json'
			});
		}
	});

	$('#todo-list').on('click', 'li .toggle', function() {
		var $this = $(this), $todo = $this.parent('li'), id = $todo.data('id');

		$.ajax({
			type: 'post',
			url: url + '/toggle',
			data: {id: id},
			success: function(response) {
				if (response.success) {
					if ($todo.hasClass('completed')) {
						$todo.removeClass('completed');
					} else {
						$todo.addClass('completed');
					}

					updateCompletedTodoCount();
				}
			},
			dataType: 'json'
		});
	}).on('click', 'li .remove', function() {
		var $todo = $(this).parent('li'), id = $todo.data('id');

		$.ajax({
			type: 'post',
			url: url + '/remove',
			data: {id: id},
			success: function(response) {
				if (response.success) {
					$todo.remove();

					updateCompletedTodoCount();
				}
			},
			dataType: 'json'
		});
	});

	$('#clear-completed').click(function() {
		if (getCompletedTodoCount()) {
			$.ajax({
				url: url + '/clear',
				success: function(response) {
					if (response.success) {
						$('li.completed').remove();

						updateCompletedTodoCount();
					}
				},
				dataType: 'json'
			});
		}
	});
});
