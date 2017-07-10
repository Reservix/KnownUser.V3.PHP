<?php
use KnownUser\KnownUser;

error_reporting(E_ALL);

$configText = file_get_contents(__DIR__ . '/integrationconfig.json');
$customerID = ""; //Your Queue-it customer ID
$secretKey = ""; //Your 72 char secrete key as specified in Go Queue-it self-service platform
$queueittoken = isset( $_GET["queueittoken"] )? $_GET["queueittoken"] :'';

try
{
    $fullUrl = getFullRequestUri();
	//Verify if the user has been through the queue
    $result = KnownUser::validateRequestByIntegrationConfig($fullUrl,
			$queueittoken, $configText, $customerID, $secretKey);

    if($result->doRedirect())
    {
        //Send the user to the queue - either becuase hash was missing or becuase is was invalid
		header('Location: '.$result->redirectUrl);
        die();
    }
    if(!empty($queueittoken))
    {

		//Request can continue - we remove queueittoken form querystring parameter to avoid sharing of user specific token
        if(strpos($fullUrl,"&queueittoken=")!==false)
        {
		header('Location: '.str_replace("&queueittoken=".$queueittoken,"",$fullUrl));
        }
        else if(strpos($fullUrl,"?queueittoken=".$queueittoken."&")!==false)
        {
		header('Location: '.str_replace("queueittoken=".$queueittoken,"",  $fullUrl));
        }
        else if(strpos($fullUrl,"?queueittoken=".$queueittoken)!==false)
        {
		header('Location: '.str_replace("?queueittoken=".$queueittoken,"",  $fullUrl));
        }
		die();
    }
}
catch(\Exception $e)
{
	//log the exception
}


 function getFullRequestUri()
 {
     // Get HTTP/HTTPS (the possible values for this vary from server to server)
    $myUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] && !in_array(strtolower($_SERVER['HTTPS']),array('off','no'))) ? 'https' : 'http';
    // Get domain portion
    $myUrl .= '://'.$_SERVER['HTTP_HOST'];
    // Get path to script
    $myUrl .= $_SERVER['REQUEST_URI'];
    // Add path info, if any
    if (!empty($_SERVER['PATH_INFO'])) $myUrl .= $_SERVER['PATH_INFO'];

    return $myUrl;
 }
?>
 <html>
  <head>
    <title>Sample KnownUser Application</title>
  </head>
  <body bgcolor=white>
      <span><?=$result->queueId?></span>
    <table border="0" cellpadding="10">
      <tr>
        <td>

        </td>
        <td>
          <h1>Sample KnownUser implementation 1 </h1>
        </td>
      </tr>
    </table>

  </body>
</html>
