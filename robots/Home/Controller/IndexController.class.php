<?php
namespace Home\Controller;
use Think\Controller;
use component\WXBizMsgCrypt;

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
            $content      = trim($postObj->Content);
            $time         = time();
            switch ($postObj->MsgType) {
                case 'text':
                    switch ($content) {
                        case '重庆时时彩':
                            $xml    = ' <xml>
                                            <ToUserName><![CDATA[%s]]></ToUserName>
                                            <FromUserName><![CDATA[%s]]></FromUserName>
                                            <CreateTime>%s</CreateTime>
                                            <MsgType><![CDATA[news]]></MsgType>
                                            <ArticleCount>1</ArticleCount>
                                            <Articles>
                                                <item>
                                                    <Title><![CDATA[%s]]></Title> 
                                                    <Description><![CDATA[%s]]></Description>
                                                    <PicUrl><![CDATA[%s]]></PicUrl>
                                                    <Url><![CDATA[%s]]></Url>
                                                </item>
                                            </Articles>
                                        </xml>';
                            $title  = '重庆时时彩玩法介绍';
                            $descr  = '一学就会，还不来！';
                            $url    = 'http://www.168cpt.com/news/shishicai/artical_236.html';
                            $picUrl = 'http://59.110.168.241/sth/img/cqssc.jpg';
                            $reStr  = sprintf($xml, $fromUsername, $toUsername, $time, $title, $descr, $picUrl, $url);
                            echo $reStr;
                            break;
                        default:
                            break;
                    }
                    break;
                default:
                    break;
            }
            // $textTpl      = "<xml>
            //                     <ToUserName><![CDATA[%s]]></ToUserName>
            //                     <FromUserName><![CDATA[%s]]></FromUserName>
            //                     <CreateTime>%s</CreateTime>
            //                     <MsgType><![CDATA[%s]]></MsgType>
            //                     <Content><![CDATA[%s]]></Content>
            //                     <FuncFlag>0</FuncFlag>
            //                 </xml>";
            // if (!empty($content)) {
            //     $msgType = "text";
            //     $contentStr = "坡上立着一只鹅，坡下就是一条河。宽宽的河，肥肥的鹅，鹅要过河，河要渡鹅不知是鹅过河，还是河渡鹅？";
            //     $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
            //     echo $resultStr;
            // } else {
            //     echo "Input something...";
            // }
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
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        
        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }

    public function getMsg(){

    }
}