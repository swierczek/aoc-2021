<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

$sum = 0;

foreach($lines as $l) {
	$segments = array_fill(0, 9, '');

	$parts = explode(' | ', $l);
	$inputs = explode(' ', $parts[0]);
	$outputs = explode(' ', $parts[1]);
	$solvedCount = 0;

	while (count($inputs) > 0) {
		// we know these unique values
		if (!$segments[1] || !$segments[4] || !$segments[7] || !$segments[8]) {
			foreach($inputs as $key => $i) {
				$i = str_split($i);
				asort($i);
				$i = implode($i);

				if (strlen($i) == 2) {
					// echo 'solved 1'.PHP_EOL;
					$segments[1] = $i;
					unset($inputs[$key]);
				} else if (strlen($i) == 4) {
					// echo 'solved 4'.PHP_EOL;
					$segments[4] = $i;
					unset($inputs[$key]);
				} else if (strlen($i) == 3) {
					// echo 'solved 7'.PHP_EOL;
					$segments[7] = $i;
					unset($inputs[$key]);
				} else if (strlen($i) == 7) {
					// echo 'solved 8'.PHP_EOL;
					$segments[8] = $i;
					unset($inputs[$key]);
				}
			}
		} else {
			// now for the remaining 0, 2, 3, 5, 6, 9
			// of lengths........... 6, 5, 5, 5, 6, 6
			foreach($inputs as $key => $i) {
				$i = str_split($i);
				asort($i);
				$i = implode($i);

				$overlap1 = count(findOverlap($i, $segments[1]));
				$overlap4 = count(findOverlap($i, $segments[4]));
				$overlap7 = count(findOverlap($i, $segments[7]));
				$overlap8 = count(findOverlap($i, $segments[8]));

				if (strlen($i) == 6) {
					// 0, 6, 9

					if ($overlap1 == 7 && $overlap4 == 7 && $overlap7 == 7 && $overlap8 == 7) {
						//7777
						// echo 'solved 6'.PHP_EOL;
						$segments[6] = $i;
						unset($inputs[$key]);
					} else if ($overlap1 == 6 && $overlap4 == 6 && $overlap7 == 6 && $overlap8 == 7) {
						//6667
						// echo 'solved 9'.PHP_EOL;
						$segments[9] = $i;
						unset($inputs[$key]);
					} else if ($overlap1 == 6 && $overlap4 == 7 && $overlap7 == 6 && $overlap8 == 7) {
						//6767
						// echo 'solved 0'.PHP_EOL;
						$segments[0] = $i;
						unset($inputs[$key]);
					}
				} else if (strlen($i) == 5) {
					// 2, 3, 5

					if ($overlap1 == 5 && $overlap4 == 6 && $overlap7 == 5 && $overlap8 == 7) {
						//5657
						// echo 'solved 3'.PHP_EOL;
						$segments[3] = $i;
						unset($inputs[$key]);
					} else if ($overlap1 == 6 && $overlap4 == 7 && $overlap7 == 6 && $overlap8 == 7) {
						//6767
						// echo 'solved 2'.PHP_EOL;
						$segments[2] = $i;
						unset($inputs[$key]);
					} else if ($overlap1 == 6 && $overlap4 == 6 && $overlap7 == 6 && $overlap8 == 7) {
						//6667
						// echo 'solved 5'.PHP_EOL;
						$segments[5] = $i;
						unset($inputs[$key]);
					}
				}
			}
		}
	}

	$out = '';
	foreach($outputs as $o) {
		$o = str_split($o);
		asort($o);
		$o = implode($o);

		$search = array_search($o, $segments);

		$out .= $search;
	}

	$sum += intval($out);
}

echo '<pre>';
var_dump($sum);
echo '</pre>';
die();

function findDiff($str1, $str2) {
	$arr1 = str_split($str1);
	$arr2 = str_split($str2);

	return array_diff($arr1, $arr2);
}

function findOverlap($str1, $str2) {
	$arr1 = str_split($str1);
	$arr2 = str_split($str2);

	return array_unique(array_merge($arr1, $arr2));
}