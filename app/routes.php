<?php

get('/', function()
{
	$data['todos'] = getTodos();
	$data['completed'] = countCompletedTodos();

	echo render('todos', $data);
});

post('/add', function()
{
	$title = trim(from($_POST, 'title'));
	$id = newTodo($title);

	$success = ! empty($id);

	echo json(compact('success', 'id'));
});

post('/toggle', function()
{
	$id = from($_POST, 'id');
	$success = toggleTodo($id);

	echo json(compact('success'));
});

post('/remove', function()
{
	$id = from($_POST, 'id');
	$success = removeTodo($id);

	echo json(compact('success'));
});

get('/clear', function()
{
	$success = removeCompletedTodos();

	echo json(compact('success'));
});
