<?php
	class Recommend{

        public static function GetTags($UserOrArticles){
            
        	require "include/connect.php";
        	$sql = "SELECT * FROM users WHERE User='" . $_SESSION["UserCode"] . "'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
            	while ($row = $result->fetch_assoc()){
                    $sql = "SELECT T.TagName FROM Tags"
	                //$recomendedTagsByInterests[0] = 'Paises';
	                foreach (explode(';', $row['Recent']) as $titleID) {
	                    $sql = "SELECT * FROM articles WHERE PostID ='". $titleID ."' ";
	                    $result = $conn->query($sql);
	                    if ($result->num_rows > 0) {
	                    	while ($row = $result->fetch_assoc()){
		                        foreach (explode(';', $row['Tags']) as $separtatedTag)
		                            $recomendedTagsByArticles[] = $separatedTag; 
	                        }
	                    }
	                }
	            }

            }
			$sql = "SELECT DISTINCT * FROM users WHERE Tags LIKE '%". $tagsOfUser[0] ."%' ";
            foreach ($tagsOfUser as $tagsSearch) {            
                $sql .= "OR Tags LIKE '%". $tagsSearch ."%'";
            }
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
            	while ($row = $result->fetch_assoc())
            		$listOfTagsByUsers = ";" . $row['Tags'];
        	}
            $recomendedTagsByUsers = explode(";", $listOfTagsByUsers);
            switch ($UserOrArticles) {
            	case 'Users':
            		return array_unique($recomendedTagsByUsers);
            		break;
        		case 'Articles':
            		return array_unique($recomendedTagsByArticles);
        			break;
            	
            	default:
            		# code...
            		break;
            }

        }
        public static function GetArticles($Max, $From){
            require "include/connect.php";
    		$tagsToUse = $this->GetTags($From);
			if (!empty($tagsToUse))
            {
            	array_unique($tagsToUse);
            	$sql = "SELECT DISTINCT * FROM articles WHERE Tags LIKE '%". $tagsToUse[0] ."%'";
            	for ($i = 1; $i< $Max; $i++)
                {
                    $sql .= "OR Tags LIKE '%". $tagsToUse[i] ."%'";
                }

                //$sql = "SELECT DISTINCT * FROM articles WHERE Tags LIKE '%". $newTags ."%' ";
                $result = $conn->query($sql);

                if ($result->num_rows > 0)
                {
                    $articles = "";	
                	while ($row = $result->fetch_assoc()){
                        $articles .= '<div class="col s12 m3">
                            <div class="card hoverable item" id="'.$row['PostID'].'">
                                <div class="card-image">
                                    <img src="https://i.ytimg.com/vi/2OgLKz9yQ0Q/hqdefault.jpg?custom=true&w=246&h=138&stc=true&jpg444=true&jpgq=90&sp=68&sigh=IE1JFDEOZl_4r872Wlo5ydYUKjc">
                                </div>
                                <div class="card-content">
                                    <strong>'.$row['Title'].'</strong>
                                    <p>ACA VA EL CREADOR</p>
                                </div>
                            </div>
                        </div>
                        '; 
                    }
                }  
            }
            return $articles;
            	
            }
		}

		/*
		function ByArticles($Max){
			require "/include/connect.php";
			$tagsToUse = $this->GetTags('Articles');
			if (!empty($tagsToUse))
                    {
                        foreach ($tagsToUse as $newTags)
                        {
                            $sql = "SELECT * FROM articles WHERE Tags LIKE '". $newTags ."' ";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0)
                            {
                            	for ($i = 0; $i< $Max; $i++){
	                                return '  <div class="col s12 m3">
	                                    <div class="card hoverable item" id="'.$row['PostID'].'">
	                                        <div class="card-image">
	                                            <img src="https://i.ytimg.com/vi/2OgLKz9yQ0Q/hqdefault.jpg?custom=true&w=246&h=138&stc=true&jpg444=true&jpgq=90&sp=68&sigh=IE1JFDEOZl_4r872Wlo5ydYUKjc">
	                                        </div>
	                                        <div class="card-content">
	                                            <strong>'.$row['Title'].'</strong>
	                                            <p>ACA VA EL CREADOR</p>
	                                        </div>
	                                    </div>
	                                </div>'; 
	                            }
                            }   
                        }
                    }
			
		}
		function ByUsers($Max){
			require "/include/connect.php";
			$tagsToUse = $this->GetTags('Users');
			if (!empty($$tagsToUse))
                    {
                        foreach ($tagsToUse as $newTags)
                        {
                            $sql = "SELECT * FROM articles WHERE Tags LIKE '". $newTags ."' ";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0)
                            {
                                for ($i = 0; $i< $Max; $i++){
	                                return '  <div class="col s12 m3">
	                                    <div class="card hoverable item" id="'.$row['PostID'].'">
	                                        <div class="card-image">
	                                            <img src="https://i.ytimg.com/vi/2OgLKz9yQ0Q/hqdefault.jpg?custom=true&w=246&h=138&stc=true&jpg444=true&jpgq=90&sp=68&sigh=IE1JFDEOZl_4r872Wlo5ydYUKjc">
	                                        </div>
	                                        <div class="card-content">
	                                            <strong>'.$row['Title'].'</strong>
	                                            <p>ACA VA EL CREADOR</p>
	                                        </div>
	                                    </div>
	                                </div>'; 
	                            }
                            }   
                        }
                    }
		}*/
	}
?>