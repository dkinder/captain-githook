<?php
	print "ok.";
	$recipients = 'email@address.com';
	$json = $_POST["payload"];
	$obj = json_decode($json);

	$tmpFile = "/tmp/tmpFile.txt";
	$fh = fopen($tmpFile, 'w') or die("can't open file");;

	foreach  ($obj->{'commits'} as $commit) {
		$data = "\n*******************\n"; 
		$data .= "Committer: " . $commit->{'committer'}->{'name'} . "\n";
		$data .=  "Commit Message: " . $commit->{'message'} . "\n"; 
		$data .= $commit->{'timestamp'} . "\n";

		// List files that have been modified
		$data .= "\nMODIFIED FILES:\n";
		foreach ($commit->{'modified'} as $mod) {
			$data .= $mod . "\n";
		}

		// List files that have been added
		$data .= "\nNEW FILES:\n";
		foreach ($commit->{'added'} as $add) {
			$data .= $add . "\n";
		}	

		// List files that have been removed
		$data .= "\nREMOVED FILES:\n";
		foreach ($commit->{'removed'} as $rem) {
			$data .= $rem . "\n";
		}

		fwrite($fh, $data);

	}

	fclose($fh);

	`email -b -s "My Repo Commit" $recipients < $tmpFile`;
?>
