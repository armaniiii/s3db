<?php
	ini_set('display_errors',0);
	if($_REQUEST['su3d']) {
		ini_set('display_errors',1);
	}
	if(file_exists('config.inc.php')) {
		include('config.inc.php');
	} else {
		Header('Location: index.php');
		exit;
	}

	include_once (S3DB_SERVER_ROOT.'/dbstruct.php');
	include_once (S3DB_SERVER_ROOT.'/s3dbcore/authentication.php');
	include_once (S3DB_SERVER_ROOT.'/s3dbcore/display.php');
	include_once (S3DB_SERVER_ROOT.'/s3dbcore/callback.php');
	include_once (S3DB_SERVER_ROOT.'/s3dbcore/element_info.php');
	include_once (S3DB_SERVER_ROOT.'/s3dbcore/validation_engine.php');
	include_once (S3DB_SERVER_ROOT.'/s3dbcore/insert_entries.php');
	include_once (S3DB_SERVER_ROOT.'/s3dbcore/file2folder.php');
	include_once (S3DB_SERVER_ROOT.'/s3dbcore/update_entries.php');
	include_once (S3DB_SERVER_ROOT.'/s3dbcore/delete_entries.php');
	include_once (S3DB_SERVER_ROOT.'/s3dbcore/datamatrix.php');
	include_once (S3DB_SERVER_ROOT.'/s3dbcore/create.php');
	include_once (S3DB_SERVER_ROOT.'/s3dbcore/permission.php');
	include_once (S3DB_SERVER_ROOT.'/s3dbcore/list.php');
	include_once (S3DB_SERVER_ROOT.'/s3dbcore/S3QLRestWrapper.php');
	include_once (S3DB_SERVER_ROOT.'/s3dbcore/SQL.php');
	include_once (S3DB_SERVER_ROOT.'/s3dbcore/S3QLaction.php');
	include_once (S3DB_SERVER_ROOT.'/s3dbcore/htmlgen.php');
	include_once (S3DB_SERVER_ROOT.'/s3dbcore/acceptFile.php');
	include_once (S3DB_SERVER_ROOT.'/s3dbcore/URIaction.php');
	include_once (S3DB_SERVER_ROOT.'/s3dbcore/common_functions.inc.php');

	$format = $_REQUEST['format'];
	if($format=='') { $format='html'; }

	#if a key has been provided, validate the key
	$key=$_REQUEST['key'];
	include_once('core.header.php');
	include_once(S3DB_SERVER_ROOT.'/s3dbcore/api.php');
	#$user_proj = $GLOBALS['users_project'];
	
	if(!$_REQUEST['query']) {
		$s3ql=compact('user_id','db');
		$s3ql['from']='authority';
	}
	#now, if query is not empyt, read it, parse it, interpret it.
	if($_REQUEST['query']) {
		$query =  $_REQUEST['query'];
		$q=compact('query','format','key','user_proj','user_id','db');
		$s3ql=parse_xml_query($q);
		##now interpret the query
		$s3ql['db']=$db;
		$s3ql['user_id']=$user_id;
		$s3ql['format']=$format;
	}	
	list($valid, $data, $cols)=apiQuery($s3ql);
	if($valid) {
		#now display the data
		if(!$cols) {
			$cols = array('user_id','authentication_id','created_on');
		}
		$z = compact('data','cols', 'format');
		echo outputFormat($z);
	} else {
		echo $data;	
	}
?>