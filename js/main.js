function switchSection(sectionName) {
	$('#server-error').html('');
	console.log('switching to section ' + sectionName);
	$('footer, .activeSection').fadeOut('slow').promise().done(function() {
		storyboard[sectionName](
			function() { // Ok
				$('footer, #' + sectionName).fadeIn('slow', function() {
					$('.activeSection').removeClass('activeSection');
					$('#' + sectionName).addClass('activeSection');
					
					focusFirstInput();
				});
			}, 
			
			function(message) { // Cancel	
				if (message.length > 0) {
					$('#server-error').html(message);
				}
				
				$('footer, .activeSection').fadeIn('slow');
			}
		);
	});
}

function focusFirstInput() {
	var firstInput = $(document).find('input[type=text],textarea,select').filter(':visible').filter('[tabindex=1]');
	
	if (firstInput) {
		firstInput.focus();
	}
}

function ajax(action, vars, callback) {
	vars['action'] = action;
	
	var url = "ajax/get.php";
	$.getJSON(url, vars)
	.done(function(result) {
		callback(result);
		console.log('Ajax call result: (GET, ' + action + ')', result);
		console.log('url: ', url);
		console.log('vars: ', vars);
	})
	.error(function(error) {
		alert("Ajax error");
		console.log('url: ', url);
		console.log('vars: ', vars);
		console.log(error);
	});
}

function ajaxPlain(action, vars, callback) {
	vars['action'] = action;
	
	var url = "ajax/get.php";
	$.get(url, vars)
	.done(function(result) {
		callback(result);
		console.log('Ajax call result: (GET, ' + action + ')', result);
		console.log('url: ', url);
		console.log('vars: ', vars);
	})
	.error(function(error) {
		alert("Ajax error");
		console.log('url: ', url);
		console.log('vars: ', vars);
		console.log(error);
	});
}

function ajaxPost(action, vars, callback) {
	vars['action'] = action;
	
	var url = "ajax/post.php";
	$.post(url, vars, null, 'JSON')
	.done(function(result) {
		callback(result);
		console.log('Ajax call result: (POST, ' + action + ')');
		console.log(result);
		console.log('url: ', url);
		console.log('vars: ', vars);
	})
	.error(function(error) {
		alert("Ajax error");
		console.log('url: ', url);
		console.log('vars: ', vars);
		console.log(error);
	});
}

function confirmDialog(message, callback) {
	$('#confirmDialog').fadeIn('slow', function() {
		$('#confirmDialog .yes').click(function() {
			$('#confirmDialog').fadeOut('slow', callback);
		});
		
		$('#confirmDialog .no').click(function() {
			$('#confirmDialog').fadeOut('slow');
		});
	});
}

function appeal() {
	
	confirmDialog('בלחיצה על כן, הודעה עם הפרטים שלך תשלח לעיריה. האם אתה בטוח שברצונך לשלוח ערעור זה?', function() {	
		$('#offense-information-form .field').removeClass('error');
			
		var unfilledFields = $("#offense-information-form .field").filter(function() {
			return this.value === "";
		});
		
		if (unfilledFields.length == 0) {
			var fieldsList = {};
			var fieldsForCookie = {};
			$("#loading-div").fadeIn("400", function() {
				$('#offense-information-form').find('.field').each(function(i, field) {
					fieldsList[$(field).attr('id')] = $(field).val();
					
					if ($(this).data('remember') != undefined) {
						fieldsForCookie[$(field).attr('id')] = $(field).val();
					}
				});
				
				$.cookie("remembered-fields", JSON.stringify(fieldsForCookie), { expires : 99999 });
				
				ajaxPost('send-appeal', {'city': $('#city-dropdown').val(), 'offense': $('.offense-radio:checked').val(), 'fields': fieldsList}, function(result) {
					if (result.error) {
						$("#loading-div").fadeOut("400");
						switchSection('appeal-error');
					}
					else {
						$("#loading-div").fadeOut("400");
						switchSection('appeal-success');
					}
				});
			});
		}
		else {
			$(unfilledFields).each(function(i, field) {
				$(field).addClass('error');
			});
		}
	});
}