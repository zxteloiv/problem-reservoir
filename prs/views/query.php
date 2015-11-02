<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container">

  <div class="page-header">
    <p class="lead">题目筛选查询</p>
  </div>

  <div class="panel panel-primary">
        <div class="panel-heading">筛选条件（留空或为0时不用作筛选）</div>

        <div class="panel-body " id="filters">
            <div class="row">
                <div class="col-md-3">
                    <label for="course">科目：</label>
                    <select class="form-control" id="course">
                        <option value="自动控制原理" selected>自动控制原理</option>
                        <option value="智能控制系统">智能控制系统</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="chapter">章节：</label>
                    <input type="text" value="" id="chapter" class="form-control"/>
                </div>

                <div class="col-md-3">
                    <label for="keypoints">知识点：</label>
                    <input type="text" value="" id="keypoints" class="form-control"/>
                </div>

                <div class="col-md-3">
                    <label for="description">备注含有</label>
                    <input type="text" id="description" class="form-control"/>
                </div>

            </div>

            <div class="row">
                <div class="col-md-3 vcenter">
                    <label for="points">分值：</label>
                    <input type="number" value="0" id="points" class="form-control"/>
                </div><!-- the comment is to prevent any blocks to appear
             --><div class="col-md-3 vcenter">
                    <label for="difficulty">难度：</label>
                    <select id="difficulty" class="form-control">
                        <option value="0" selected ></option>
                        <option value="10" >容易</option>
                        <option value="30" >普通</option>
                        <option value="50" >较难</option>
                        <option value="70" >很难</option>
                    </select>
                </div><!--
             --><div class="col-md-3 vcenter">
                    <label for="pid" >题目ID：</label>
                    <input id="pid" value="" type="text" class="form-control" />
                </div><!--
             --><div class="col-md-3 vcenter">
                    <button class="btn btn-default" id="reset">清空</button>
                    <button class="btn btn-success" id="search">查询</button>
                </div>
            </div>

        </div>
  </div>

  <div id="result_container" class="hidden">
      <table class="table table-striped table-bordered" id="query_result">
          <caption>查询结果</caption>
          <thead>
              <tr>
                  <td class="col-md-1 text-center">#</td>
                  <td class="col-md-3 text-center">属性</td>
                  <td class="col-md-6 text-center">内容</td>
                  <td class="col-md-2 text-center">操作</td>
              </tr>
          </thead>
          <tbody>
          </tbody>
      </table>
      <nav>
          <ul class="pagination" id="pagination">
              <li>
                  <a href='#'>
                      <span>&laquo;</span>
                  </a>
              </li>
              <li><a href="#">1</a></li>
              <li>
                  <a href="#" aria-label="Next">
                      <span aria-hidden="true">&raquo;</span>
                  </a>
              </li>
          </ul>
      </nav>
      <input type="hidden" value="" id="querypage" />
  </div>

  <div class="panel panel-info">
      <div class="panel-heading">所选题目
      </div>
      <p id="summary" class="hidden">summary:<span id="summary_points"></span></p>
      <div class="panel-body" id="select_problems_content">
      </div>
  </div>

</div>

<script src="assets/js/jquery-1.11.3.min.js"></script>
<script src="assets/js/query-page.js"></script>

