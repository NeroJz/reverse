<?php

    function reverse($uri = ''){

        $patterns = '/\(en\|tc\)/';

        $options = 'en';
        $lang = 'eng';

        $tmp = '/\/[$]\d/';
        
        if($lang == 'chi'){
            $options = 'tc';
        }

        $route['(en|tc)/cinema/amc/(:any)']                                     = 'cinema';
        $route['(en|tc)/cinema/ticketprice']                                    = 'ticketing/info';

        //ticketing
        $route['(en|tc)/movie/ticketing/view=bymovie']                          = 'ticketing/movie/16';
        $route['(en|tc)/movie/ticketing/view=the-oval-office']                  = 'ticketing/dine/16';
        $route['(en|tc)/movie/ticketing/view=amcplus']                          = 'ticketing/house/16';
        $route['(en|tc)/movie/ticketing/view=bydate']                           = 'ticketing/date/16';
        $route['(en|tc)/movie/details/(:num)/(:num)']                           = 'ticketing/movieDetail/$2/$3';
        $route['(en|tc)/ticketing/movieSeat/(:num)/(:num)/(:num)']              = 'ticketing/movieSeat/$2/$3/$4';
        $route['(en|tc)/ticketing/food/(:num)/(:num)/(:num)']              = 'ticketing/movieFood/$2/$3/$4';
        $route['(en|tc)/ticketing/moviePayment/(:num)/(:num)/(:num)']           = 'ticketing/moviePayment/$2/$3/$4';
        $route['(en|tc)/ticketing/memberLogin']                                 = 'ticketing/memberLogin';


        $uri = trim($uri, '/');


        foreach($route as $key => $val){

            if(preg_match('/[^\(][.+?{\:]/', $key)){
                continue;
            }

            if(strpos($val, '$') !== FALSE AND strpos($key, '(') !== FALSE){

                preg_match_all( '/\(.+?\)/', $key, $keyRefs );
                preg_match_all( '/\$.+?/', $val, $valRefs );


                $keyRefs = $keyRefs[0];


                $uriRegex = $val;


                $goodValRefs = array();
                $modifyRefs = array();
                foreach ($valRefs[0] as $ref) {
                    $tempKey = substr($ref, 1);
                    if (is_numeric($tempKey)) {
                        --$tempKey;
                        $goodValRefs[$tempKey] = $ref;
                        $modifyRefs[] = '$' . $tempKey;
                    }
                }

                foreach ($goodValRefs as $tempKey => $ref) {
                    if (isset($keyRefs[$tempKey])) {
                        $uriRegex = str_replace($ref, $keyRefs[$tempKey], $uriRegex);
                    }
                }

                $uriRegex = str_replace(':any', '.+', str_replace(':num', '[0-9]+', $uriRegex));

                //$uriRegex = str_replace('/', '\/', $uriRegex);


                if(preg_match('#^'.$uriRegex.'$#', $uri)){
                    $key = str_replace(':any', '.+', str_replace(':num', '[0-9]+', $key));

                    $routeString = preg_split( '/\(.+?\)/', $key );

                    $replacement = '';
                    $rsEnd = count( $routeString ) - 2;

                    for($i=0; $i < $rsEnd; $i++){

                        $replacement .= $routeString[$i+1] . $modifyRefs[$i];
                    }

                    $replacement .= $routeString[$rsEnd];

                    $customURI = preg_replace('#^'.$uriRegex.'$#', $replacement, $uri);

                    return $options . $customURI;
                }
                //preview($uriRegex);

            }else if($val == $uri){
                $pattern = '/\(en\|tc\)/';

                $key = preg_replace($pattern, $options, $key);

                $key = str_replace('/(:any)', '', $key);

                return $key;
            }

        }


        return $uri;

    }

    function preview($params){

        echo '<pre>';
        print_r($params);
        echo '</pre>';

    }

?>


<?php

    //echo 'hello world' . '<br/>';

    //echo reverse('cinema') . '<br/>';
    echo reverse('cinema') . '<br/>';
    echo reverse('ticketing/dine/16') . '<br/>';

    echo reverse('ticketing/movieDetail/16/9568') . '<br/>';
    echo reverse('ticketing/movieFood/2/16/32') . '<br/>';

?>