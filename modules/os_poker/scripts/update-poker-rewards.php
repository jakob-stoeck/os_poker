<?php
define('REWARDS_FILE', 'rewards.lib.php');

function t($str) {
	return "t(\"$str\")";
}

function load_rewards($file) {
	if (!file_exists($file)) {
		print "Unable to find rewards.lib.php\n";
		exit;
	}
	include_once($file);
	
	return os_poker_get_all_rewards();
}

function save_rewards($file, $rew) {
	$fd = fopen($file, 'w');
	if (!$fd) {
		print "Unable to write to $file\n";
		exit;
	}
	
	fwrite($fd, "<?php\n");
	fwrite($fd, "/* This file has been autogenerated by update-poker-class.php */\n");
	fwrite($fd, "function os_poker_get_all_rewards() {\n");
	fwrite($fd, "\treturn array(\n");
	foreach ($rew as $name => $r) {
		fwrite($fd, "\t'{$name}'\t=> array('value' => {$r['value']}, 'name' => {$r['name']}, 'color' => \"{$r['color']}\", 'points' => {$r['points']}, 'bonus' => {$r['bonus']}, 'picture' => \"\", 'desc' => {$r['desc']}),\n");
	}
	fwrite($fd, ");\n");
	fwrite($fd, "}\n");
	fwrite($fd, "?>\n");
	fclose($fd);
	print "$file updated\n";
}

function update_rewards($txtfile, &$rewards) {
	if (!file_exists($txtfile)) {
		print "Unable to open $txtfile\n";
		exit;
	}

	$data = file($txtfile);
	$line_num = 0;
	foreach ($data as $line) {
		$line_num++;
		$fields = split("\t", trim($line));
		if (count($fields) != 6) {
			print "Malformatted line at $txtfile:$line_num, skipping\n";
			continue;
		}

		list($id, $name, $desc, $hint, $image, $color) = $fields;
		$id = 'reward' . $id;

		if (!isset($rewards[$id])) {
			print "Unexpected reward at $txtfile:$line_num\n";
			exit;
		}

		if ($rewards[$id]['name'] != t($name)) {
			print "Changing reward name for $id: {$rewards[$id]['name']} => t(\"{$name}\")\n";
		}

		$rewards[$id]['name'] = t($name);
		$rewards[$id]['desc'] = t($desc);
		$rewards[$id]['color'] = $color;
	}
}

function usage($cmd) {
	print "Usage: $cmd <path to rewards.txt>

rewards.txt is the tab delimited text file formatted as per http://drupal-dev.pokersource.info/z2/wiki/RewardMap

";
	exit;
}

if (empty($argv[1])) {
	usage($argv[0]);
}

$rewards = load_rewards(REWARDS_FILE);
update_rewards($argv[1], $rewards);
save_rewards(REWARDS_FILE, $rewards);
?>
