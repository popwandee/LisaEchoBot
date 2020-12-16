
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="Relax Website">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>AFAPS40-CRMA51</title>
  
</head>
<body>
   

<?php // core logic

  setUrl('https://area51-dfba.restdb.io/rest/people?q={"name": "ไพศาล"}');
$request->setMethod(HTTP_METH_GET);

$request->setHeaders(array(
'cache-control' => 'no-cache',
'x-apikey' => '5fd9fb83ff9d670638140649',
'content-type' => 'application/json'
));

try {
    $response = $request->send();
    echo $response->getBody();
} catch (HttpException $ex) {
    echo $ex;
}
           
?>
</body>
</html>
