jQuery(document).ready(function($) {
	
	var email = $('#Form_Email'),
		uName = $('#Form_Name'),
		pass = $('#Form_Password'),
		fakePass = $('#fakePass'),
		confPass = $('#Form_PasswordMatch'),
		confFakePass = $('#confFakePass');
	
	
	email.attr('value', 'Email');
	uName.attr('value', 'Username');
	fakePass.attr('value', 'Password');
	confFakePass.attr('value', 'Confirm Password');
	
	pass.hide();
	confPass.hide();
	
	
	email.bind({
		focusin: function(e){
			var that = $(this);
			if(that.attr('value') == 'Email'){
				that.attr('value', '').css({'color': '#515F6A'});
			}
		},
		focusout: function(e){
			var that = $(this);
			if(that.attr('value') == ''){
				that.attr('value', 'Email');
			}
		}
	});
	
	uName.bind({
		focusin: function(e){
			var that = $(this);
			if(that.attr('value') == 'Username'){
				that.attr('value', '').css({'color': '#515F6A'});
			}
		},
		focusout: function(e){
			var that = $(this);
			if(that.attr('value') == ''){
				that.attr('value', 'Username');
			}
		}
	});
	
	
	fakePass.bind({
		focusin: function(e){
				fakePass.hide();
				pass.show();
		}
	});
	
	confFakePass.bind({
		focusin: function(e){
				confFakePass.hide();
				confPass.show();
		}
	});
	
	pass.bind({
		focusout: function(e){
			var that = $(this);
			if(that.attr('value') == ''){
				pass.hide();
				fakePass.show();	
			}			
		}
	});
	
	confPass.bind({
		focusout: function(e){
			var that = $(this);
			if(that.attr('value') == ''){
				confPass.hide();
				confFakePass.show();	
			}			
		}
	});

   
});