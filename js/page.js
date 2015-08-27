var Page = {
    editor: '', // The editor object. Set in startEditor
    originalHTML: '', 
    init : function() {
        this.bindUIActions();
        // Save original HTML for cancel
        this.originalHTML = $('#page-content').html();
    },
    bindUIActions: function(){
        $('.js-editor-start').on('click', Page.startHandler);
        $('.js-btn-cancel').on('click', Page.cancelHandler);
        $('.js-btn-save').on('click', Page.savePage);
    },

    startHandler: function(){
        // Start the editor and set it's content
        Page.editor = ace.edit("editor");
        Page.editor.setValue(Page.originalHTML);
        // Configure
        Page.configEditor();
        Page.startLiveUpdate();

        // Change to action buttons
        Page.toggleButtons();
    },
    cancelHandler: function(){
        // restore the original HTML
        $('#page-content').html(Page.originalHTML);
        Page.resetPage();
    },
    resetPage: function(){
        // Remove editor from page and end sessions
        $('#editor').html('');
        $('#editor').attr('class', 'full-width');
        Page.editor.destroy();
        // Change to edit page button
        Page.toggleButtons();
    },
    toggleButtons: function(){
        $('.js-editor-start').toggle();
        $('.js-editor-running').toggle();
    },
    configEditor: function(){
        var editor = Page.editor;
        editor.setTheme("ace/theme/monokai");
        editor.getSession().setMode("ace/mode/html");
        editor.getSession().setUseWrapMode(true);
        editor.getSession().setUseWorker(false); // disables hints
        editor.focus(); editor.gotoLine(1); // focus editor and move to topline
        window.scrollTo(0, $('.content').offset().top); // move window back to top to show editor element
        editor.$blockScrolling = Infinity; // was told by the editor to do this
    },
    startLiveUpdate: function(){
        // Updates the page when typing has stopped for 500ms
        var editorTimer = null;
        Page.editor.on('change', function(){
            if (editorTimer) {
                clearTimeout(editorTimer);   // clear previous pending timer
            }
            editorTimer = setTimeout(function(){
                $('#page-content').html(Page.editor.getValue());
            }, 500);
        });
    },
    savePage: function(){
        $.ajax({
            type: 'POST',
            url : 'page.php',
            data:'action=pageUpdate'+
                 '&new_content='+ encodeURIComponent(Page.editor.getValue())+
                 '&pageurl='+$('#page-content').data('pageid'),
            dataType: 'text', // server return type
            success: function(response){
                if (response === ''){
                    // refresh the page
                    document.location.reload();
                }
                else{
                    // show the error dramatically
                    alert('The page was not saved: '+response);
                }
            }
        });
    }
};
$(document).ready(function () {
    Page.init();
});