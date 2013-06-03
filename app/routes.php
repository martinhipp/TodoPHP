<?php

get('/', function()
{
	$data['todos'] = getTodos();

	render('index', $data);
});

post('/add', function()
{
	$title = trim(from($_POST, 'title'));
	$id = addTodo($title);

	return to(url('/'));
});

post('/complete', function()
{
	$id = from($_POST, 'id');

	if ($id !== null)
	{
		completeTodo($id);
	}

	return to(url('/'));
});

post('/remove', function()
{
	$id = from($_POST, 'id');

	if ($id !== null)
	{
		removeTodo($id);
	}

	return to(url('/'));
});

get('/reset', function()
{
	resetTodos();

	return to(url('/'));
});
