<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));


// list of node relationships
$graph = [];
$validPaths = [];

// build the graph
foreach($lines as $l) {
	$edge = explode('-', $l);
	$graph[$edge[0]][] = $edge[1];
	$graph[$edge[1]][] = $edge[0];
	//TODO: may need to ensure these are unique or only added once?
	// $graph[$edge[0]][$edge[1]] = $edge[1];
	// $graph[$edge[1]][$edge[0]] = $edge[0];
}

followNodes(['start']);

echo '<pre>';
var_dump(count($validPaths));
echo '</pre>';
die();



function followNodes($path = []) {
	global $graph;
	global $validPaths;

	$key = $path;
	$key = array_pop($key);

	foreach($graph[$key] as $child) {
		// we've already visited this small node on this traversal
		if (isSmall($child) && in_array($child, $path) ) {
			continue;
		}

		// found an end node, return
		if (isEnd($key)) {
			$unique = implode('', $path);
			$validPaths[$unique] = $path;
			continue;
		}

		array_push($path, $child);

		followNodes($path);

		array_pop($path);
	}
}

function isSmall($key) {
	return strtolower($key) === $key;
}

function isBig($key) {
	return strtoupper($key) === $key;
}

function isStart($key) {
	return $key === 'start';
}

function isEnd($key) {
	return $key === 'end';
}

