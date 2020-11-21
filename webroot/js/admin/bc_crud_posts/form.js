$(function() {

	var fullUrl = $("#AdminBcCrudBcCrudPostsEditScript").attr('data-fullurl');
	var previewurlBase = $("#AdminBcCrudBcCrudPostsEditScript").attr('data-previewurl');

	$("input[type=text]").each(function(){
		$(this).keypress(function(e){
			if(e.which && e.which === 13) {
				return false;
			}
			return true;
		});
	});

	if (!document.queryCommandSupported('copy')) {
		$("#BtnCopyUrl").hide();
	}
	$("#BtnCopyUrl").click(function(){
		var copyArea = $("<textarea style=\" opacity:0; width:1px; height:1px; margin:0; padding:0; border-style: none;\"/>");
		copyArea.text(fullUrl);
		$(this).after(copyArea);
		copyArea.select();
		document.execCommand("copy");
		copyArea.remove();
		return false;
	});

	$("#BtnPreview").click(function(){
		window.open('', 'preview');
		var form = $(this).parents('form');
		var action = form.attr('action');
		var previewMode = 'default';
		var previewurl = previewurlBase;

		if ($("#DraftModeDetailTmp").val() == 'draft') {
			previewMode = 'draft';
		}
		if (previewurl.match(/\?/)) {
			previewurl += '&preview=' + previewMode;
		} else {
			previewurl += '?preview=' + previewMode;
		}
		form.attr('target', 'preview');
		form.attr('action', previewurl);
		form.submit();
		form.attr('target', '_self');
		form.attr('action', action);
		$.get($.baseUrl + '/bc_form/ajax_get_token?requestview=false', function(result) {
			$('input[name="data[_Token][key]"]').val(result);
		});
		return false;
	});

	/**
	 * フォーム送信時イベント
	 */
	$("#BtnSave").click(function(){
		$.bcUtil.showLoader();
		if(typeof editor_detail_tmp != "undefined") {
			editor_detail_tmp.execCommand('synchronize');
		}
		$("#BcCrudPostMode").val('save');
		$.bcToken.check(function(){
			$("#BcCrudPostForm").submit();
		}, {useUpdate: false, hideLoader: false});
		return false;
	});
});
