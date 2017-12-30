function handleAddPublicationAjaxResponse(responseData, textStatus, jqXHR) {
	//console.log(responseData);
	//alert("data response from the server: " + responseData);
	// print the returned html block into the correct location
	jQuery("#related-publication-list").append(responseData);
	// clear the inputs in the form
	jQuery("#create_new_publication").val('');
	// need to register event listener on new item
	jQuery("#related-publication-list").find(".remove-publication").last().click(handlePublicationRemove);	
	
}

function handleAddNewPublication(){
		
	var postId                = jQuery('#post_ID').val();
		
	var newPublication = {
	  publication_title:   jQuery('input[name="publication_title"]').val(),
	  publication_authors: jQuery('input[name="publication_authors"]').val(),
	  publication_details: jQuery('input[name="publication_details"]').val(),
	  publication_link:    jQuery('input[name="publication_link"]').val(),
	};	
	
	
	var postData = {
		'action': 'publication_add_new_action',
		'post_id' : postId,
		'new_publication' : JSON.stringify(newPublication),		
	};

	jQuery.ajax({
		type: "post",
		url: ajaxurl,
		data: postData,
		success: handleAddPublicationAjaxResponse,
		error: function(jqXHR, textStatus, errorThrown) {
			console.log(errorThrown);
		}
	});	
}

function handleAddExistingPublication(){
		
	var postId                = jQuery('#post_ID').val();
	var selectedPublicationId = jQuery('#newcategory_parent').val();

	var postData = {
		'action': 'publication_add_existing_action',
		'post_id' : postId,
		'publication_id': selectedPublicationId,
	};

	jQuery.ajax({
		type: "post",
		url: ajaxurl,
		data: postData,
		success: handleAddPublicationAjaxResponse,
		error: function(jqXHR, textStatus, errorThrown) {
			console.log(errorThrown);
		}
	});	
}

/*
	This is the function that sends info via ajax to the server when the "add publication" button is pressed.
*/

function handlePublicationAddSubmit(event) {
        
	var isAddNew = !jQuery('#create_new_publication').hasClass('wp-hidden-child');
	if (isAddNew) {
		handleAddNewPublication();
	} else {
		handleAddExistingPublication();
	}
	/*	
	var postId                = jQuery('#post_ID').val();
	var selectedPublicationId = jQuery('#newcategory_parent').val();
		
	var newPublication = {
	  publication_title:   jQuery('input[name="publication_title"]').val(),
	  publication_authors: jQuery('input[name="publication_authors"]').val(),
	  publication_details: jQuery('input[name="publication_details"]').val(),
	  publication_link:    jQuery('input[name="publication_link"]').val(),
	};	
	
	
	var postData = {
		'action': 'publication_add_action',
		'post_id' : postId,
		'is_add_existing' : (isAddExisting ? 1:0),
		'publication_id': selectedPublicationId,
		'is_add_new' : (isAddNew ? 1:0),
		'new_publication' : JSON.stringify(newPublication),		
	};
		
	
	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	//jQuery.post(ajaxurl, postData, function(response) {
	//	alert('Got this from the server: ' + response);
	//});
	

	jQuery.ajax({
		type: "post",
		url: ajaxurl,
		data: postData,
		success: function(responseData, textStatus, jqXHR) {
			//console.log(responseData);
			//alert("data response from the server: " + responseData);
			// print the returned html block into the correct location
			jQuery("#related-publication-list").append(responseData);
			// clear the inputs in the form
			jQuery("#create_new_publication").val('');
			// need to register event listener on new item
			jQuery("#related-publication-list").find(".remove-publication").last().click(handlePublicationRemove);	
		},
		error: function(jqXHR, textStatus, errorThrown) {
			console.log(errorThrown);
		}
	});
	
			
	*/
}


/*
	This is the function that sends info via ajax to the server when the "remove publication" button is pressed.
*/
function handlePublicationRemove(event) {
	
	var postId                = jQuery('#post_ID').val();
	var publicationId         = jQuery(this).closest('.publication-container').find('input[name="post_ID_publication"]').val();
	
	var containerToRemove = jQuery(this).closest('.publication-container');
	
	var postData = {
		'action'         : 'publication_remove_action',
		'post_ID'        : postId,
		'publication_ID' : publicationId,
	};

	jQuery.ajax({
		type: "post",
		url: ajaxurl,
		data: postData,
		success: function(responseData, textStatus, jqXHR) {
			// remove the correct block from the dom
			jQuery(containerToRemove).remove();

		},
		error: function(jqXHR, textStatus, errorThrown) {
			console.log(errorThrown);
		}
	});	
}


function registerListeners() {
	jQuery("#publication-add-selector a").click(function (event) {
		jQuery("#publication-add-selector-existing").toggleClass("nav-tab-active");
		jQuery("#publication-add-selector-new").toggleClass("nav-tab-active");
		
		jQuery("#existing_publication").toggleClass("wp-hidden-child");
		jQuery("#create_new_publication").toggleClass("wp-hidden-child");
	});
	
	jQuery("#publication-add-toggle").click(function (event) { jQuery("#publication-add").toggleClass("wp-hidden-child"); });

	jQuery("#publication-add-submit").click(handlePublicationAddSubmit);
	
	jQuery(".remove-publication").click(handlePublicationRemove);	
	}

jQuery( document ).ready(registerListeners);



