var storyboard = [];
var last_city = '';

storyboard['homepage'] = function(ok, cancel) {
	ok();	
}

storyboard['choose-city'] = function(ok, cancel) {
	ajax('cities', {}, function(result) {		
		if (result.error == false) {
			$('#city-dropdown').autocomplete({
				source: result.data,
				select: function (suggestion) {
					switchSection('choose-template');
				},
				open: function () {
					$(this).data("autocomplete").menu.element.width(80);
				}
			});
			
			$("#city-dropdown").keypress(function(event) {
				if (event.which == 13) {
					event.preventDefault();
					switchSection('choose-template');
				}
			});
			
			$('#city-dropdown').click(function() {
				$(this).val('');
			});
			
			ok();
		}
		else {
			cancel(result.message);
		}
	});
}

storyboard['choose-template'] = function(ok, cancel) {
	if ($('#city-dropdown').val() == '') {
		cancel('יש להזין עיר');
		return;
	}
	
	if ($('#city-dropdown').val() != last_city) {
		ajax('offenses', {'city': $('#city-dropdown').val()}, function(result) {
			if (result.error == false) {
				$('#template-radios').empty();
				for (key in result.data) {
					var description = result.data[key];
					$('#template-radios').append('<li><input type="radio" class="offense-radio radio-primary" name="offense-radio" value="' + key + '" id="radio_' + key + '" /> <label for="radio_' + key + '">' + description + '</label></li>');
				}
				$('#template-radios input:first').attr('checked', '');
				
				$('#template-radios input').click(function() {
					switchSection("fill-information");
				});
				
				last_city = $('#city-dropdown').val();
				
				ok();
			}
			else {
				cancel(result.message);
			}
		});	
	}
	else {
		ok();
	}
}

storyboard['fill-information'] = function(ok, cancel) {
	ajaxPlain('html-form', {'city': $('#city-dropdown').val(), 'offense': $('.offense-radio:checked').val()}, function(result) {
		$("#offense-information-form").html(result);
		
		$("#offense-information-form .field").each(function(i, field) {
			if ($(field).data('mask') != undefined) {
				$(field).inputmask($(field).data('mask'));
			}
		});
		
		$("#offense-information-form .field").keypress(function() {
			if (event.which == 13) {
				event.preventDefault();
				appeal();
			}
		});
		
		ok();	
	});
}

storyboard['appeal-success'] = function(ok, cancel) {
	ok();
}

storyboard['appeal-error'] = function(ok, cancel) {
	ok();	
}