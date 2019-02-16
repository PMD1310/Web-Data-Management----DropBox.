<?php
//echo "<title> Photo  App </title>";
//echo "<h1><center> DropBox using Cloud Storage - Photo App!</center> </h1> ";
// display all errors on the browser
error_reporting(E_ALL);
ini_set('display_errors','On');
require_once 'demo-lib.php';
demo_init(); // this just enables nicer output

// if there are many files in your Dropbox it can take some time, so disable the max. execution time
set_time_limit( 0 );

require_once 'DropboxClient.php';

/** you have to create an app at @see https://www.dropbox.com/developers/apps and enter details below: */
/** @noinspection SpellCheckingInspection */
$dropbox = new DropboxClient( array(
	'app_key' => "qsiqt1kc49865zn",      // Put your Dropbox API key here
	'app_secret' => "s6bqteddzgtsqdq",   // Put your Dropbox API secret here
	'app_full_access' => false,
) );
/**
 * Dropbox will redirect the user here
 * @var string $return_url
 */
$return_url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] . "?auth_redirect=1";

// first, try to load existing access token
$bearer_token = demo_token_load( "bearer" );

if ( $bearer_token ) {
	$dropbox->SetBearerToken( $bearer_token );
	//echo "loaded bearer token: " . json_encode( $bearer_token, JSON_PRETTY_PRINT ) . "\n";
} elseif ( ! empty( $_GET['auth_redirect'] ) ) // are we coming from dropbox's auth page?
{
	// get & store bearer token
	$bearer_token = $dropbox->GetBearerToken( null, $return_url );
	demo_store_token( $bearer_token, "bearer" );
} elseif ( ! $dropbox->IsAuthorized() ) {
	// redirect user to Dropbox auth page
	$auth_url = $dropbox->BuildAuthorizeUrl( $return_url );
	die( "Authentication required. <a href='$auth_url'>Continue.</a>" );
}

if(isset($_GET["delete"])){
	$fileName = $_GET["delete"];
	$fileArray = $dropbox->GetFiles("",false);

	foreach($fileArray as $key=>$value){
		if((string)$fileName == (string)$key){
			$dropbox->Delete($value->path); 
			echo "Image Deleted";
		}
 	}
 }
 
if(isset($_FILES["fileToUpload"])){
$target_dir = "C:/xampp/htdocs/project6/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
$dropbox->UploadFile($target_file);
unlink($target_file);
//header("location: album.php");
} 
} 



?>
<html>
<head>
<title> Photo Application </title>
</head>
<body>
<style>
.class1 {
    background-color: lightgrey;
    width: 300px;
    border: 25px solid green;
    padding: 25px;
    margin: 25px;
}
.class2 {
    background-color: lightgrey;
    width: 300px;
    border: 25px solid green;
    padding: 25px;
    margin: 25px;
}
.class3 {
    background-color: lightgrey;
    width: 300px;
    border: 25px solid green;
    padding: 25px;
    margin: 25px;
}
.button {
    background-color: #4CAF50;
    border: none;
    color: white;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
}


body {
    background-color: lightyellow;
}
</style>
<div class = "class1" id="pmd" style=" margin-left: 10px; margin-right: 10px; margin-top: 20px; height: 100px;width:1000px">
<h1> <u><i><b>DropBox using Cloud Storage : Image Application </b></i></u></h1>
</div>
<div class = "class3">
<form action="<?= $_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data">           
<h2><u>Upload Here..</u><h2><input type="file" name="fileToUpload"/>
<br>
<input type="submit" class="button" value="Upload" name="upload">
</form>
</div>
<div class = "class2" id = "firstdiv"> 
<?php
 $fileList = $dropbox->GetFiles("",false);               
?>
<?php 
$file="";	    
$i=0;
foreach($fileList as $key=>$value){
?>
<h2><u>File Name:</u></h2>
<?php echo $key;?>
<h3><i><u>Click to Display!</u></i></h3>

<a href="album.php?show=<?php echo $key;?>"><br><br>
<?php echo $key;?></a><br>

<a href="album.php?delete=<?php echo $key;?>"><button type="button" class = "button">Delete</button></a>




<?php   
 }   
?>
</div>
<div id="simg" style=" margin-left: 10px; margin-right: 10px; margin-top: 20px; height: 100px;width:2000px">
<?php 
if(isset($_GET['show'])){
echo "<img style=\" display: block; margin: 10px;\" src='".$dropbox->GetLink($_GET['show'],false)."'/></br>"; 
 }

?>
</div>
</body>
</html>
<?php
if(isset($_FILES["fileToUpload"])){
$target_dir = "C:/xampp/htdocs/project6/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
$dropbox->UploadFile($target_file);
unlink($target_file);
//header("location: album.php");
} 
} 


?>