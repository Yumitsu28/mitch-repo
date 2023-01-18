<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function returnJson(Request $request){
        $conversation_table = [
            "Hi" => "Welcome to StationFive.",
            "Hello" => "Welcome to StationFive.",
            "Goodbye" => "Thank you, see you around.",
            "bye" => "Thank you, see you around.",
        ];

        $params = [
            "conversation_id", "message"
        ];

        $collected_keys = collect($request->all())->keys(); 

        if(in_array($collected_keys[0], $params) && in_array($collected_keys[1], $params) ) {
            if($request->message == '') {
                //check if the message has no context -- return ""Sorry, I don't understand" if not
                $returnJson = [
                    "response_id" => $request->conversation_id,
                    "response" => "Sorry, I don’t understand.",
                ]; 
                return response()->json( $returnJson ,200);
            } else {
                //if message has context 
                $message = strstr($request->message, ',', true);
                
                $converse = '';
                if($message != '') {
                    forEach($conversation_table as $key => $mes) {
                        if($key == $message) {
                            $converse = $mes;
                        } 
                    }
                    $returnJson = [
                        "response_id" => $request->conversation_id,
                        "response" => $converse ? $converse : "Sorry, I don’t understand.",
                    ]; 
                    return response()->json( $returnJson ,200);
                } else {
                    //returns error if the context in message is not in proper format
                    return response()->json( "The format on your message data is incorrect" , 400);
                }                    
            }
        } else {
            //returns error if the parameter in the request form is incorrect
            return response()->json( "The format on your request data is incorrect" , 400);
        }
       
    }
}
