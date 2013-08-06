<?php

if ( ! defined('PHP_VERSION_ID') or PHP_VERSION_ID < 50300)
{
	error(500, 'TodoPHP requires PHP 5.3+');
}

function error($code = 500, $message = 'Internal Server Error')
{
	if (php_sapi_name() !== 'cli')
	{
		@header("HTTP/1.0 {$code} {$message}", true, $code);
	}

	die("Error {$code}: {$message}\n");
}

function config($key, $value = null)
{
	static $config = array();

	if ($key === 'load' && is_file($value))
	{
		$config = array_merge($config, (array) @require $value);
	}
	elseif (empty($value))
	{
		return from($config, $key);
	}

	set($config, $key, $value);
}

function from($array, $key, $default = null)
{
	foreach (explode('.', $key) as $segment)
	{
		if ( ! is_array($array) or ! array_key_exists($segment, $array))
		{
			return $default;
		}

		$array = $array[$segment];
	}

	return $array;
}

function set(&$array, $key, $value)
{
	if (empty($key))
	{
		return $array = $value;
	}

	$keys = explode('.', $key);

	while (count($keys) > 1)
	{
		$key = array_shift($keys);

		if ( ! isset($array[$key]) or ! is_array($array[$key]))
		{
			$array[$key] = array();
		}

		$array =& $array[$key];
	}

	$array[array_shift($keys)] = $value;
}

function stash($key, $value = null)
{
	static $stash = array();

	if ($value === null)
	{
		$value = from($stash, $key);
	}
	else
	{
		set($stash, $key, $value);
	}

	return $value;
}

function html($string, $charset = 'UTF-8', $flags = ENT_QUOTES)
{
	return htmlentities($string, $flags, $charset, false);
}

function render($file, $data = null)
{
	if (is_array($data))
	{
		extract($data, EXTR_SKIP);
	}

	$ext = pathinfo($file, PATHINFO_EXTENSION);
	$file = empty($ext) ? "{$file}.php" : $file;
	$path = sprintf('%s/%s', rtrim(config('view.path'), '/'), ltrim($file, '/'));

	if (file_exists($path))
	{
		ob_start();
  		include $path;
		return ob_get_clean();
	}
	else
	{
		error(500, "The view file '{$path}' could not be found.");
	}
}

function db()
{
	static $db;

	if (empty($db))
	{
		try
		{
			$db = new PDO(config('db.dsn'), config('db.username'), config('db.password'));
		}
		catch (PDOException $e)
		{
			error(500, $e->getMessage());
		}
	}

	return $db;
}

function query($sql, $params = null)
{
	$query = db()->prepare($sql);

	if (is_array($params) && count($params))
	{
		foreach ($params as $key => $value)
		{
			$param = is_int($key) ? ($key + 1) : $key;
			$type = PDO::PARAM_STR;

			if (is_int($value))
			{
				$type = PDO::PARAM_INT;
			}
			elseif (is_bool($value))
			{
				$type = PDO::PARAM_BOOL;
			}
			elseif (is_null($value))
			{
				$type = PDO::PARAM_NULL;
			}

			$query->bindParam($param, $params[$key], $type);
		}
	}

	return $query->execute() ? $query : false;
}

function row($query)
{
	return $query->fetch(PDO::FETCH_OBJ);
}

function result($query)
{
	return $query->fetchAll(PDO::FETCH_OBJ);
}

function insertId()
{
	return db()->lastInsertId();
}

function rowCount($query)
{
	return $query->rowCount();
}

function uri()
{
	$uri = $_SERVER['REQUEST_URI'];

	if (strpos($uri, $_SERVER['SCRIPT_NAME']) !== false)
	{
		$uri = substr($uri, strlen($_SERVER['SCRIPT_NAME']));
	}
	elseif (strpos($uri, dirname($_SERVER['SCRIPT_NAME'])) !== false)
	{
		$uri = substr($uri, strlen(dirname($_SERVER['SCRIPT_NAME'])));
	}

	if (strpos($uri, '/') !== 0)
	{
		$uri = sprintf('/%s', $uri);
	}

	return parse_url($uri, PHP_URL_PATH);
}

function url($uri = null, $index = true)
{
	$url = sprintf('%s/%s', trim(config('site.url'), '/'), $index ? trim(config('site.index'), '/') : null);

	return trim(sprintf('%s/%s', trim($url, '/'), trim($uri, '/')), '/');
}

function redirect($url = null, $code = 302)
{
	if (empty($url))
	{
		$url = url();
	}

	if (is_array($url))
	{
		$url = implode('/', $url);
	}

	header("Location: {$url}", true, $code);
	exit;
}

function ajax()
{
	return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
}

function json($data, $decode = false)
{
	return $decode ? json_decode($data) : json_encode($data);
}

function method($method = null)
{
	if (empty($method) or strtoupper($method) == strtoupper($_SERVER['REQUEST_METHOD']))
	{
		return strtoupper($_SERVER['REQUEST_METHOD']);
	}

	error(400, 'Bad Request');
}

function route($method, $pattern, $callback = null)
{
	static $routes = array();

	$method = strtoupper($method);

	if (in_array($method, array('GET', 'POST', 'PUT', 'DELETE')))
	{
		if (is_callable($callback))
		{
			return $routes[$method]["@^{$pattern}$@i"] = $callback;
		}
		elseif (isset($routes[$method]))
		{
			foreach ($routes[$method] as $_pattern => $_callback)
			{
				if (preg_match($_pattern, $pattern, $params))
				{
					array_shift($params);

					if (is_callable($_callback))
					{
						return call_user_func_array($_callback, $params);
					}
				}
			}
		}

		error(404, 'Not Found');
	}
	else
	{
		error(500, "Request method '{$method}' not supported");
	}
}

function get($pattern, $callback)
{
	route('GET', $pattern, $callback);
}

function post($pattern, $callback)
{
	route('POST', $pattern, $callback);
}

function run($uri = null, $method = null)
{
	if (empty($uri))
	{
		$uri = uri();
	}

	route(method($method), $uri);
}
