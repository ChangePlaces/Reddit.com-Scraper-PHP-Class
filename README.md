Reddit.com-Scraper-PHP-Class
============================

PHP class for scraping reddit.com for a list of links/authors and/or imgur images. Originally created to 
mass download images from gonewild.

## BASIC USAGE ##
```php 
<?php 
$section = 'gonewild';
$max_pages = 5;

// include the class
require_once("reddit.class.php");

// perform a scrape on the appropriate section and pages
$data = $reddit->scrape($section,$max_pages);

foreach($data as $item) {
	// if the link is an imgur link, download the images
	if(strstr($item['url'],'imgur.com')) { ```
		$reddit->processImgurLink($item['url'],'images/',$item['author']);
	}
}
```

There are currently no pauses or anything put into place. Don't overload the reddit server...
