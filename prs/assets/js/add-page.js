/*!
 * Script for the Add Page.
 */

if (typeof jQuery === 'undefined') {
  throw new Error('jQuery must be included before.')
}

var g_problem = {};
var g_files = {};
var g_fileReaders = {};

// initialization:
// in case which we may load items by using AJAX API
// and fill in all the options of controls in the DOM
+function($) {
}(jQuery);

// Bind function to file controls
// Show thumbnail in the img control
var handleFileChange = function(evt) {
    if (this.files.length !== 1) { return; }

    // find id: for file0, file1, ..., fileN => IDs are 0, 1, ..., N
    var re = new RegExp(/\d+/g);
    var arr = re.exec($(this).attr('id'));
    if (!arr) { return; }

    var id = parseInt(arr[0]);

    var file = this.files[0];
    var reader = new FileReader();
    g_files[id] = file;
    g_fileReaders[id] = reader;

    reader.onload = (function(theFile, id) {
        return function(e) {
            // Render thumbnail.
            $('#thumb' + id)
                .attr('src', e.target.result)
                .attr('title', encodeURI(theFile.name))
                .removeClass('hidden');
        };
    })(file, id);

    reader.readAsDataURL(file);
};

+function($) {
    $('#problem-contents')
        .find('input:file')
        .change(handleFileChange);
}(jQuery);

// Bind submit function to the button
// do the submit
var saveImg = function() {
    var files_data = [];
    var max_file_now = $('#problem-contents').find('input:file').length;
    for (var id = 0; id < max_file_now; id++) {
        if (!g_files[id]) continue;
        var img_obj = $('#thumb' + id);
        if (!img_obj) continue;
        var dataurl = img_obj.attr('src');
        if (!dataurl) continue;

        var file = {
            name: g_files[id].name,
            size: g_files[id].size,
            type: g_files[id].type,
            content: extractBase64PartFromDataUrl(dataurl)
        };

        files_data.push(file);
    }

    // save img files first
    $.ajax('img/save', {
        method: "POST",
        dataType: 'json',
        data: {
            filecount: files_data.length,
            files: JSON.stringify(files_data)
        },
        success: function(data, state, jqXHR) {
            if (!data || !('errno' in data) || !('idmap' in data)) {
                alert('上传失败');
                return;
            }
            if (data['errno'] > 0) {
                alert(data['errno'] + ":" + data['errmsg']);
                return;
            }

            saveProblem(data['idmap']);
        },
        error: function(jqXHR, state, err) {
            alert(state + ":保存图片失败\n" + err);
        }
    });
};

var saveProblem = function(idmap) {
    // As the database stores course as varchar, we use the text instead of value.
    var course = $('#course option:selected').text();

    var chapter = $('#chapter').val();
    var keypoints = $('#keypoints').val();
    var points = $('#points').val();
    var difficulty = $('#difficulty').val();
    var description = $('#description').val();

    // currently we only have pictures in problem content
    var content = [];
    for (var i = 0; i < idmap.length; i++) {
        var img_id = idmap[i]['id'];
        var primitive = {
            type: 'image',
            img_id: img_id
        };
        content.push(primitive);
    }

    var data = {
        course: course, chapter: chapter,
        keypoints: keypoints, description: description,
        points: points, difficulty: difficulty,
        content: JSON.stringify(content)
    };

    $.ajax('problem/create', {
        method: "POST",
        dataType: "json",
        data: data,
        success: function(data, state, jqXHR) {
            g_files = {};
            g_fileReaders = {};
            $('#problem-contents')
                .find('img.problem-img')
                .addClass('hidden')
                .attr('src', '')
                .attr('title', '')
                ;

            $('#problem-contents')
                .find('input:file')
                .each(function(){
                    // reset the file control
                    var e = $(this);
                    e.wrap('<form>').closest('form').get(0).reset();
                    e.unwrap();
                })
                ;
            alert('保存成功');
        },
        error: function(jqXHR, state, err) {
            alert(state + ":保存题目失败\n" + err);
        }
    });

};

var handleSubmitClick = function(evt) {
    saveImg();
};

var extractBase64PartFromDataUrl = function(dataurl) {
    // DataURL scheme: "data:[media-type][;charset=<charset>][;base64],<content>"
    // Refer to https://en.wikipedia.org/wiki/Data_URI_scheme
    var pos = dataurl.indexOf(',');
    if (pos < 0 || pos == dataurl.length - 1) return '';
    return dataurl.substr(pos + 1);
};

+function($) {
    $('#submit_btn')
        .click(handleSubmitClick)
        ;

}(jQuery);


