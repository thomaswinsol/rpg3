<?php
set_include_path('/www/zendsvr/htdocs/easypost/phpseclib1.0.15');
include('Net/SFTP.php');
/* Change the following directory path to your specification */
$local_directory = '/www/zendsvr/htdocs/pdfmail/easypost/';
$local_directory2 = '/www/zendsvr/htdocs/pdfmail/easypost/send/';
$remote_directory = '/home/Winsol/';
 
/* Add the correct FTP credentials below */

//-EASYPOST$sftp = new Net_SFTP('Portal.easypost.eu');
//-EASYPOSTif (!$sftp->login('Winsol', 'Winsol2019')) 
$sftp = new Net_SFTP('wincal.winsol.eu');
if (!$sftp->login('mailingman', 'M3YAd8Wk'))
{
    exit('Login Failed');
} 

/* We save all the filenames in the following array */
$files_to_upload = array();

/* Open the local directory form where you want to upload the files */
if ($handle = opendir($local_directory)) 
{
    /* This is the correct way to loop over the directory. */
    while (false !== ($file = readdir($handle))) 
    {
        if ($file != "." && $file != "..") 
        {
        	$pos = strpos($file, ".pdf");
			if ($pos === false) {
				 
			}
			else {
				$files_to_upload[] = $file;
			}
        }
    } 
    closedir($handle);
}
 
print_r($files_to_upload);

if(!empty($files_to_upload))
{
    /* Now upload all the files to the remote server */
	//-EASYPOST$dir=date("Y-m-d");
	
	//-EASYPOST$remote_directory = trim($dir)."/";
	$remote_directory = "";
	
	//-EASYPOST$response=$sftp->is_dir($remote_directory);
	//-EASYPOSTSTFP check directory exists
	//-EASYPOSTif ($response<>1) {
	//-EASYPOST	$sftp->mkdir($dir);
		
	//-EASYPOST}

    foreach($files_to_upload as $file)
    {
    	try {  
          $success = $sftp->put($remote_directory . $file, 
                                $local_directory . $file, 
                                 NET_SFTP_LOCAL_FILE);
                                 
                                 rename($local_directory . $file, $local_directory2 . $file);
                                 
    	}
    	catch (Exception $e) {
				die("error");
		}
    }
}
?>