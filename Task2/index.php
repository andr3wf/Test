<?php

    $api_token = '68dc4027096168e1e65023d5e8a91c0c2c5afb75'; //api token
    $company_domain = 'andrew'; // company domain

    // grt all notes
    $url = 'https://' . $company_domain . '.pipedrive.com/v1/notes?limit=100&api_token=' . $api_token;
    $notes = curl($url);

    // get all organizations
    $url = 'https://' . $company_domain . '.pipedrive.com/v1/organizations?limit=100&api_token=' . $api_token;
    $organizations = curl($url);

    // get all persons
    $url = 'https://' . $company_domain . '.pipedrive.com/v1/persons?limit=100&api_token=' . $api_token;
    $persons = curl($url);

    //get all deals
    $url = 'https://' . $company_domain . '.pipedrive.com/v1/deals?limit=100&api_token=' . $api_token;
    $deals = curl($url);

    //get all tasks
    $url = 'https://' . $company_domain . '.pipedrive.com/v1/activities?limit=100&api_token=' . $api_token;
    $tasks = curl($url);


    //showing all organizations
    echo '<h1>Organizations list</h1>';
    $header = array('Id', 'Name', 'Open Deals', 'Close deals', 'Activities', 'Done activities', 'Owner');
    $keys = array('id', 'name', 'open_deals_count', 'closed_deals_count',  'activities_count', 'done_activities_count', 'owner_name');
    show($organizations, $keys, $header, $notes, 'org_id');


    //showing all persons
    echo '<h1>Persons list</h1>';
    $header = array('Id', 'Name', 'Open deals', 'Close deals', 'Activities', 'Done activities', 'Organization');
    $keys = array('id', 'name', 'open_deals_count', 'closed_deals_count',  'activities_count', 'done_activities_count', 'org_name');
    show($persons, $keys, $header, $notes, 'person_id');

    //showing all deals
    echo '<h1>Deals list</h1>';
    $header = array('Id', 'Title', 'Value', 'Currency', 'Status', 'Responsible person', 'Organization', 'Owner');
    $keys = array('id', 'title', 'value', 'currency', 'status', 'person_name', 'org_name', 'owner_name');
    show($deals, $keys, $header, $notes, 'deal_id');

    //showing all tasks(activities)
    echo '<h1>Tasks list</h1>';
    $header = array('Id', 'Type', 'Due Date', 'Organization', 'Person', 'Owner');
    $keys = array('id', 'type', 'due_date', 'org_name', 'person_name', 'owner_name');
    show($tasks, $keys, $header);

    /*
     *  return array
     *  GET request
     */
    function curl($url = ' '){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        curl_close($ch);
        $data =  json_decode($output, true);
        return $data['data'];
    }

    /*
     * return void
     * create array and show some information about organizations
     */
    function show($data = [], $keys = [], $header = [], $notes = [], $note_option = ' '){
        // convert all data to array
        $array = [];
        for($index = 0; $index < count($data); $index++){
            for($i = 0; $i < count($header); $i++){
                $key = $keys[$i];
                $array[$index][$header[$i]] = $data[$index][$key];
            }
            // show information
            $str = '';
            foreach($array[$index] as $org_key => $org_info){
                $str .= $org_key . ' = ' . $org_info  . ', ';
            }
            $str = rtrim($str, ', ');
            echo $str . '<br>';
            // if note exists, add it to array
            if($note_option != ' ' && !empty($notes)){
                foreach($notes as $key => $note){
                    if($note[$note_option] == $data[$index]['id']) {
                        $array[$index]['Notes'][] = $note['content'];
                    }
                }
            }

            //show notes
            if(isset($array[$index]['Notes'])){
                echo 'Notes: ';
                $note = implode(', ', $array[$index]['Notes']);
                echo $note . '<br>';
            }
        }
    }
