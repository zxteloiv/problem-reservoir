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

// bind functions to the controls if needed
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
                .attr('title', escape(theFile.name))
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


