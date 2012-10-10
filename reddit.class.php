<?php
/*
	Reddit.com Scraper PHP Class
	Author: Brandon DuBois
*/
class Reddit {
	/*
		function: scrape
		returns an array of titles,article urls
	*/ 
	function scrape($section,$max_pages) {

		$base_url = 'http://www.reddit.com/r/'.$section.'.json';

		$entries = array();
		for($i=1;$i<=$max_pages;$i++) {
			
			$scrape_url = ($i==0) ? $base_url : $base_url.'?after='.$after;
			
			$data = json_decode(file_get_contents($scrape_url),true);

			$after = $data['data']['after'];
			
			foreach($data['data']['children'] as $child) {
					list($url,$title,$author) = array($child['data']['url'],$child['data']['title'],$child['data']['author']);
					array_push($entries,array('url'=>$url,'title'=>$title,'author'=>$author));
			}
		}
		
		if(count($entries)>0) return $entries;
			
		return false;

	}
	
	/*
		function: processImgurLink
		processes imgur urls and downloads pictures to specified directory
	*/
	function processImgurLink($url,$savedir,$username = '') {
		if($username<>'') { $username.='_'; }
		if(strstr($url,'i.imgur')) {
			// this is a single picture, grab the location
			$imgname = explode('/',$url); $imgname = end($imgname);
			$img = $savedir.$username.$this->cleanFileName($imgname);
			// save the image locally
			if(file_put_contents($img,file_get_contents($url))) {
				return true;
			}
			return false;
		} elseif(strstr($url,'imgur.com/a')) {
			// this is an album
			$url.='/noscript';
			$urls = $this->getImgurAlbum($url);
			foreach($urls as $url) {
				$imgname = explode('/',$url); $imgname = end($imgname);
				$img = $savedir.$username.$this->cleanFileName($imgname);
				// save the image locally
				if(file_put_contents($img,file_get_contents($url))) {
					return true;
				}
			}			
			return false;
		} else {
			// this is a single picture
			$imgname = explode('/',$url); $imgname = end($imgname);
			$url = 'http://imgur.com/download/'.$imgname;
			$img = $savedir.$username.$imgname.'.jpg';
			//save the image locally
			if(file_put_contents($img,file_get_contents($url))) {
				return true;
			}
			return false;
		}
	}
	
	/*
		function: getImgurAlbum
		returns a list of images in an imgur album link
	*/
	function getImgurAlbum($url) {
		$data = file_get_contents($url);
		preg_match_all('/http\:\/\/i\.imgur\.com\/(.*)\.jpg/',$data,$matches);
		return $matches = array_unique($matches[0]);
	}
	
	/*
		function: cleanFileName
		returns a name with no special characters, only alphanumeric characters and periods.
	*/
	function cleanFileName($name) {
		$name = preg_replace('/[^a-zA-Z0-9.]/', '', $name);
		$name = substr($name,0,9);
		return $name;
	}	
	
}
$reddit = new Reddit;