  //select all
  function toggle(source)
  {
    var checked = source.checked;
    var checkboxes = source.form.elements['global_ids[]'];

    for (var i = checkboxes.length; i--;)
    {
      var checkbox = checkboxes[i];
      if (checkbox != source)
      {
        checkbox.checked = checked;
      }
    }
  }
function validate(form, showState) {
	if(form.global_ids.value=='') {
		alert("Please select a criteria to modify");
		form.global_ids.focus();
		return false;		
	}
}

$(document).ready(function() {
	
	//process xml config output
	$('.xmlConfigShow').on('click', function (e) {
	  var affiliateKey = $(this).attr("value");									  
	  var action = 'get-xml';	  
	  var post_data = $(':input:hidden', this).serializeArray();
	  post_data.push({name: 'action', value: action},{name: 'affiliateKey', value: affiliateKey});
	  $.post('../functions/post.php', post_data, function(data) {
		$('#basicExampleModal').modal('show');
		$("#configHere").empty();
		$('#configHere').append(data);
	  }); 
		event.preventDefault();
	});

	//process criteria editing for margin
	$( "#criteriaEdit" ).submit(function( event ) {
	  var $this = $(this);											   
	  $this.button('loading');										 
	  var action = 'margin-bid';									  										 
	  var post_data = $(':input:hidden', this).serializeArray();
	  var marginBid = $("select#marginBid").val();
	  post_data.push({name: 'marginBid', value: marginBid},{name: 'action', value: action});
	  $.post('../functions/post.php', post_data, function(data) {
		$("#edit-table-margin").empty();
		$("#edit-table").empty();
		$('#edit-table-margin').append(data);
		$('html, body').animate({
			scrollTop: $("#edit-table").offset().top
		}, 1000);	  
	  }); 
	  event.preventDefault();	 
	}); 	

	//publish criteria changes
	$( "#publish" ).on('click', function (e) {
	  $("#modal-btn-yes").button('reset');										  
	  $('h4.modal-title').empty();											  										  
	  var action = 'update-criteria';	
	  var affkey = $("input#affkey").val();	  
	  var id = $("input[name=id]").val();
	  var post_data = $('#criteriaEdit').serializeArray();
	  post_data.push({name: 'action', value: action});
	  $('h4.modal-title').append('Confirm Criteria Updates');
	  $('#confirm').modal('show');
	  $("#modal-btn-yes").on("click", function(){
		  var $this = $(this);											   
		  $this.button('loading');											   
		  $.post('../functions/post.php', post_data, function(data) {
			$('#confirm').modal('hide');
			document.location.href = '../pages/hs-publishers-edit.php?affiliateKey='+affkey+'&confirm=y'
		  }); 
		  event.preventDefault();	 
	  });
	  $("#modal-btn-no").on("click", function(){
	  $('h4.modal-title').empty();										  
	  $('#confirm').modal('hide');
	    return;
	  });	  
	}); 	
	
	//process publisher main editing
	$( "#update-publisher" ).on('click', function (e) {
	  $("#modal-btn-yes").button('reset');										  
	  $('h4.modal-title').empty();											  										  
	  var action = 'update-publisher';	
	  var status = $('.active input').prop('id');
	  var affiliateKey = $("input#affiliateKey").val();
	  var affiliateKeyOriginal = $("input#affiliateKeyOriginal").val();
	  var password = $("input#password").val();
	  var username = $("input#username").val();
	  var id = $("input#id").val();
	  $('h4.modal-title').append('Confirm Publisher Update');
	  $('#confirm').modal('show');
	  $("#modal-btn-yes").on("click", function(){
		  var $this = $(this);											   
		  $this.button('loading');
		  var post_data = $(':input:visible', this).serializeArray();
		  post_data.push({name: 'action', value: action},{name: 'affiliateKey', value: affiliateKey},{name: 'status', value: status},{name: 'id', value: id},{name: 'password', value: password},{name: 'username', value: username},{name: 'affiliateKeyOriginal', value: affiliateKeyOriginal});	  
		  $.post('../functions/post.php', post_data, function(data) {
			//alert(data);	
			$('#confirm').modal('hide');
			document.location.href = '../pages/index.php'
		  }); 
		  event.preventDefault();	 
	  });
	  $("#modal-btn-no").on("click", function(){
	  $('#confirm').modal('hide');
	    return;
	  });	  
	}); 	

	//process service edit
	$( "#update-service" ).on('click', function (e) {
	  $("#modal-btn-yes").button('reset');										  
	  $('h4.modal-title').empty();											  										  
	  $('#textFill').empty();											  												   	  	  
	  var action = 'update-service';
	  var parentService = $("input#parentService").val();
	  var service = $("input#service").val();
	  var serviceKey = $("input#serviceKey").val();
	  $('h4.modal-title').append('Confirm Service Update');
	  $('#confirm').modal('show');
	  $("#modal-btn-yes").on("click", function(){
		  var $this = $(this);											   
		  $this.button('loading');											   											   
		  var post_data = $(':input:visible', this).serializeArray();
		  post_data.push({name: 'action', value: action},{name: 'serviceKey', value: serviceKey},{name: 'parentService', value: parentService},{name: 'service', value: service});	  
		  $.post('../functions/post.php', post_data, function(data) {
		  	$('#confirm').modal('hide');
			var result = encodeURIComponent(data);
			document.location.href = '../pages/hs-services.php?confirm=y&msg=Updated Service Complete:'+result;
		  }); 
		  event.preventDefault();	 
	  });
	  $("#modal-btn-no").on("click", function(){
	  $('#confirm').modal('hide');
	    return;
	  });	  
	}); 	
	//process service add
	$( "#add-service" ).on('click', function (e) {
	  $("#modal-btn-yes").button('reset');										  
	  $('h4.modal-title').empty();											  										  
	  $('#textFill').empty();											  												   	  	  
	  var action = 'add-service';
	  var parentService = $("input#parentService").val();
	  var service = $("input#service").val();
	  $('h4.modal-title').append('Confirm New Service Add');
	  $('#confirm').modal('show');
	  $("#modal-btn-yes").on("click", function(){
		  var $this = $(this);											   
		  $this.button('loading');											   
		  var post_data = $(':input:visible', this).serializeArray();
		  post_data.push({name: 'action', value: action},{name: 'parentService', value: parentService},{name: 'service', value: service});
		  $.post('../functions/post.php', post_data, function(data) {
			if (data.indexOf('error') > -1) {
		  	  $('#confirm').modal('hide');				
			  document.location.href = '../pages/hs-services-add.php?confirm=y&msg='+data;
			} else {
		  	  $('#confirm').modal('hide');								
			  document.location.href = '../pages/hs-services.php?confirm=y&msg=Add Complete:'+data;
			} 
		  }); 
		  event.preventDefault();	 
	  });
	  $("#modal-btn-no").on("click", function(){
	  $('#confirm').modal('hide');
	    return;
	  });	  
	}); 	
	//process attribute add
	$( "#add-attribute" ).on('click', function (e) {
	  $("#modal-btn-yes").button('reset');										  
	  $('h4.modal-title').empty();											  										  
	  $('#textFill').empty();											  												   	  	  
	  var action = 'add-attribute';
	  var name = $("input#name").val();
	  var value = $("input#value").val();
	  $('h4.modal-title').append('Confirm New Attribute Add');
	  $('#confirm').modal('show');
	  $("#modal-btn-yes").on("click", function(){
		  var $this = $(this);											   
		  $this.button('loading');											   											   
		  var post_data = $(':input:visible', this).serializeArray();
		  post_data.push({name: 'action', value: action},{name: 'name', value: name},{name: 'value', value: value});	  
		  $.post('../functions/post.php', post_data, function(data) {
			if (data.indexOf('error') > -1) {
			  $('#confirm').modal('hide');				
			  document.location.href = '../pages/hs-attributes-add.php?confirm=y&msg='+data;
			} else {
			  $('#confirm').modal('hide');				
			  document.location.href = '../pages/hs-attributes.php?confirm=y&msg=Add Complete:'+data;
			} 
		  }); 
		  event.preventDefault();	 
	  });
	  $("#modal-btn-no").on("click", function(){
	  $('#confirm').modal('hide');
	    return;
	  });	  
	}); 	
	//process attribute edit
	$( "#update-attribute" ).on('click', function (e) {
	  $("#modal-btn-yes").button('reset');										  
	  $('h4.modal-title').empty();											  										  
	  $('#textFill').empty();											  												   	  	  
	  var action = 'update-attribute';
	  var name = $("input#name").val();
	  var value = $("input#value").val();
	  var attributeKey = $("input#attributeKey").val();
	  $('h4.modal-title').append('Confirm Attribute Update');
	  $('#confirm').modal('show');
	  $("#modal-btn-yes").on("click", function(){
		  var $this = $(this);											   
		  $this.button('loading');											   
		  var post_data = $(':input:visible', this).serializeArray();
		  post_data.push({name: 'action', value: action},{name: 'attributeKey', value: attributeKey},{name: 'name', value: name},{name: 'value', value: value});	  
		  $.post('../functions/post.php', post_data, function(data) {
		    $('#confirm').modal('hide');															  
			document.location.href = '../pages/hs-attributes.php?confirm=y&msg=Updated Attribute Complete:'+data;
		  }); 
		  event.preventDefault();	 
	  });
	  $("#modal-btn-no").on("click", function(){
	  $('#confirm').modal('hide');
	    return;
	  });	  
	}); 	

	//process edit criteria
	$( "#myForm" ).submit(function( event ) {
	  var post_data = $(':input:visible', this).serializeArray();	
	  $.post('../functions/post.php', post_data, function(data) {
		$("#edit-table-original").empty();
		$('#edit-table').append(data);
		$('html, body').animate({
			scrollTop: $("#edit-table").offset().top
		}, 1000);	  
		
	  }); 
	  event.preventDefault();
	});
	
	//process delete selected criteria
	$( "#delete-criteria" ).on('click', function (e) {
	  $("#modal-btn-yes").button('reset');										  
	  $('h4.modal-title').empty();											  										  
	  $('#textFill').empty();											  												   	  
	  var action = 'delete-criteria';	
	  var id = $("input[name=id]").val();
	  var affkey = $("input#affkey").val();	  	  
	  var post_data = $('#criteriaEdit').serializeArray();
	  post_data.push({name: 'action', value: action});
	  $('h4.modal-title').append('Confirm Delete Criteria');
	  $('#textFill').append('<br /><p>*Important - you will delete all criteria presently shown on this screen for this publisher</p>');
	  $('#confirm').modal('show');
	  $("#modal-btn-yes").on("click", function(){
		  var $this = $(this);											   
		  $this.button('loading');											   											   
		  $.post('../functions/post.php', post_data, function(data) {
		    $('#confirm').modal('hide');															  
			document.location.href = '../pages/hs-publishers-edit.php?affiliateKey='+affkey+'&confirm=y'
		  }); 
		  event.preventDefault();	 
	  });
	  $("#modal-btn-no").on("click", function(){											  
	  $('#confirm').modal('hide');
	    return;
	  });	  
	}); 	

	//process delete publisher
	$( "#delete-publisher" ).on('click', function (e) {
	  $("#modal-btn-yes").button('reset');										  
	  $('h4.modal-title').empty();											  										  
	  var action = 'delete-publisher';	
	  var id = $("input#id").val();
	  var affkey = $("input#affiliateKeyOriginal").val();	  	  
	  var post_data = $(':input:visible', this).serializeArray();	
	  post_data.push({name: 'action', value: action},{name: 'affkey', value: affkey},{name: 'id', value: id});
	  $('h4.modal-title').append('Confirm Delete Publisher');
	  $('#confirm').modal('show');
	  $("#modal-btn-yes").on("click", function(){
		  var $this = $(this);											   
		  $this.button('loading');											   
		  $.post('../functions/post.php', post_data, function(data) {
		  	$('#confirm').modal('hide');															  
			document.location.href = '../pages/index.php?msg=Publisher%20'+affkey+'%20Successfully%20Deleted&confirm=y'
		  }); 
		  event.preventDefault();	 
	  });
	  $("#modal-btn-no").on("click", function(){
	  $('#confirm').modal('hide');
	    return;
	  });	  
	}); 	

	//process delete service
	$( "#delete-service" ).on('click', function (e) {
	  $("#modal-btn-yes").button('reset');										  
	  $('h4.modal-title').empty();											  										  
	  $('#textFill').empty();											  												   	  
	  var action = 'delete-service';	
	  var serviceKey = $("input#serviceKey").val();
	  var post_data = $(':input:visible', this).serializeArray();	
	  post_data.push({name: 'action', value: action},{name: 'serviceKey', value: serviceKey});
	  $('#textFill').append('<br /><p>*Important - deleting this service will remove it from ALL publisher config lines that contain this service.  Delete with caution!</p>');		  
	  $('h4.modal-title').append('Confirm Delete Service');
	  $('#confirm').modal('show');
	  $("#modal-btn-yes").on("click", function(){
		  var $this = $(this);											   
		  $this.button('loading');											   											   
		  $.post('../functions/post.php', post_data, function(data) {
		  	$('#confirm').modal('hide');															  
			document.location.href = '../pages/hs-services.php?msg=Service%20'+serviceKey+'%20Successfully%20Deleted&confirm=y'
		  }); 
		  event.preventDefault();	 
	  });
	  $("#modal-btn-no").on("click", function(){
	  $('#confirm').modal('hide');
	    return;
	  });	  
	}); 	

	//process delete attribute
	$( "#delete-attribute" ).on('click', function (e) {
	  $("#modal-btn-yes").button('reset');										  
	  $('h4.modal-title').empty();											  										  
	  $('#textFill').empty();											  												   
	  var action = 'delete-attribute';	
	  var attributeKey = $("input#attributeKey").val();
	  var post_data = $(':input:visible', this).serializeArray();	
	  post_data.push({name: 'action', value: action},{name: 'attributeKey', value: attributeKey});
	  $('#textFill').append('<br /><p>*Important - deleting this attribute will remove it from ALL publisher config lines that contain this attribute.  Delete with caution!</p>');	
	  $('h4.modal-title').append('Confirm Delete Attribute');
	  $('#confirm').modal('show');
	  $("#modal-btn-yes").on("click", function(){
		  var $this = $(this);											   
		  $this.button('loading');											   
		  $.post('../functions/post.php', post_data, function(data) {
			$('#confirm').modal('hide');															  
			document.location.href = '../pages/hs-attributes.php?msg=Attribute%20'+attributeKey+'%20Successfully%20Deleted&confirm=y'
		  }); 
		  event.preventDefault();	 
	  });
	  $("#modal-btn-no").on("click", function(){
	  $('#confirm').modal('hide');
	    return;
	  });	  
	}); 	

	//upload xml
	$( "#loadXML" ).on('click', function (e) {
	  $("#modal-btn-yes").button('reset');										  
	  $('h4.modal-title').empty();											  										  
	  $('#textFill').empty();											  												   	  	  
	  var action = 'load-xml';	
	  var xmlString = $("#xmlString").val();	
	  var post_data = $(':input:visible', this).serializeArray();
	  post_data.push({name: 'action', value: action},{name: 'xmlString', value: xmlString});
	  $('h4.modal-title').append('Confirm Load XML');
	  $('#confirm').modal('show');
	  $("#modal-btn-yes").on("click", function(){
		  var $this = $(this);									   
		  $this.button('loading');											   											   
		  $.post('../functions/post.php', post_data, function(data) {
			if (data.indexOf('done') > -1) {
			    $('#confirm').modal('hide');								
				document.location.href = '../pages/index.php?confirm=y&msg=XML%20Successfully%20Loaded';				
			} else {
			    $('#confirm').modal('hide');				
				alert(data);				
			}
		  }); 
		  event.preventDefault();	 
	  });
	  $("#modal-btn-no").on("click", function(){
	  $('#confirm').modal('hide');
	    return;
	  });	  
	}); 
	
	//clear tables
	$( "#clear-tables" ).on('click', function (e) {
	  $("#modal-btn-yes").button('reset');										  
	  $('h4.modal-title').empty();											  										  
	  $('#textFill').empty();											  												   	  
	  var action = 'clear-tables';	
	  var post_data = $(':input:visible', this).serializeArray();
	  post_data.push({name: 'action', value: action});
	  $('#textFill').append('<br /><p>*Important - clearing tables will delete ALL publisher configs in the system.  Delete with caution!</p>');		  	  
	  $('h4.modal-title').append('Confirm Delete Tables');
	  $('#confirm').modal('show');
	  $("#modal-btn-yes").on("click", function(){
		  var $this = $(this);											   
		  $this.button('loading');											   
		  $.post('../functions/post.php', post_data, function(data) {
		    $('#confirm').modal('hide');															  
			document.location.href = '../pages/index.php?msg=Tables%20Deleted&confirm=y'
		  }); 
		  event.preventDefault();	 
	  });
	  $("#modal-btn-no").on("click", function(){
	  $('#confirm').modal('hide');
	    return;
	  });	  
	}); 	
});