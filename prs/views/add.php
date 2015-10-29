<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container">

  <div class="page-header">
    <p class="lead">录入新的题目</p>
  </div>

<div class="panel panel-primary">
<div class="panel-heading">题目属性</div>
<div class="panel-body">
<div class="row form-inline">

    <div class="col-md-4 form-group">
        <label for="course">科目：</label>
        <select class="form-control">
            <option value="1" selected>自动控制原理</option>
        </select>
    </div>

    <div class="col-md-4 form-group">
        <label for="chapter">章节：</label>
        <input type="text" value="" id="chapter" class="form-control"/>
    </div>

    <div class="col-md-4 form-group">
        <label for="keypoints">知识点：</label>
        <input type="text" value="" id="keypoints" class="form-control"/>
    </div>

</div>

<div class="row form-inline">

    <div class="col-md-4 form-group">
        <label for="points">分值：</span>
        <input type="number" value="5" id="points" class="form-control"/>
    </div>

    <div class="col-md-4 form-group">
        <label for="difficulty">难度：</label>
        <select id="difficulty" class="form-control">
            <option value="10" >容易</option>
            <option value="30" selected>普通</option>
            <option value="50" >较难</option>
            <option value="70" >很难</option>
        </select>
    </div>

</div>
</div>
</div>

<div class="panel panel-primary">
    <div class="panel-heading">题目内容</div>
    <div class="panel-body">
        <div class="form-group">
            <label for="description">简单描述：</label>
            <textarea class="form-control" rows="3" id="description"></textarea>
        </div>

        <div class="form-group">
            <label>上传题目截图：（多张图片时需要按顺序选择）</label>
            <button id="new-pic" class="btn btn-primary hidden">新增一张图</button>
        </div>

        <div id="problem-contents" class="form-horizontal">
            <div class="row form-group">
                <div class="col-md-4">
                    <input type="file" name="file0" id="file0"/>
                </div>
                <div class="col-md-6">
                    <img id="thumb0" class="problem-img img-thumbnail hidden" />
                </div>
            </div>

            <div class="row form-group">
                <div class="col-md-4">
                    <input type="file" name="file1" id="file1"/>
                </div>
                <div class="col-md-6">
                    <img id="thumb1" class="problem-img img-thumbnail hidden" />
                </div>
            </div>

            <div class="row form-group">
                <div class="col-md-4">
                    <input type="file" name="file2" id="file2"/>
                </div>
                <div class="col-md-6">
                    <img id="thumb2" class="problem-img img-thumbnail hidden" />
                </div>
            </div>

            <div class="row form-group">
                <div class="col-md-4">
                    <input type="file" name="file3" id="file3"/>
                </div>
                <div class="col-md-6">
                    <img id="thumb3" class="problem-img img-thumbnail hidden" />
                </div>
            </div>

        </div>
    </div>
</div>
</div>


</div> 

<script src="assets/js/jquery-1.11.3.min.js"></script>
<script src="assets/js/add-page.js"></script>

