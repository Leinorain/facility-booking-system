<?php
require_once('mail.php');

//CREATE PASSWORD
function random_string($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return strtoupper($randomString);
}

/*
	Check if a session user id exist or not. If not set redirect
	to login page. If the user session id exist and there's found
	$_GET['logout'] in the query string logout the user
*/
function checkFDUser()
{
	// if the session id is not set, redirect to login page
	if (!isset($_SESSION['calendar_fd_user'])) {
		header('Location: ' . WEB_ROOT . 'views/login.php');
		exit;
	}
	// the user want to logout
	if (isset($_GET['logout'])) {
		doLogout();
	}
}

function doLogin()
{
	$name 	= $_POST['name'];
	$pwd 	= $_POST['pwd'];
	
	$errorMessage = '';
	
	//$sql 	= "SELECT * FROM tbl_frontdesk_users WHERE username = '$name' AND pwd = PASSWORD('$pwd')";
	$sql 	= "SELECT * FROM tbl_users WHERE name = '$name' AND pwd = '$pwd'";
	$result = dbQuery($sql);
	
	if (dbNumRows($result) == 1) {
		$row = dbFetchAssoc($result);
		$_SESSION['calendar_fd_user'] = $row;
		$_SESSION['calendar_fd_user_name'] = $row['username'];
		header('Location: index.php');
		exit();
	}
	else {
		$errorMessage = 'Invalid username / passsword. Please try again or contact to support.';
	}
	return $errorMessage;
}


/*
	Logout a user
*/
function doLogout()
{
	if (isset($_SESSION['calendar_fd_user'])) {
		unset($_SESSION['calendar_fd_user']);
		//session_unregister('hlbank_user');
	}
	header('Location: index.php');
	exit();
}

function getBookingRecords(){
	$per_page = 10;
	$page = (isset($_GET['page']) && $_GET['page'] != '') ? $_GET['page'] : 1;
	$start 	= ($page-1)*$per_page; 
	$sql = "SELECT u.id AS uid, u.name, r.event_name AS reservationEventName,
			r.ucount, f.facility AS facility_name, r.rdate, r.status, r.comments, r.id

			FROM tbl_users u
			JOIN tbl_reservations r ON u.id = r.uid
			JOIN facilities f ON r.facility = f.id
			ORDER BY r.id DESC 
			LIMIT $start, $per_page";
	//echo $sql;
	$result = dbQuery($sql);
	$records = array();
	while($row = dbFetchAssoc($result)) {
		extract($row);
		$records[] = array("user_id" => $uid,
							"reservationEventName" => $reservationEventName,
							"count" => $ucount,
							"facility" => $facility_name,
							"res_date" => $rdate,
							"status" => $status,
							"comments" => $comments,
							"reservationId" => $id
						);
	}//while
	return $records;
	
		// $records = $records;
		// if (is_array($records))
		// 	$records = implode(',', $records);
	
		// echo "<script>console.log('Debug Objects: " . $records . "' );</script>";
	
}


function getUserRecords(){
	$per_page = 20;
	$page = (isset($_GET['page']) && $_GET['page'] != '') ? $_GET['page'] : 1;
	$start 	= ($page-1)*$per_page;
	
	$type = $_SESSION['calendar_fd_user']['type'];
	if($type == 'student') {
		$id = $_SESSION['calendar_fd_user']['id'];
		$sql = "SELECT  * FROM tbl_users u WHERE type != 'admin' AND id = $id ORDER BY u.id DESC";
	}
	else {
		$sql = "SELECT  * FROM tbl_users u WHERE type != 'admin' ORDER BY u.id DESC LIMIT $start, $per_page";
	}
	
	//echo $sql;
	
	$result = dbQuery($sql);
	$records = array();
	while($row = dbFetchAssoc($result)) {
		extract($row);
		$records[] = array("user_id" => $id,
			"user_name" => $name,
			"type" => $type,
			"status" => $status,
			"bdate" => $bdate
		);	
	}
	return $records;
}

function getHolidayRecords() {
	$per_page = 10;
	$page 	= (isset($_GET['page']) && $_GET['page'] != '') ? $_GET['page'] : 1;
	$start 	= ($page-1)*$per_page;
	$sql 	= "SELECT * FROM tbl_holidays ORDER BY id DESC LIMIT $start, $per_page";
	//echo $sql;
	$result = dbQuery($sql);
	$records = array();
	while($row = dbFetchAssoc($result)) {
		extract($row);
		$records[] = array("hid" => $id, "hdate" => $date,"hreason" => $reason);	
	}//while
	return $records;
}

function generateHolidayPagination() {
	$per_page = 10;
	$sql 	= "SELECT * FROM tbl_holidays";
	$result = dbQuery($sql);
	$count 	= dbNumRows($result);
	$pages 	= ceil($count/$per_page);
	$pageno = '<ul class="pagination pagination-sm no-margin pull-right">';
	for($i=1; $i<=$pages; $i++)	{
		$pageno .= "<li><a href=\"?v=HOLY&page=$i\">".$i."</a></li>";
	}
	$pageno .= 	"</ul>";
	return $pageno;
}

function generatePagination(){
	$per_page = 10;
	$sql 	= "SELECT * FROM tbl_users";
	$result = dbQuery($sql);
	$count 	= dbNumRows($result);
	$pages 	= ceil($count/$per_page);
	$pageno = '<ul class="pagination pagination-sm no-margin pull-right">';
	for($i=1; $i<=$pages; $i++)	{
	//<li><a href="#">1</a></li>
		//$pageno .= "<a href=\"?v=USER&page=$i\"><li id=\".$i.\">".$i."</li></a> ";
		$pageno .= "<li><a href=\"?v=USER&page=$i\">".$i."</a></li>";
	}
	$pageno .= 	"</ul>";
	return $pageno;
}

?>