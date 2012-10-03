var T3V3Admin = window.T3V3Admin || {};

!function ($) {
	$.extend(T3V3Admin, {
		initBuildLessBtn: function(){
			
			var jmessage = $('#system-message'),
				msgid = null;
				
			if(!jmessage.length){
				jmessage = $('<dl id="system-message">' +
									'<dt class="message">Message</dt>' +
									'<dd class="message">' +
										'<ul><li></li></ul>' +
									'</dd>' +
								'</dl>').hide().appendTo($('#system-message-container'));
			}

			//t3 added
			$('#jatoolbar-recompile').on('click', function(){
				var jrecompile = $(this);
				jrecompile.addClass('loading');
				$.get(T3V3Admin.baseurl, {'t3action': 'lesscall'}, function(rsp){
					var json = $.parseJSON(rsp);
					jrecompile.removeClass('loading');
					jmessage.show().find('li:first').html(json.error || json.successful).show();
					
					clearTimeout(msgid);
					msgid = setTimeout(function(){
						jmessage.hide();
					}, 5000);
				});
				return false;
			});

			$('#jatoolbar-themer').on('click', function(){
				if(!T3V3Admin.themermode){
					alert(T3V3Admin.langs.enableThemeMagic);
				} else {
					window.location.href = T3V3Admin.themerUrl;
				}
				return false;
			});

			//for style toolbar
			$('#jatoolbar-style-save-save').on('click', function(){
				Joomla.submitbutton('style.apply');
			});

			$('#jatoolbar-style-save-close').on('click', function(){
				Joomla.submitbutton('style.save');
			});
			
			$('#jatoolbar-style-save-clone').on('click', function(){
				Joomla.submitbutton('style.save2copy');
			});

			$('#jatoolbar-close').on('click', function(){
				Joomla.submitbutton(($(this).hasClass('template') ? 'template' : 'style') + '.cancel');
			});
		},

		initRadioGroup: function(){
			//copy from J3.0 a2
			// Turn radios into btn-group
		    $('.radio.btn-group label').addClass('btn');
		    $('.btn-group label:not(.active)').click(function() {
		        var label = $(this),
		        	input = $('#' + label.attr('for'));

		        if (!input.prop('checked')) {
		            label.closest('.btn-group').find("label").removeClass('active btn-success btn-danger btn-primary');
		            if(input.val()== '') {
		                    label.addClass('active btn-primary');
		             } else if(input.val()==0) {
		                    label.addClass('active btn-danger');
		             } else {
		            label.addClass('active btn-success');
		             }
		            input.prop('checked', true);
		        }
		    });
		    $(".btn-group input[checked=checked]").each(function() {
				if($(this).val()== '') {
		           $("label[for=" + $(this).attr('id') + "]").addClass('active btn-primary');
		        } else if($(this).val()==0) {
		           $("label[for=" + $(this).attr('id') + "]").addClass('active btn-danger');
		        } else {
		            $("label[for=" + $(this).attr('id') + "]").addClass('active btn-success');
		        }
		    });
		},
		
		initChosen: function(){
			$('#style-form').find('select').chosen({
				disable_search_threshold : 10,
				allow_single_deselect : true
			});
		},

		initT3Title: function(){
			var jptitle = $('.pagetitle');
			if (!jptitle.length) jptitle = $('.page-title');
			var titles = jptitle.html().split(':');

			jptitle.html(titles[0] + '<small>' + titles[1] + '</small>');
		},

		hideDisabled: function(){
			$('#style-form').find('[disabled="disabled"]').closest('.control-group').hide();
		},

		initPreSubmit: function(){
			var form = document.adminForm;
			if(!form){
				return false;
			}

			var onsubmit = form.onsubmit;
			form.onsubmit = function(){
				var json = {};
				$(this).find('.jaa_positions').each(function(){
					json[this.name.replace('jaa_', '')] = $(this).val();
				});

				console.log(json);

				$('#jform_params_jat3v3_positions').val(JSON.stringify(json));

				if($.isFunction(onsubmit)){
					onsubmit();
				}
			};
		},

		initLayoutPosition: function(){
			$(window).load(function(){
				setTimeout(function(){
					$('#jform_params_mainlayout').trigger('change.less');
				}, 500);
			});
		}
	});
	
	$(document).ready(function(){
		T3V3Admin.initT3Title();
		T3V3Admin.initBuildLessBtn();
		T3V3Admin.initRadioGroup();
		T3V3Admin.initChosen();
		T3V3Admin.initPreSubmit();
		T3V3Admin.initLayoutPosition();
		T3V3Admin.hideDisabled();
	});
	
}(window.$ja || window.jQuery);