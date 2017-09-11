<?php

/******************************************************************************
 * 
 * 此文件用于使包含者进程能以单例模式运行
 * （那些需要单例运行的应用引入此文件方可使用）
 * 
 * @author	liangwei@myhexin.com
 * @create	2011-05-27
 * 
 ******************************************************************************/
$lockFileName	= basename(str_replace('/', '_', $argv[0]), '.php');
$lockFilePath	= '/tmp/' . $lockFileName . '.lock';
$lockFileHdl	= fopen($lockFilePath, 'w+');
if (!$lockFileHdl) {
	echo "\nfailure to open lock file: {$lockFilePath}!\n";
	exit(0);
}
if (!flock($lockFileHdl, LOCK_EX + LOCK_NB)) {
	echo "\n{$lockFileName} process already is running!\n";
	exit(0);
}

/******************************************************************************/