<?php
// reddit/imgur scraper
$section = 'gonewild';
$max_pages = 50;

require_once("reddit.class.php");

echo "Fetching pages...";

// scrape the list of posts
$data = $reddit->scrape($section,$max_pages);

// total number of links returned, initialize counter for percentages
$totalitems = count($data); $counter = 1;

echo "Parsing ".$totalitems." total items...\n\n";

// process the links we are left with
foreach($data as $item) {
	if(strstr($item['url'],'imgur.com')) {
		$reddit->processImgurLink($item['url'],'images/',$item['author']);	
	}
	
	// display progress
	$completed = round(($counter/$totalitems)*100);
	echo ($completed<>$last_completion) ? $completed."% complete\n" : '';
	$last_completion = $completed;	
	$counter++;
	
}