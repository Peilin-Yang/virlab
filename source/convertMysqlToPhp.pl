#!/usr/bin/perl -w

use strict;

#############################################################
## Convert the mysql code to php code
## Author: Hao Wu
## Create Time: 09/02/2015
#############################################################

if(scalar(@ARGV)!=2) {die "Usage: $0 mysqlcode phpcode\n";}
my $input = $ARGV[0];
my $output = $ARGV[1];

open(F1,$input) || die "Could not read $input\n";
open(F2,">$output") || die "Could not write to $output\n";
my $str="";
while(my $line = <F1>) {
  $line = substr($line,0,-1);
  while($line =~ /^([^;]+);/) {
    $str .= $1;
    $line = $';
    my $query = $str;
    print F2 "mysqli_query(\$mysql,\"$query\");\n";
    $str = "";
  }
  $str .= $line;
}
close(F2);
close(F1);
