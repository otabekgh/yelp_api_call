function get_yelp_api($homepress_post_id, $is_cache, $token, array $categories, $latitude, $longitude, $sort_by, $limit)
        {
            if ( !$is_cache ) {
                delete_transient( 'yelp_post_id_'.$homepress_post_id );
            }

            $yelp_cache = get_transient('yelp_post_id_'.$homepress_post_id);
            if (!$yelp_cache) {
                $result = [];
                foreach ($categories as $category) {
                    $ch = curl_init('https://api.yelp.com/v3/businesses/search' . '?term=' . $category . '&latitude=' . $latitude . '&longitude=' . $longitude . '&sort_by=' . $sort_by . '&limit=' . $limit);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Content-Type: application/json',
                        'Authorization: Bearer ' . $token
                    ));
                    $data = curl_exec($ch);
                    //$info = curl_getinfo($ch);
                    curl_close($ch);
                    array_push($result, array(
                        'category' => $category,
                        'businesses' => json_decode($data)
                    ));
                }
                set_transient('yelp_post_id_' . $homepress_post_id, $result, 60 * 60 * 24);
                return $result;
            } else {
                return $yelp_cache;
            }
        }

    //}

    function my_custom_menu_page() {

        echo '<pre>';
            print_r(get_yelp_api(
                1,
                false,
                'kJltjjAcZLdVJz87_2su0Rz7rp2gC3YbuR7wFpFTBJza-6nIxpPWmhQvBrPuafZD_Bx22TRRbV6kWsyW_649GssJF5a0XLNtbML5Wn87pDia9ztxS-DzTnbtkqS-XHYx',
                ['education', 'realestate', 'restaurants'],
                '40.741895',
                '-73.989308',
                'best_match',
                '1'
            ));

        echo '</pre>';
