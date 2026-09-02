<?php

if (!function_exists("url")) {
	function url($path = "")
	{
		$baseUrl = "/web-painting/public";
		$path = trim((string) $path, "/");
		$parts = explode("?", $path, 2);
		$route = $parts[0];
		$query = isset($parts[1]) ? $parts[1] : "";
		$parameters = [];

		if ($route !== "") {
			$parameters["route"] = $route;
		}

		if ($query !== "") {
			parse_str($query, $queryParameters);
			$parameters = array_merge($parameters, $queryParameters);
		}

		return $baseUrl . "/index.php" . (empty($parameters) ? "" : "?" . http_build_query($parameters));
	}
}

if (!function_exists("asset")) {
	function asset($path)
	{
		return "/web-painting/public/" . ltrim((string) $path, "/");
	}
}

class Controller
{
	protected function view($view, $data = [])
	{
		extract($data);
		require __DIR__ . "/../views/" . $view . ".php";
	}

	protected function redirect($path = "")
	{
		header("Location: " . url($path));
		exit;
	}

	protected function requireAdmin()
	{
		if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? "") !== "admin") {
			$this->redirect("login");
		}
	}
}
