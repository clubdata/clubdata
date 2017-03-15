if ($ARGV ne $oldargv) {
	$oldargv = $ARGV;
	print "FILE: $ARGV\n";
}

if ( $_ =~ /index.php/) 
{ 
	print "$_\n";
	print "VOR: $`\n";
}


