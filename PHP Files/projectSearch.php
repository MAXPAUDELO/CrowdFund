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
    
    $scoreFullCategories = 7;
    $scoreCategoriesKeyword = 7;
    
    $scoreFullCurrentFunding = 6;
    $scoreCurrentFundingKeyword = 5;
    
    $scoreFullTargetFunding = 5;
    $scoreTargetFundingKeyword = 4;
    
    $scoreFullMinInvest = 4;
    $scoreMinInvestKeyword = 3;

    $keywords = filterSearchKeys($query);
    $escQuery = database::escape($query); // see note above to get DB object
    $nameSQL = array();
    $categoriesSQL = array();
    $currentFundingSQL = array();
    $targetFundingSQL = array();
    $minInvestSQL = array();

    /** Matching full occurences **/
    if (count($keywords) > 1){
        $nameSQL[] = "if (name LIKE '%".$escQuery."%',{$scoreFullName},0)";
        $categoriesSQL[] = "if (categories LIKE '%".$escQuery."%',{$scoreFullCategories},0)";
        $currentFundingSQL[] = "if (current_funding LIKE '%".$escQuery."%',{$scoreFullCurrentFunding},0)";
        $targetFundingSQL[] = "if (target_funding LIKE '%".$escQuery."%',{$scoreFullTargetFunding},0)";
        $minInvestSQL[] = "if (min_invest LIKE '%".$escQuery."%',{$scoreFullMinInvest},0)";
    }

    /** Matching Keywords **/
    foreach($keywords as $key){
        $nameSQL[] = "if (name LIKE '%".database::escape($key)."%',{$scoreNameKeyword},0)";
        $categoriesSQL[] = "if (categories LIKE '%".database::escape($key)."%',{$scoreCategoriesKeyword},0)";
        $currentFundingSQL[] = "if (current_funding LIKE '%".database::escape($key)."%',{$scoreCurrentFundingKeyword},0)";
        $targetFundingSQL[] = "if (target_funding LIKE '%".database::escape($key)."%',{$scoreTargetFundingKeyword},0)";
        $minInvestSQL[] = "if (min_invest LIKE '%".database::escape($key)."%',{$scoreMinInvestKeyword},0)";
    }
// end_date, backer_count, company_logo, source, source_site, 
    $sql = "SELECT project_id,name,categories,current_funding,target_funding,min_invest, end_date, backer_count, company_logo, source, source_site,
            (
                ( -- Name score
                    ".implode(" + ", $nameSQL)."
                )+
                ( -- Categories score
                ".implode(" + ", $categoriesSQL)."
                )+
                ( -- Current Funding score
                ".implode(" + ", $currentFundingSQL)."
                )+
                ( -- Target Funding score
                ".implode(" + ", $targetFundingSQL)."
                )+
                ( -- Minimal Investment score
                ".implode(" + ", $minInvestSQL)."
                )
            ) as relevance
            FROM Project
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