$(document).ready(function() {
	// Set the iframe-container to be resizeable.
	$('#iframe-container').resizable({
		maxHeight : 500,
		maxWidth : 800,
		minHeight : 240,
		minWidth : 240
	});
	// Set iframe load event if the testMatrix is checked.
	$('#iframe').load(function() {
		var $this = $(this);
		var id = $('#mailclient-select :selected').val();
		if (typeof testHashMap[id] != 'undefined' && $('#sampleTestMatrix').prop('checked')) {
			$.each(testHashMap[id], function(idx, item) {
				$this.contents().find('#hash-'+item).html('<span style="color:red;">FAIL</span>');
			});
		}
	});
	
	var gui = {};
	gui.updateFields = function () {
		if ($('input[name="sampleFile"]:checked').val() == 'testMatrix') {
			if($('input[name="premailer"]').length) {
				$('[name="premailer"]').prop('disabled', true);
				$('[for="premailer"]').css('color', 'gray');
			}
			$('[name="template"]').prop('disabled', true);
			$('[name="template"]').css('color', 'gray');
			$('[for="template"]').css('color', 'gray');
		} else {
			if($('input[name="premailer"]').length) {
				$('[name="premailer"]').prop('disabled', false);
				$('[for="premailer"]').css('color', '');
			}
			$('[name="template"]').prop('disabled', false);
			$('[name="template"]').css('color', '');
			$('[for="template"]').css('color', '');
		}
	};
	gui.updateIframeSrc = function(){
		this.updateFields();
		premailer = '';
		if ($('input[name="premailer"]:checked').length && $('input[name="sampleFile"]:checked').val() != 'testMatrix') {
			premailer = '&premailer=premailer';
		}
		template = '';
		if ($('input[name="sampleFile"]:checked').val() != 'testMatrix') {
			template = '&template=' + $('#template-select :selected').val();
		}
		sampleFile = '&sampleFile=' + $('input[name="sampleFile"]:checked').val();
		$('#iframe').attr('src', './?mailClient=' + $('#mailclient-select :selected').val() + sampleFile + template + premailer);
	};
	
	// Change if premailer
	if ($('input[name="premailer"]').length) {
		$('input[name="premailer"]').change(function(){
			gui.updateIframeSrc();
		});
	}
	
	// Change the sampleFile
	$('input[name="sampleFile"]').change(function(){
		if ($(this).prop('checked')) {
			gui.updateIframeSrc();
		}
	});
	
	// Change the template
	$('#template-select').change(function() {
		gui.updateIframeSrc();
	});
	
	// Change the mail client
	$('#mailclient-select').change(function() {
		gui.updateIframeSrc();
	});
	
	// Update the iframe on load
	gui.updateIframeSrc();
});