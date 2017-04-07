<?php

// 检测PHP环境
if(version_compare(PHP_VERSION,'5.6.0','<'))  die('require PHP > 5.6.0 !');
 
// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG',true);

require '../ThinkPHP/ThinkPHP.php';
