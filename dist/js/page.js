var Page={editor:"",originalHTML:"",init:function(){this.bindUIActions(),this.originalHTML=$("#page-content").html()},bindUIActions:function(){$(".js-editor-start").on("click",Page.startHandler),$(".js-btn-cancel").on("click",Page.cancelHandler),$(".js-btn-save").on("click",Page.savePage)},startHandler:function(){Page.editor=ace.edit("editor"),Page.editor.setValue(Page.originalHTML),Page.configEditor(),Page.startLiveUpdate(),Page.toggleButtons()},cancelHandler:function(){$("#page-content").html(Page.originalHTML),Page.resetPage()},resetPage:function(){$("#editor").html(""),$("#editor").attr("class","full-width"),Page.editor.destroy(),Page.toggleButtons()},toggleButtons:function(){$(".js-editor-start").toggle(),$(".js-editor-running").toggle()},configEditor:function(){var e=Page.editor;e.setTheme("ace/theme/monokai"),e.getSession().setMode("ace/mode/html"),e.getSession().setUseWrapMode(!0),e.getSession().setUseWorker(!1),e.focus(),e.gotoLine(1),window.scrollTo(0,$(".content").offset().top),e.$blockScrolling=1/0},startLiveUpdate:function(){var e=null;Page.editor.on("change",function(){e&&clearTimeout(e),e=setTimeout(function(){$("#page-content").html(Page.editor.getValue())},500)})},savePage:function(){$.ajax({type:"POST",url:"page.php",data:"action=pageUpdate&new_content="+encodeURIComponent(Page.editor.getValue())+"&pageurl="+$("#page-content").data("pageid"),dataType:"text",success:function(e){""===e?document.location.reload():alert("The page was not saved: "+e)}})}};$(document).ready(function(){Page.init()});