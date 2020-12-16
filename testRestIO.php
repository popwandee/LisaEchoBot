
<?php // core logic
setUrl('https://area51-dfba.restdb.io/rest/people');
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
