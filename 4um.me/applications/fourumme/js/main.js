jQuery(document).ready(function($) {
	
		var feedbackName = $('#Form_Feedback').find('#Form_Name'),
			feedbackEmail = $('#Form_Feedback').find('#Form_Email'),
			feedbackMessage = $('#Form_Feedback').find('#Form_Message'),
			forumName = $('#Form_Forums').find('#Form_Name'),
			forumPath = $('#Form_Forums').find('#Form_Path'),
			createForm = $('.toggleCreate'),
			spacer = $('#spacer');
		
		feedbackName.attr('value', 'Name');
		feedbackEmail.attr('value', 'Email');
		feedbackMessage.attr('value', 'Message');
		
		forumName.attr('value', 'Forum Name');
		forumPath.attr('value', 'Forum Path');
		
		createForm.hide();
		spacer.hide();
		
		feedbackName.bind({
			focusin: function(e){
				var that = $(this);
				if(that.attr('value') == 'Name'){
					that.attr('value', '').css({'color': '#515F6A'});
				}
			},
			focusout: function(e){
				var that = $(this);
				if(that.attr('value') == ''){
					that.attr('value', 'Name');
				}
			}
		});
		
		feedbackEmail.bind({
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
		
		feedbackMessage.bind({
			focusin: function(e){
				var that = $(this);
				if(that.attr('value') == 'Message'){
					that.attr('value', '').css({'color': '#515F6A'});
				}
			},
			focusout: function(e){
				var that = $(this);
				if(that.attr('value') == ''){
					that.attr('value', 'Message');
				}
			}
		});
		
		forumName.bind({
			focusin: function(e){
				var that = $(this);
				if(that.attr('value') == 'Forum Name'){
					that.attr('value', '').css({'color': '#515F6A'});
				}
			},
			focusout: function(e){
				var that = $(this);
				if(that.attr('value') == ''){
					that.attr('value', 'Forum Name');
				}
			}
		});
		
		forumPath.bind({
			focusin: function(e){
				var that = $(this);
				if(that.attr('value') == 'Forum Path'){
					that.attr('value', '').css({'color': '#515F6A'});
				}
			},
			focusout: function(e){
				var that = $(this);
				if(that.attr('value') == ''){
					that.attr('value', 'Forum Path');
				}
			}
		});
			
		
		
		$('.showForum').live('click', function(e){
			
			var that = $(this);
			
			if(createForm.hasClass('open')){
				that.text('New Forum');
				createForm.slideUp().removeClass('open');
				spacer.hide();
			}else{
				that.text('Hide Create');
				createForm.slideDown().show().addClass('open');
				spacer.show();
				
			}
			
			e.preventDefault();
		});
	
});