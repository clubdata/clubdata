#!/usr/bin/perl
#
# Script to generate Language files from PHP-Scripts.
# Usage:
#   OLD: perl Installation/getAllLang2.pl $(perl -n -e 'if ( /item .*url=\"(.*\.php|.*\.tpl)\"/ && !/templates_c/ ) { print "$1\n"; }' clubdata2.webprj)
#   perl Installation/getAllLang2.pl $(find . \( \( -wholename './Tools' -o -wholename '*/templates_c' \) -prune -o -name '*.php' -o -name '*.tpl' \) -a -type f)
#
# IMPORTANT: Edit the $defEncoding version as this script will copy it to other code pages (i.e. ISO-8859-1), otherwise
#            all changes will be deleted !!!!!!!!!!!!
#
use File::Basename;
use DBI;

$database='Clubdata2a';
$hostname='localhost';
$user='clubdata2a';
$password='cluba';

$langPath = "Language/";
$defEncoding="UTF8";
@languages = ("FR", "UK", "DE");
$defLang = "DE";

@incArr = ("./", "include/" );

# Initialize text hashes with text which should always be created in translation files
%textHash = ();
%dbHash = ("Members" => "Members",
		   "Members_Attributes" => "Members_Attributes");

sub parseFilename {
    my ( $fn ) = @_;

    my ($name,$path,$suffix) = fileparse($fn, (".php",".tpl"));
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
        
        foreach $index (0 .. $#languages) {
            if ( $languages[$index] eq $name )
            {
                print "PRESET LANGUAGE TO $name ($index, $languages[$index])\n";
                $lang = $name;
            }
        }

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

sub readPHPfile {
    my ( $fn, $base, %text ) = @_;
    my ( $incFile, $txt, $recFile);
    local (*IN);
    print "Opening $fn\n";
    open (IN, "$fn") || die "Can't read $fn";

    while(<IN>)
    {
         while ( /lang\(\s*([\"\'])([^"]*?)\1\s*\)/g )
         {
            $txt = $2;
            print "TEXT: $txt\n";
            $text{$txt} = $txt;
         }

         while ( /helpAndLang\(.*,.*,\s*([\"\'])([^"]*)\1\s*\)/g )
         {
            $txt = $2;
            print "TEXT(HLP): $txt\n";
            $text{$txt} = $txt;
         }
    }
    close(IN);
    return %text;
}

sub readTPLfile {
    my ( $fn, $base, %text ) = @_;
    my ( $incFile, $txt, $recFile);
    local (*IN);
    print "Opening $fn\n";
    open (IN, "$fn") || die "Can't read $fn";

    while(<IN>)
    {
         while ( /\{lang\s+([^}]*)\}/g )
         {
            $txt = $1;
            print "TEXT: $txt\n";
            $text{$txt} = $txt;
         }

         while ( /("([^"]*)"|'([^']*)')\|(lang|translate)/g )
         {
            $txt = $2;
            print "LANG TEXT: $txt\n";
            $text{$txt} = $txt;
         }

    }
    close(IN);
    return %text;
}

sub readColumnnames {
    my ( %text ) = @_;
    

    $dsn = "DBI:mysql:database=$database;host=$hostname";
    
    $dbh = DBI->connect($dsn, $user, $password);
    
    my @tables = map { $_ =~ s/((.*)\.)?`([^`]*)`/$3/; $_ } $dbh->tables( undef, undef, undef, 'TABLE' );

    foreach my $table ( @tables )
    {
        $sth = $dbh->prepare("LISTFIELDS $table");
    
        $sth->execute;

        foreach $col (@{$sth->{NAME}})
        {
            print "DB($table): " . $col . "\n";
            $text{$col} = $col;
        }
    }
    return %text;
}

sub saveOtherCoding {
  my ( $language, $origCoding ) = @_;

  opendir(DIR, "$langPath") || die "can't opendir $langPath: $!";
  my @dirs = grep { !/^\./ && -d "$langPath/$_" } readdir(DIR);
  closedir DIR;

  foreach my $dir ( @dirs )
  {
    if ( $dir ne $defEncoding ) {
      system("cat '$langPath$defEncoding/$language.php' | recode '$defEncoding..$dir' >'$langPath$dir/$language.php'");
    }
  }

}
sub convertUmlaut {
    my ($text) = @_;

#     $text =~ s/�&Auml;/g;
#     $text =~ s/�&Ouml;/g;
#     $text =~ s/�&Uuml;/g;
#     $text =~ s/�&auml;/g;
#     $text =~ s/�&ouml;/g;
#     $text =~ s//&uuml;/g;
#     $text =~ s/�&szlig;/g;
#     $text =~ s/�&acirc;/g;
#     $text =~ s/�&agrave;/g;
#     $text =~ s/�&aacute;/g;
#     $text =~ s/�&egrave;/g;
#     $text =~ s/�&eacute;/g;

    return $text;
}

sub checkAllTrans {
    my($origTxt, $language, %allTrans) = @_;

    foreach $j ( keys %allTrans )
    {
        if ( exists $allTrans{$j}{$language}{$origTxt} )
        {
            return $allTrans{$j}{$language}{$origTxt};
        }
    }

    return "";
}


for ($fi=0 ; $fi <= $#ARGV; $fi++)
{

    next if ( ! -f $ARGV[$fi] );

    ($name,$path,$suffix, $fp) = parseFilename($ARGV[$fi]);

    print "ARGV: $ARGV[$fi], Path: $path, Basename: $name, FP: $fp, Suffix: $suffix\n";

    if ( $suffix eq ".php" )
    {
        %textHash = readPHPfile($ARGV[$fi], $fp, %textHash);
    }
    elsif ( $suffix eq ".tpl" )
    {
        %textHash = readTPLfile($ARGV[$fi], $fp, %textHash);
        print "XXXXXXXXXX\n";
    }
}
%dbHash = readColumnnames(%dbHash);

#exit;

%allTrans = readLangFile(glob("${langPath}${defEncoding}/??.php"));


foreach $language ( @languages )
{
    $langFile = "${langPath}${defEncoding}/${language}.php";

    print "PROCESSING FILE: $language -> LANGUAGE: $langFile\n";

    $transTxt = $allTrans{$language};

    rename $langFile, "$langFile.bak";

    open(OUT, ">$langFile") || die "Can't open $langFile for writing";
    
    my %seen = ();
    print(OUT "<?php\n");
    print OUT <<EOF;
/**
 * Clubdata Language Transation Files
 *
 * Contains the translation of Clubdata textes
 *
 * \@package Translation
 * \@license http://opensource.org/licenses/gpl-license.php GNU Public License
 * \@author Franz Domes <franz.domes@gmx.de>
 * \@version 2.0
 * \@copyright Copyright (c) 2009, 2010, Franz Domes
 */
EOF

    print(OUT "// DB means that the index is the name of a database column\n");
    print(OUT "// DB1 means that the index is a text and the name of a database column\n");
    print(OUT "// no commentary means that the index is a text\n\n");
    foreach $origTxt ( sort (keys %textHash, %dbHash) )
    {
#         print "ORIGTXT: $origTxt, SEEN{$origTxt}: $seen{ $origTxt }\n";
        next if ( $seen{ $origTxt }++ );
        
        if ( defined($dbHash{$origTxt}) )
        {
            if ( defined($textHash{$origTxt}) )
            {
                $com = "/* DB1 */";
            }
            else
            {
                $com = "/* DB  */";
            }
        }
        else
        {
            $com = "         ";
        }
        
        print "$origTxt => $transTxt->{$origTxt} \n";
        if ( exists($transTxt->{$origTxt}) )
        {
            $outTxt = convertUmlaut($transTxt->{$origTxt});
            print OUT " $com \$lang[\"$origTxt\"] = \"" . $outTxt . "\";\n";
        }
        elsif ( $outTxt = checkAllTrans($origTxt, $language, %allTrans) )
        {
            print OUT "/*T*/    \$lang[\"$origTxt\"] = \"". $outTxt . "\";\n";
        }
        else
        {
            print OUT "#$com \$lang[\"$origTxt\"] = \"\";\n";
        }

    }
    print OUT "\n?>\n";
    close OUT;
    saveOtherCoding($language,'');
}
