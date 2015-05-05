<?php
// Remove unnecessary words from the search term and return them as an array
include 'database.php';
function filterSearchKeys($query){
    $query = trim(preg_replace("/(\s+)+/", " ", $query));
    $words = array();
    // expand this list with your words.
    $list = array("in","it","a","the","of","or","I","you","he","me","us","they","she","to","but","that","this","those","then");
    $c = 0;
	if(count(explode(" ", $query))>1){
		foreach(explode(" ", $query) as $key){
			if (in_array($key, $list)){
				continue;
			}
			$words[] = $key;
			if ($c >= 15){
				break;
			}
			$c++;
		}
	} else {
		$words[] = $query;
	}
    return $words;
}

// limit words number of characters
function limitChars($query, $limit = 200){
    return substr($query, 0,$limit);
}

function search($query){

    $query = trim($query);
    if (mb_strlen($query)===0){
        // no need for empty search right?
        return false; 
    }
    $query = limitChars($query);

    // Weighing scores
    $scoreFullName = 7;
    $scoreNameKeyword = 7;
    
    $scoreFullLocation = 6;
    $scoreLocationKeyword = 5;
    
    $scoreFullInvestments = 5;
    $scoreInvestmentsKeyword = 4;
    
    $scoreFullAverageInvested = 4;
    $scoreAverageInvestedKeyword = 3;
    
    $scoreFullProfession = 4;
    $scoreProfessionKeyword = 2;
    
    $scoreFullTargetMarket = 1;
    $scoreTargetMarketKeyword = 1;
    
    $scoreFullTargetLocation = 1;
    $scoreTargetLocationKeyword = 1;
    
    $scoreFullCompanyTitle = 1;
    $scoreCompanyTitleKeyword = 1;
    
    $scoreFullSourceURL = 1;
    $scoreSourceURLKeyword = 1;
    
    $scoreFullSourceSite = 1;
    $scoreSourceSiteKeyword = 1;

    $keywords = filterSearchKeys($query);
    $escQuery = database::escape($query); // see note above to get DB object
    $nameSQL = array();
    $locationSQL = array();
    $investmentsSQL = array();
    $averageInvestedSQL = array();
    $professionSQL = array();
    $targetMarketSQL = array();
    $targetLocationSQL = array();
    $companyTitleSQL = array();
    $sourceURLSQL = array();
    $sourceSiteSQL = array();

    /** Matching full occurences **/
    if (count($keywords) > 1){
        $nameSQL[] = "if (name LIKE '%".$escQuery."%',{$scoreFullName},0)";
        $locationSQL[] = "if (location LIKE '%".$escQuery."%',{$scoreFullLocation},0)";
        $investmentsSQL[] = "if (investments LIKE '%".$escQuery."%',{$scoreFullInvestments},0)";
        $averageInvestedSQL[] = "if (average_invested LIKE '%".$escQuery."%',{$scoreFullAverageInvested},0)";
        $professionSQL[] = "if (profession LIKE '%".$escQuery."%',{$scoreFullProfession},0)";
        $targetMarketSQL[] = "if (target_market LIKE '%".$escQuery."%',{$scoreFullTargetMarket},0)";
        $targetLocationSQL[] = "if (target_location LIKE '%".$escQuery."%',{$scoreFullTargetLocation},0)";
        $companyTitleSQL[] = "if (company_title LIKE '%".$escQuery."%',{$scoreFullCompanyTitle},0)";
        $sourceURLSQL[] = "if (source_url LIKE '%".$escQuery."%',{$scoreFullSourceURL},0)";
        $sourceSiteSQL[] = "if (source_site LIKE '%".$escQuery."%',{$scoreFullSourceSite},0)";
    }

    /** Matching Keywords **/
    foreach($keywords as $key){
        $nameSQL[] = "if (name LIKE '%".database::escape($key)."%',{$scoreNameKeyword},0)";
        $locationSQL[] = "if (location LIKE '%".database::escape($key)."%',{$scoreLocationKeyword},0)";
        $investmentsSQL[] = "if (investments LIKE '%".database::escape($key)."%',{$scoreInvestmentsKeyword},0)";
        $averageInvestedSQL[] = "if (average_invested LIKE '%".database::escape($key)."%',{$scoreAverageInvestedKeyword},0)";
        $professionSQL[] = "if (profession LIKE '%".database::escape($key)."%',{$scoreProfessionKeyword},0)";
        $targetMarketSQL[] = "if (target_market LIKE '%".database::escape($key)."%',{$scoreTargetMarketKeyword},0)";
        $targetLocationSQL[] = "if (target_location LIKE '%".database::escape($key)."%',{$scoreTargetLocationKeyword},0)";
        $companyTitleSQL[] = "if (company_title LIKE '%".database::escape($key)."%',{$scoreCompanyTitleKeyword},0)";
        $sourceURLSQL[] = "if (source_url LIKE '%".database::escape($key)."%',{$scoreSourceURLKeyword},0)";
        $sourceSiteSQL[] = "if (source_site LIKE '%".database::escape($key)."%',{$scoreSourceSiteKeyword},0)";
    }
// 
    $sql = "SELECT investor_id,name,location,investments,average_invested,profession,target_market,target_location,company_title,source_url,source_site,
            (
                ( -- Name score
                    ".implode(" + ", $nameSQL)."
                )+
                ( -- Location score
                    ".implode(" + ", $locationSQL)."
                )+
                ( -- Investments score
                    ".implode(" + ", $investmentsSQL)."
                )+
                ( -- Average Invested score
                    ".implode(" + ", $averageInvestedSQL)."
                )+
                ( -- Profession score
                    ".implode(" + ", $professionSQL)."
                )+
                ( -- Target Market score
                    ".implode(" + ", $targetMarketSQL)."
                )+
                ( -- Target Location score
                    ".implode(" + ", $targetLocationSQL)."
                )+
                ( -- Company Title score
                    ".implode(" + ", $companyTitleSQL)."
                )+
                ( -- Source URL score
                    ".implode(" + ", $sourceURLSQL)."
                )+
                ( -- Source Site score
                    ".implode(" + ", $sourceSiteSQL)."
                )
            ) as relevance
            FROM Investor
            HAVING relevance > 0
            ORDER BY relevance DESC
            LIMIT 25";
    $results = database::query($sql);
    if (!$results){
        return false;
    }
    return $results;
}
?>