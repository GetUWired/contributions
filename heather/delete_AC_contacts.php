<?php 
include('helper-functions.php');
include('active-api.php');
include('phone2action-api.php');

echo "deleting from test ActiveCampaign: <br>";

// exit;



$limit = 1000;
$offset = 0;
$page = 1;
$tagId = 0;

$params = array('tagid' => $tagId, 'limit' => $limit, 'offset' => $offset);

do {


	$all_contacts = find_contacts($params);

	echo "Page Number: " . $page . "<br>";

    // echo "<pre>";
    // print_r($all_contacts);

    // print_r($all_contacts->contacts);
    // echo "</pre>";




	foreach ($all_contacts->contacts as $single => $val) {
		// code...

		$ac_contact_id = $val->id;
		echo "id: " . $ac_contact_id . "<br>";

		delete_contact($ac_contact_id);
	}


        $offset+= $limit;
        $page++;

	  if( $page >= 1000 ){
	      exit; //prevents inifinite loops
	  }

} while ( count( $all_contacts->contacts == $limit));



