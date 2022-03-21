<?php
/**
 * Register the helper file inside
 * app/Providers/HelperServiceProvider.php  > register()
 * ex :
 *    - public function register(){
 *    -   requrie_once __DIR__.'/../Helpers/Common.php';
 *    - }
 * 
 */

use Illuminate\Http\Exceptions\HttpResponseException;



/**
 * Formatting the responses and exceptions and throwing 
 * from the functions
 * ex: {"code":200,"error"=>false,"message"=>"sample message"}
 * The Exceptions will be formatted inside the function
 * usage: 
 *    - public function exampleFunction(){
 *    -   try{
 *    -     $data = "this can be text or array";
 *    -     throwNewResponse('Sample message',$data,200);
 *    -   }catch(\Exception $execption){
 *    -       throwNewResponse($exception);  
 *    -    }
 *    - }
 */
function throwNewResponse($message='Exceptions',$data=[],$errorCode=500){
    if ((gettype($message) !== 'string') && ($message instanceof \Exception)) {
        if($message->getMessage()){
            $data      = (!empty($message->getTrace()))   ? $message->getTrace()   : [];
            $message   = (!empty($message->getMessage())) ? $message->getMessage() : "Something went wrong";
            $data      = $data?:[$message];
            $errorCode = 500;
        }else{
            throw new HttpResponseException($message->getResponse());
        }
    }
    $errStatus = (in_array($errorCode,[200,201])) ? false : true;
    $response = ['code'=>(int)$errorCode,'error'=>$errStatus,"message"=>$message];
    if(!empty($data)){
        $response['data'] = $data;
    }
    if($errorCode == 200 && $data == "empty"){
        $response['data'] = [];
    }
    throw new HttpResponseException(response()->json($response,$errorCode));
}


/**
 * This function is to debug data 
 * without ending the function
 * This function will write the responses 
 * into a file called debug.txt
 */
function debug($data){
    file_put_contents('debug.txt', print_r($data, 1) . PHP_EOL . PHP_EOL, FILE_APPEND);
}