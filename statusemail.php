<?php
//Test Connection
require_once("isdk.php");	
$app = new iSDK;

if( $app->cfgCon("aa478"))
{
$contactId= $_REQUEST['contactId'];
$ABBV = $_REQUEST['ABBV'];
$StatusUpdate = $_REQUEST['StatusUpdate'];
$Company = $_REQUEST['Company'];

$returnFields1 = array('contactId','Id','ActionDescription');
$query1 = array('ActionDescription' => '%'.$ABBV.'%',
				'contactId' => $contactId
				);
$notes = $app->dsQuery("ContactAction",10,0,$query1,$returnFields1);

if($notes){ //if Abbrevation is already exist
	foreach ($notes as $notes) {
		$grp = array('ActionDescription' => $ABBV.'-'.$StatusUpdate,'_Company' => $Company);
		$grpID = $notes['Id'];
		$check = $app->dsUpdate("ContactAction", $grpID, $grp);
		echo $check;
	}
}else
	{ //if Abbrevation is not yet in notes
		$grp = array(
			'ContactId' => $contactId,
			'UserID' => 1,
			'ActionType' => 'Other',
			'_Company' => $Company,
			'ActionDescription' => $ABBV.' - '.$StatusUpdate,
			'CompletionDate' => $app->infuDate(date('YmdTH:i:s', time()))
		);
		$app->dsAdd("ContactAction", $grp);
	}
}
?>

