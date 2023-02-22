define(['durandal/system', 'durandal/app', 'jquery', 'knockout', 'knockoutvalidation','./simpleGrid'], function(system, app, $, ko, koval,SimpleGrid) {
	
	ko.validation.rules.pattern.message = 'Invalid.';
	ko.validation.configure({
		registerExtenders: true,
		messagesOnModified: true,
		insertMessages: true,
		parseInputAttributes: true,
		messageTemplate: null
	});
	var retrieveDataFromDb = function () {
		$.getJSON("retriveUser.php", function(data) {
			viewModel.records(data);
		});
	};
	var records = ko.observableArray([new retrieveDataFromDb()]);
	var saveDataToDb = function (data) {
		$.ajax({
			url: "createUser.php",
			type: 'post',
			data: data,
			success: function () {
				new retrieveDataFromDb();
			}
		});		
	};
	var deleteDataFromDb = function (data) {
		$.ajax({
			url: "deleteUser.php",
			type: 'post',
			data: data,
			success: function () {
				new retrieveDataFromDb();
			}
		});		
	};
	
    var gridViewModel = new SimpleGrid({
        data: records,
        columns: [
            { headerText: "ID", rowText: "ID" },
            { headerText: "First Name", rowText: "FIRST_NAME" },
			{ headerText: "Last Name", rowText: "LAST_NAME" },
			{ headerText: "Action", rowText: { 
				action: function(id) {
						return function(){
							viewModel.delRecord(id);
						}
					}
				}
			}
        ],
        pageSize: 20
    });
	
    var viewModel = {
		firstName: ko.observable().extend({ minLength: 5, maxLength: 10, required: true }),
		lastName: ko.observable().extend({ required: true }),        
		submitRecord: function () {
			if (this.errors().length == 0) {
				var sentdata = ko.toJS({"firstName":this.firstName, "lastName":this.lastName} );
				new saveDataToDb(sentdata);
			} else {
				alert('Please check your submission.');
				this.errors.showAllMessages();
			}
		},
		records: records,
        sortFld: function (fld) {
            records.sort(function(a, b) {
                return a.fld < b.fld ? -1 : 1;
            });
        },
		delRecord: function (id) {
		if (confirm('Are you sure you want to delete this row?')) {
			var deletedata = ko.toJS({"id":id});
			new deleteDataFromDb(deletedata);
		   }
		},
        jumpToFirstPage: function() {
            gridViewModel.currentPageIndex(0);
        },		
        gridViewModel: gridViewModel,
        SimpleGrid: SimpleGrid		
    };
	viewModel.errors = ko.validation.group(viewModel);
    return viewModel;
});