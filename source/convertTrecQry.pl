#!/usr/bin/perl -w

use strict;

###################################################
## Convert Trec query to simple format
## Author: Hao Wu
## Create Time: June 7,2013
###################################################

if(scalar(@ARGV)!=2) {die "Usage $0 inputFile outputFile\n";}
my $input=$ARGV[0];
my $output=$ARGV[1];

open(F1,$input) || die "Could not read file $input\n";
open(F2,">$output") || die "Could not write to file $output\n";
my $topic="";
my $query="";
while(my $line=<F1>)
{
	if($line=~/^<DOC ([^>]+)>/)
	{
		$topic=$1;
		$query="";
	}
	elsif($line=~/^(\w+)\n/)
	{
		if($query eq "") {$query=$1;}
		else {$query.=" ".$1;}
	}
	elsif($line=~/^<\/DOC>/)
	{
		print F2 $topic.":".$query."\n";
		$query="";
	}
}
close(F2);
close(F1);