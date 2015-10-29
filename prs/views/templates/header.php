<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="zh_CN">
<head>
	<meta charset="utf-8">
    <title><?php echo $title; ?></title>

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap-theme.min.css">
</head>
<body>

<nav class="navbar navbar-default navbar-static-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="/prs">首页</a>
    </div>

    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
          <li <?php if ($active_title == 'query') echo 'class="active"'; ?>><a href="query">查询题目</a></li>
          <li <?php if ($active_title == 'add') echo 'class="active"'; ?>><a href="add">增加题目</a></li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
