use DBI;
use File::Basename;

$database='Clubdata2';
$hostname='localhost';
$user='clubdata';
$password='club';

$langPath = "Language/";
@languages = ("FR", "UK", "DE");


%colTrans = ();

$dsn = "DBI:mysql:database=$database;host=$hostname";

$dbh = DBI->connect($dsn, $user, $password);

sub parseFilename {
    my ( $fn ) = @_;

    my ($name,$path,$suffix) = fileparse($fn, (".php"));
    if ( $path =~ /Authentication/ )
    {
        $fp = "authentication";
    }
    else
    {
        $name =~ /^([^_]*)/;
        $fp = $1;
    }

    return ( $name, $path, $suffix, $fp);
}


sub readLangFile {
    my ( @fnList ) = @_;
    my ( %transTxt );

    %transTxt = ();
    $lang = "";
    foreach $fn ( @fnList )
    {
        ($name, $path, $suffix, $fp) = parseFilename($fn);

	$lang = substr($name,0,2);
        print "PRESET LANGUAGE TO $lang ($name)\n";

        open (IN, "$fn") || do { warn "Can't read $fn"; return; };
        print("Lese Datei $fn\n");

        while(<IN>)
        {
            next if (/^\s*#/) ;

            if ( /case "(..)"/ )
            {
                $lang = $1;
                print "Switch to lang: $lang\n";
            }
            elsif ( /lang\[([\"\'])([^\"]*)\1\]\s*=\s*\"(.*)\";$/ )
            {
                $idx = $2;
                $trans = $3;

                print "$idx => $trans\n";
                if ( ! exists($transTxt{$lang}{$idx}) )
                {
                    $transTxt{$lang}{$idx} = $trans;
                }
            }
        }
        close(IN);
    }
    return %transTxt;
}

sub convertUmlaut {
    my ($text) = @_;

    $text =~ s/304/&Auml;/g;
    $text =~ s/326/&Ouml;/g;
    $text =~ s/334/&Uuml;/g;
    $text =~ s/344/&auml;/g;
    $text =~ s/366/&ouml;/g;
    $text =~ s/374/&uuml;/g;
    $text =~ s/337/&szlig;/g;
    $text =~ s/342/&acirc;/g;
    $text =~ s/340/&agrave;/g;
    $text =~ s/341/&aacute;/g;
    $text =~ s/350/&egrave;/g;
    $text =~ s/341/&eacute;/g;

    return $text;
}

%allTrans = readLangFile(glob("Language/*.php"));

#@tables = map { $_ =~ s/.*\.//; $_ } $dbh->tables();
@tables = map { $_ =~ s/`([^`]*)`/\1/; $_ }$dbh->tables();

foreach $table (@tables)
{
	print "TABELLE: $table\n";
	$sth = $dbh->prepare("LISTFIELDS $table");

	$sth->execute;

	foreach $col (@{$sth->{NAME}})
	{
#	        print "SPALTEN: $col\n";
		$colTrans{$col} = '';
	}	
}

foreach $language ( @languages )
{
    $langFile = "${langPath}${language}_tablang.php";

    print "PROCESSING FILE: $language -> LANGUAGE: $langFile\n";

    $transTxt = $allTrans{$language};

    rename $langFile, "$langFile.bak";

    open(OUT, ">$langFile") || die "Can't open $langFile for writing";

    print(OUT "<?php\n");
    foreach $origTxt ( keys %colTrans )
    {
        print "$origTxt => $transTxt->{$origTxt} \n";
        if ( exists($transTxt->{$origTxt}) )
        {
            $outTxt = convertUmlaut($transTxt->{$origTxt});
            print OUT "        \$tablang[\"$origTxt\"] = \"" . $outTxt . "\";\n";
        }
        else
        {
            print OUT "#        \$tablang[\"$origTxt\"] = \"\";\n";
        }

    }
    print OUT "\n?>\n";
    close OUT;
}
