<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        if (isset($_GET["echostr"])) {
            $this->valid();
        } else {
            $this->responseMsg();
        }
    }

    public function valid() {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }

    public function responseMsg(){
        //get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

          //extract post data
        if (!empty($postStr)) {
                
            $postObj      = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;
            $toUsername   = $postObj->ToUserName;
            $keyword      = trim($postObj->Content);
            $time         = time();
            $textTpl      = "<xml>
                                <ToUserName><![CDATA[%s]]></ToUserName>
                                <FromUserName><![CDATA[%s]]></FromUserName>
                                <CreateTime>%s</CreateTime>
                                <MsgType><![CDATA[%s]]></MsgType>
                                <Content><![CDATA[%s]]></Content>
                                <FuncFlag>0</FuncFlag>
                            </xml>";
            if (!empty( $keyword )) {
                $msgType = "text";
                $contentStr = "坡上立着一只鹅，坡下就是一条河。宽宽的河，肥肥的鹅，鹅要过河，河要渡鹅不知是鹅过河，还是河渡鹅？";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                echo $resultStr;
            } else {
                echo "Input something...";
            }

        } else {
            echo "";
            exit;
        }
    }

    private function checkSignature(){
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce     = $_GET["nonce"];    
                
        $token  = C('WECHAT_TOKEN');
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode(  $tmpArr );
        $tmpStr = sha1( $tmpStr );
        
        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }
}