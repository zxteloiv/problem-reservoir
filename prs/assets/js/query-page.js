/*!
 * Script for the Add Page.
 */

if (typeof jQuery === 'undefined') {
  throw new Error('jQuery must be included before.')
}

// Bind function to reset button:
// Reset all control value
+function($) {
    $('#reset').click(function(evt){
        $('#chapter').val('');
        $('#keypoints').val('');
        $('#description').val('');
        $('#points').val(0);
        $('#difficulty option:eq(0)').prop('selected', true);
        $('#pid').val('');
    });

    $('#filters').change(function(){
        $('#querypage').val('');
    });
}(jQuery);

// Bind function to search button:
// Build a query parameter and send it to API using ajax.
var handleSearchClick = function(evt) {
    doSearch();
};

var doSearch = function() {
    var raw_params = {
        course: $('#course').val(),
        chapter: $('#chapter').val(),
        keypoints: $('#keypoints').val(),
        difficulty: parseInt($('#difficulty').val()),
        description: $('#description').val(),
        points: parseInt($('#points').val()),
        page: parseInt($('#querypage').val())
    };
    var params = {};
    for (var k in raw_params) {
        if (!raw_params[k]) { continue; };
        params[k] = raw_params[k];
    }

    var pid = $('#pid').val();
    if (pid && !jQuery.isEmptyObject(params)) {
        alert('其他条件与题目ID同时使用时将视为无效');
        params = {pid: pid};
    }

    jQuery.ajax('problem/search', {
        method: "POST",
        dataType: "json",
        data: params,
        success: onSearchAjaxSuccess,
        error: onSearchAjaxError
    });
};

var onSearchAjaxError = function(jqXHR, state, err) {
    alert(state + ":" + err);
};

var onSearchAjaxSuccess = function(data, state, jqXHR) {
    if (!data ||
            ['errno', 'errmsg', 'pageid', 'pagenum', 'data'].map(function(val){
                return !(val in data);
            }).reduce(function(prevVal, curVal){
                return (prevVal || curVal);
            }))
    {
        alert('服务器返回错误结果');
        return;
    }
    if (data['errno'] > 0) {
        alert(data['errno'] + ":" + data['errmsg']);
        return;
    }

    initQueryResult(data['data']);
    initPagination(data['pageid'], data['pagenum']);
};

var initQueryResult = function(data) {
    var dataTable = $('#query_result').find('tbody');
    dataTable.html('').removeClass('hidden');

    for (var i in data) {
        var problem = data[i];
        var row = $('<tr>');
        var id = $('<td>').addClass('col-md-1').text(problem['pid']);
        var prop = $('<td>').addClass('col-md-3');
        var content = $('<td>').addClass('col-md-6').css('max-width', '100%');
        var op = $('<td>').addClass('col-md-2');

        initProblemProperties(problem, prop);
        initProblemContent(problem, content);
        initOperationButton(problem, op);

        row.append(id);
        row.append(prop);
        row.append(content);
        row.append(op);
        dataTable.append(row);
    };
};

var initOperationButton = function(problem, op) {
    var pid = problem['pid'];
    var points = problem['points'];
    var del_btn = $('<button>')
        .addClass('btn btn-danger')
        .attr('del_pid', pid)
        .append($('<span>').addClass('glyphicon glyphicon-remove').text('删除'))
        .click(handleDelBtnClick)
        ;

    var select_btn = $('<button>')
        .addClass('btn btn-success')
        .attr('select_pid', pid)
        .attr('points', points)
        .append($('<span>').addClass('glyphicon glyphicon-ok').text('选中'))
        .click(handleSelectBtnClick)
        ;

    var group = $('<div>')
        .addClass('btn-group')
        .attr('role', 'group')
        .append(del_btn).append(select_btn)
        ;

    op.append(group);
};

var handleDelBtnClick = function(evt) {
    var del_btn = $(this);
    if (!confirm('确定从题库中删除此题吗?')) { return; }

    var row = del_btn.closest('tr');
    var pid = del_btn.attr('del_pid');

    $.ajax('problem/del', {
        method: 'POST',
        dataType: 'json',
        data: {pid: pid},
        success: function(data, state, jqXHR) {
            if (!data || !('errno' in data) || !('errmsg' in data)) {
                alert('服务器连接失败');
                return;
            }

            if (data['errno'] > 0) {
                alert(data['errno'] + ":" + data['errmsg']);
                return;
            }

            row.remove();
        },
        error: function(jqXHR, state, err) {
            alert(state + ":" + err);
            return;
        }
    });
};
    
var handleSelectBtnClick = function(evt) {
    var select_btn = $(this);
    var pid = select_btn.attr('select_pid');
    var problem_points = parseInt(select_btn.attr('points'));
    var row = select_btn.closest('tr');

    var new_row = $('<div>').addClass('row')
        .attr('pid', pid).attr('points', problem_points);
    var summary = $('#summary').removeClass('hidden');
    var points = parseInt($('#summary_points').text());

    if (!points) { points = 0; }
    if (!problem_points) { problem_points = 0; }

    points += problem_points;
    $('#summary_points').text(points);

    var contents = row.find('img');
    contents.detach().after($('<br/>'));
    new_row.append(contents);
    $('#select_problems_content').append(new_row).append($('<hr/>'));
    row.remove();
};

var initProblemContent = function(problem, content) {
    if (!('content' in problem) || !problem['content']) { return; }
    for (var i in problem['content']) {
        var primitive = problem['content'][i];
        if (!('type' in primitive) || !primitive['type']) { continue; }

        if (primitive['type'] == 'image') { 
            if (!('img_id' in primitive) || !primitive['img_id']) { continue; }
            img_id = primitive['img_id'];
            if (content.children().length > 0) {content.append($('<br/>'));}

            content.append($('<img>').addClass('img-responsive')
                    .css('max-width', '500px')
                    .attr('src', 'img/loadImg/' + img_id));
        }
    }
};

var initProblemProperties = function(problem, prop) {
    if ('course' in problem && problem['course'] && problem['course'] != "") {
        if (prop.children().length > 0) prop.append($('<br/>'));
        prop.append($('<span>').text(problem['course']))
    }

    if ('chapter' in problem && problem['chapter'] && problem['chapter'] != "") {
        if (prop.children().length > 0) prop.append($('<br/>'));
        prop.append($('<span>').text(problem['chapter']))
    }

    if ('keypoints' in problem
            && problem['keypoints'] && problem['keypoints'] != "") {
        if (prop.children().length > 0) prop.append($('<br/>'));
        prop.append($('<span>').text(problem['keypoints']))
    }

    if ('difficulty' in problem
            && problem['difficulty'] && problem['difficulty'] != "") {
        if (prop.children().length > 0) prop.append($('<br/>'));
        prop.append($('<span>').text('难度:' + problem['difficulty']));
    }

    if ('points' in problem
            && problem['points'] && problem['points'] != "") {
        if (prop.children().length > 0) prop.append($('<br/>'));
        prop.append($('<span>').text('分值:' + problem['points']));
    }
};

var initPagination = function(pageid, pagenum) {
    var pagination = $('#pagination').html('');
    if (pageid <= 0 || pagenum <= 0 || pageid > pagenum) {
        $('result_container').addClass('hidden');
        return;
    }

    pageid = parseInt(pageid);
    pagenum = parseInt(pagenum);

    if (pageid > 1) {
        var previous = $('<li>').append($('<a>').attr('pageid', pageid - 1).append(
            $('<span>').addClass('glyphicon-backward glyphicon')
        ));
        previous.click(getHandlerOnPageClick(pageid - 1));
        pagination.append(previous);
    }

    var candidate = [pageid - 4, pageid - 3, pageid - 2, pageid - 1, pageid, pageid + 1, pageid + 2, pageid + 3, pageid + 4];
    for (var val in candidate) {
        if (val < 1 || pagenum < val) { continue; }

        var page = $('<li>').append($('<a>').attr('pageid', val).text(val));
        page.click(getHandlerOnPageClick(val));
        if (pageid == val) {
            page.addClass('active');
        }
        pagination.append(page);
    };

    if (pageid < pagenum) {
        var next = $('<li>').append( $('<a>').attr('pageid', pageid + 1).append(
            $('<span>').addClass('glyphicon-forward glyphicon')
        ));
        next.click(getHandlerOnPageClick(pageid + 1));
        pagination.append(next);
    }

    $('#result_container').removeClass('hidden');
};

var getHandlerOnPageClick = function(desired_page) {
    return function(evt) {
        var page = $(this);
        $('#querypage').val(desired_page);
        doSearch();
    };
};

+function($) {
    $('#search').click(handleSearchClick);
}(jQuery);
