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
                        case '天津时时彩':
                            $xml     = '<xml>
                                            <ToUserName><![CDATA[%s]]></ToUserName>
                                            <FromUserName><![CDATA[%s]]></FromUserName>
                                            <CreateTime>%s</CreateTime>
                                            <MsgType><![CDATA[news]]></MsgType>
                                            <ArticleCount>2</ArticleCount>
                                            <Articles>
                                                <item>
                                                    <Title><![CDATA[%s]]></Title> 
                                                    <Description><![CDATA[%s]]></Description>
                                                    <PicUrl><![CDATA[%s]]></PicUrl>
                                                    <Url><![CDATA[%s]]></Url>
                                                </item>
                                                <item>
                                                    <Title><![CDATA[%s]]></Title> 
                                                    <Description><![CDATA[%s]]></Description>
                                                    <PicUrl><![CDATA[%s]]></PicUrl>
                                                    <Url><![CDATA[%s]]></Url>
                                                </item>
                                            </Articles>
                                        </xml>';
                            $title1  = '天津时时彩玩法介绍1';
                            $title2  = '天津时时彩玩法介绍2';
                            $descr1  = '天津时时彩1————好玩！';
                            $descr2  = '天津时时彩2————好玩！';
                            $url1    = 'http://www.168cpt.com/news/shishicai/artical_93.html';
                            $url2    = 'http://www.168cpt.com/news/shishicai/artical_174.html';
                            $picUrl1 = 'http://59.110.168.241/sth/img/tjssc1.jpg';
                            $picUrl2 = 'http://59.110.168.241/sth/img/tjssc2.jpg';
                            $reStr  = sprintf($xml, $fromUsername, $toUsername, $time, $title1, $descr1, $picUrl1, $url1, $title2, $descr2, $picUrl2, $url2);
                            echo $reStr;
                            break;
                        default:
                            break;
                    }
                    break;
                default:
                    break;
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