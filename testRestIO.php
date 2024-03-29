<?PHP
/**
 * Document   : RestDB CRUD Class
 * Author     : josephtinsley
 * Description: Simple PHP class to be used to interact with databases from RestDB, RestDB.io
 * http://twitter.com/josephtinsley 
*/
$test = new RestDB();
$collectionName = "mibnpeople";
$obj =  array(

);
//$return = $test->selectDocument($collectionName, $obj);
$return = $test->insertDocument($collectionName, $obj);
print_r($return);

class RestDB {

    CONST APIDB = '';
    CONST APIKEY = '';

    private $apiurl = '';
    
    public function __construct() 
    {
        $this->apiurl = 'https://'.self::APIDB.'.restdb.io/rest/';   
    } 

    //CREATE
    /**
     * INSERT A OBJECT
     * EXAMPLE INSERT QUERY STATEMENT
     * --------------------
     * $collectionName = "MyFriends";
     * $obj =  array("first_name"=>"Dennis", "last_name"=>"Crowley", "email" => "Denis.Crowley@gmail.com");
     */
    
    public function insertDocument($collectionName, $obj) 
    {
        if(!is_array($obj) || count($obj) === 0)
        {
          return false;  
        }
        $post_vars = json_encode($obj);
        $url = $this->apiurl.$collectionName;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','x-apikey:'.self::APIKEY) );
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_vars);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $return_data = curl_exec($ch);
        curl_close($ch);  
        $retVal = json_decode($return_data ,TRUE);
        
        if(empty($retVal['_id']))
        {
            return false;
        }
        return $retVal['_id'];
    } 

    //RETRIEVE/READ
    /**
     * SELECT A OBJECT
     * EXAMPLE SELECT QUERY STATEMENT
     * --------------------
     * $collectionName = "MyFriends";
     * $obj =  array("email" => "Denis.Crowley@gmail.com");
     */
    public function selectDocument($collectionName, $obj) 
    {
        if( count($obj) === 0)
        {
           $obj = array(); 
        }
        $post_vars = json_encode($obj);
        $queryString = http_build_query( ['q'=>$post_vars] );
        
        $url = $this->apiurl.$collectionName .'?'.$queryString;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','x-apikey:'.self::APIKEY) );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $return_data = curl_exec($ch);
        curl_close($ch);
        $retVal = json_decode($return_data, TRUE);
        
        if(empty($retVal[0]))
        {
            return false;
        }
        return $retVal;
    } 

    //UPDATE
    /**
     * UPDATE A OBJECT
     * EXAMPLE UPDATE QUERY STATEMENT
     * --------------------
     * $collectionName = "MyFriends";
     * $objectId = "587511c695490046000010a8";
     * $obj =  array("email" => "Denis.Crowley@gmail.com");
     */
    public function updateDocument($collectionName, $objectId, $obj) 
    {
        if( count($obj) === 0)
        {
           $obj = array(); 
        }
        $post_vars = json_encode($obj);
        $url = $this->apiurl.$collectionName.'/'.$objectId;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','x-apikey:'.self::APIKEY) );
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_vars);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $return_data = curl_exec($ch);
        curl_close($ch);  
        $retVal = json_decode($return_data ,TRUE);

        if(empty($retVal['_id']))
        {
            return false;
        }
        return $retVal['_id'];  
    } 
 
    //DELETE
    /**
     * DELETE A OBJECT
     * EXAMPLE DELETE QUERY STATEMENT
     * --------------------
     * $collectionName = "MyFriends";
     * $objectId = "587511c695490046000010a8";
     */
    public function deleteDocument($collectionName, $objectId) 
    {
        $url = $this->apiurl.$collectionName.'/'.$objectId;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','x-apikey:'.self::APIKEY) );
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $return_data = curl_exec($ch);
        curl_close($ch);  
        $retVal = json_decode($return_data ,TRUE);
        
        if(!empty($retVal['result'][0]))
        {
            return $retVal['result'][0]; // RECORD DELETED, RETURN ID
        }
        return 0; //RECORD NOT DELETED
    } 

}//END CLASS
