(function($) {
	'use strict';
	$(window).on('load', function() {
		//fix_ver_upgrade
		$(document).ready(function() {
            

            $('#get-posts-by-term').click(function() {
                var termId = $('#term-select').val();
                fetchPosts(termId);
            });
        
            $('#get-posts-without-term').click(function() {
                fetchPosts(null); // Pass null to indicate no term
            });


            function fetchPosts(termId) {

				$.ajax({
					type: 'POST',
					url: gt_ajax_var.url,
					data: {
						action: 'get_posts_by_term_or_no_term',
						term_id: termId
					},
					success: function(response) {
						// Now you have the list of post IDs
						// You can send another AJAX request to delete these posts
						deletePosts(response.data.posts);
					}
				});


            }

			// $('#get-posts-by-term').click(function() {
			// 	var termId = $('#term-select').val();
			// 	$.ajax({
			// 		type: 'POST',
			// 		url: gt_ajax_var.url,
			// 		data: {
			// 			action: 'get_posts_by_term',
			// 			term_id: termId
			// 		},
			// 		success: function(response) {
			// 			// Now you have the list of post IDs
			// 			// You can send another AJAX request to delete these posts
			// 			deletePosts(response.data.posts);
			// 		}
			// 	});
			// });

			function deletePosts(postIds) {
                var postPerBatcch = 100;
                var totalPosts = postIds.length;
    var batchNumber = 0;
				// Check if postIds is defined and is an array
				// Function to send AJAX request to delete a batch of posts
				function deleteBatch(batch) {
					$.ajax({
						type: 'POST',
						url: gt_ajax_var.url,
						data: {
							action: 'delete_posts_by_ids',
							post_ids: batch
						},
						success: function(response) {
							console.log(response);

                            batchNumber++;
                            const d = new Date();
                            var remainingPosts = postIds.length - postPerBatcch;
                            var message = $('<div style="display: none;">Batch ' + batchNumber + 
                                            ' deleted successfully. ' + remainingPosts + 
                                            ' posts remaining. '+d+'</div>');

                            // Prepend with fadeIn animation
                            $('#success-message').prepend(message);
                            message.slideDown(400); 

                            // Remove all notifications except for the top two
                            $('#success-message div:not(:lt(5))').fadeOut(300, function() {
                                $(this).slideUp(300, function() {
                                    $(this).remove();
                                });
                            });
                        




							// Remove the deleted batch from the original array
							postIds.splice(0, postPerBatcch);
							// Check if there are more posts to delete
							if (postIds.length > 0) {
								deleteNextBatch(postPerBatcch); // Delete the next batch
							} else {
								console.log('All posts deleted');

                                var allDoneMessage = $('<div style="display: none;">All posts deleted</div>');
                                $('#success-message').prepend(allDoneMessage);
                                allDoneMessage.fadeIn();
            
                                $('#success-message div:not(:lt(5))').fadeOut(300, function() {
                                    $(this).slideUp(300, function() {
                                        $(this).remove();
                                    });
                                });
							}
						}
					});
				}
				// Function to delete the next batch of posts
				function deleteNextBatch(postPerBatcch) {
					var batch = postIds.slice(0, postPerBatcch); // Get the next 10 posts
					deleteBatch(batch);
				}
				deleteNextBatch(postPerBatcch); // Start the deletion process
			}
			console.log($('.gt-select2'))
			$('.gt-select2').select2();
			$('.gt-select2-ajax').select2({
				ajax: {
                    type: 'POST',
					url: gt_ajax_var.url,
					delay: 250,
					data: function(params) {

console.log(params.term)
						return {
							searchTerm: params.term, // search term
							action: 'search_members' // the action in PHP
						};
					},
					processResults: function(data) {
						return {
							results: data
						};
					},
					cache: true
				},
				minimumInputLength: 1,
                    templateResult: formatGroupedResults
			});
		});

        function formatGroupedResults(item) {
            // Custom formatting can go here
            // For example, you can return different HTML based on whether the item is a group or an option
            if (item.children) {
                // This is a group
                return $('<strong>' + item.text + '</strong>');
            } else {
                // This is a regular item
                return item.text;
            }
        }

		$(function() {
			$('.gt-color-field').wpColorPicker();
		});

		$('.fix_ver_upgrade').click(function() {
			$.post(gt_ajax_var.url, {
				action: 'fix_ver_upgrade_ajax',
			}).done(function(data) {
				window.location.reload();
			}).fail(function(data) {});
		})

		$('#Upgrade-Genealogical-Tree-Database').click(function() {
			$(this).find('span').addClass('spinner is-active')
			$.post(gt_ajax_var.url, {
				action: 'fix_ver_upgrade_ajax',
				_gt_version_fixed_through_notice: true
				
			}).done(function(data) {
				window.location.reload();
			}).fail(function(data) {});

			return false;
		})


		$('#birth-sex').change(function() {
			var gt_sex = $('#birth-sex').val();
			if (!gt_sex) {
				$('tr.tr-husb').show();
				$('tr.tr-wife').show();
			}
			if (gt_sex === 'F') {
				$('tr.tr-wife').hide();
				$('tr.tr-husb').show();
			}
			if (gt_sex === 'M') {
				$('tr.tr-husb').hide();
				$('tr.tr-wife').show();
			}
		})
		$('.generate_default_tree').click(function() {
			var family_id = $(this).data('id');
			$.post(gt_ajax_var.url, {
				action: 'generate_default_tree',
				'family_id': family_id,
				nonce: gt_ajax_var.nonce,   // pass the nonce here
			}).done(function(data) {
				window.location.reload();
			}).fail(function(data) {});
			return false;
		})
		


	})

	function isInArray(value, array) {
		for (var i = 0; i < array.length; i++) {
			if (array[i] == value) {
				return true
			}
		}
		return false;
	}
	/*
	 * A custom function that checks if element is in array, we'll need it later
	 */
	function in_array(el, arr) {
		for (var i in arr) {
			if (arr[i] == el) return true;
		}
		return false;
	}
	jQuery(function($) {
		/*
		 * Sortable images
		 */
		$('ul.gt-member-gallery-images').sortable({
			items: 'li',
			cursor: '-webkit-grabbing',
			/* mouse cursor */
			scrollSensitivity: 40,
			/*
			You can set your custom CSS styles while this element is dragging
			start:function(event,ui){
				ui.item.css({'background-color':'grey'});
			},
			*/
			stop: function(event, ui) {
				ui.item.removeAttr('style');
				var sort = new Array(),
					/* array of image IDs */
					gallery = $(this); /* ul.gt-member-gallery-images */
				/* each time after dragging we resort our array */
				gallery.find('li').each(function(index) {
					sort.push($(this).attr('data-id'));
				});
				/* add the array value to the hidden input field */
				gallery.parent().next().val(sort.join());
				/* console.log(sort); */
			}
		});
		/*
		 * Multiple images uploader
		 */
		$('.misha_upload_gallery_button').click(function(e) {
			/* on button click*/
			e.preventDefault();
			var button = $(this),
				hiddenfield = button.prev(),
				hiddenfieldvalue = hiddenfield.val().split(","),
				/* the array of added image IDs */
				custom_uploader = wp.media({
					title: 'Insert images',
					/* popup title */
					library: {
						type: 'image'
					},
					button: {
						text: 'Use these images'
					},
					/* "Insert" button text */
					multiple: true
				}).on('select', function() {
					var attachments = custom_uploader.state().get('selection').map(function(a) {
							a.toJSON();
							return a;
						}),
						thesamepicture = false,
						i;
					/* loop through all the images */
					for (i = 0; i < attachments.length; ++i) {
						/* if you don't want the same images to be added multiple time */
						if (!in_array(attachments[i].id, hiddenfieldvalue)) {
							/* add HTML element with an image */
							$('ul.gt-member-gallery-images').append('<li data-id="' + attachments[i].id + '">\
							<span style="background-image:url(' + attachments[i].attributes.url + ')">\
							<img src="' + attachments[i].attributes.url + '">\
							</span><a href="#" class="misha_gallery_remove">×</a>\
							</li>');
							/* add an image ID to the array of all images */
							hiddenfieldvalue.push(attachments[i].id);
						} else {
							thesamepicture = true;
						}
					}
					/* refresh sortable */
					$("ul.gt-member-gallery-images").sortable("refresh");
					/* add the IDs to the hidden field value */
					hiddenfield.val(hiddenfieldvalue.join());
					/* you can print a message for users if you want to let you know about the same images */
					if (thesamepicture == true) alert('The same images are not allowed.');
				}).open();
		});
		/*
		 * Remove certain images
		 */
		$('body').on('click', '.misha_gallery_remove', function() {
			var id = $(this).parent().attr('data-id'),
				gallery = $(this).parent().parent(),
				hiddenfield = gallery.parent().next(),
				hiddenfieldvalue = hiddenfield.val().split(","),
				i = hiddenfieldvalue.indexOf(id);
			$(this).parent().remove();
			/* remove certain array element */
			if (i != -1) {
				hiddenfieldvalue.splice(i, 1);
			}
			/* add the IDs to the hidden field value */
			hiddenfield.val(hiddenfieldvalue.join());
			/* refresh sortable */
			gallery.sortable("refresh");
			return false;
		});
		/*
		 * Selected item
		 */
		$('body').on('mousedown', 'ul.gt-member-gallery-images li', function() {
			var el = $(this);
			el.parent().find('li').removeClass('misha-active');
			el.addClass('misha-active');
		});
	});
	jQuery(function($) {
		if (typeof inlineEditPost !== 'undefined') {
			const wp_inline_edit_function = inlineEditPost.edit;
			inlineEditPost.edit = function(post_id) {
				wp_inline_edit_function.apply(this, arguments);
				if (typeof(post_id) == 'object') {
					post_id = parseInt(this.getId(post_id));
				}
				const edit_row = $('#edit-' + post_id)
				const post_row = $('#post-' + post_id)
				const family = $('.column-family', post_row).find('span').text()
				const root = $('.column-root', post_row).find('span').text()

				$('select[name="family"]', edit_row).val(family);
				$('select[name="root"]', edit_row).val(root);
			}
		}
	})

})(jQuery);
