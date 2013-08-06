<?php

function getTodo($id)
{
	$sql = 'SELECT * FROM todos WHERE id = ?';
	$query = query($sql, array($id));

	return row($query);
}

function getTodos()
{
	$sql = 'SELECT * FROM todos';
	$query = query($sql);

	return result($query);
}

function newTodo($title)
{
	$sql = 'INSERT INTO todos (title) VALUES (?)';
	$query = query($sql, array($title));

	return $query ? insertId() : false;
}

function toggleTodo($id)
{
	$sql = 'UPDATE todos SET completed = completed != 1 WHERE id = ?';
	$query = query($sql, array($id));

	return rowCount($query) > 0;
}

function removeTodo($id)
{
	$sql = 'DELETE FROM todos WHERE id = ?';
	$query = query($sql, array($id));

	return rowCount($query) > 0;
}

function removeCompletedTodos()
{
	$sql = 'DELETE FROM todos WHERE completed = ?';
	$query = query($sql, array(true));

	return rowCount($query);
}

function countCompletedTodos()
{
	$sql = 'SELECT COUNT(*) AS total FROM todos WHERE completed = ?';
	$query = query($sql, array(true));

	return row($query)->total;
}
