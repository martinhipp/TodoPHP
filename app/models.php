<?php

function getTodos()
{
	$sql = 'SELECT * FROM todos';
	$query = query($sql);

	return result($query);
}

function addTodo($title)
{
	$sql = 'INSERT INTO todos (title) VALUES (?)';

	return query($sql, array($title)) ? insertId() : false;
}

function completeTodo($id)
{
	$sql = 'UPDATE todos SET completed_at = ? WHERE id = ?';

	return query($sql, array(date('Y-m-d H:i:s'), $id));
}

function removeTodo($id)
{
	$sql = 'DELETE FROM todos WHERE id = ?';

	return query($sql, array($id));
}

function resetTodos()
{
	$sql = 'DELETE FROM todos';

	return query($sql);
}
