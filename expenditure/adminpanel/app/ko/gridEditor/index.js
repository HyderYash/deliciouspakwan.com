define(['durandal/system', 'durandal/app', 'jquery', 'knockout','knockoutvalidation'], function(system, app, $, ko, koval) {
	
	ko.validation.rules.pattern.message = 'Invalid.';
	ko.validation.configure({
		registerExtenders: true,
		messagesOnModified: true,
		insertMessages: true,
		parseInputAttributes: true,
		messageTemplate: null
	});
	var Note = function() {
		var self = this;
		self.name = ko.observable().extend({required: true});
		self.price = ko.observable().extend({required: true});
	};   
	var GiftModel = function (gifts) {
		var self = this;
		self.gifts = ko.observableArray(gifts);
        self.addGift = function () {
            self.gifts.push({
                name: "",
                price: ""
            });
        };
		self.notes = ko.observableArray([new Note()]);
		self.errors = ko.computed(function() {
            return ko.validation.group(self.notes, { deep: true })});
			
        self.removeGift = function (gift) {
            self.gifts.remove(gift);
        };

        self.save = function (form) {
			if (self.errors()().length == 0) {
				app.showMessage(self.errors()().length + 'You could now send this to server: ' + ko.utils.stringifyJson(self.gifts), 'Grid Editor - Results');
				// To actually transmit to server as a regular form post, write this: ko.utils.postJson($("form")[0], self.gifts);			
			}
			else
			{
				self.errors().showAllMessages();
				console.log(self.errors().showAllMessages());
			}
        };
        self.activate = function () {
            console.log('activate grid');
        };

        self.canActivate = function () {
            console.log('canActivate grid');
            return true;
        };

        self.canDeactivate = function () {
            return app.showMessage('Are you sure you want to leave?', 'Leaving', ['Yes', 'No']);
        };

        self.deactivate = function () {
            console.log('deactivate grid');
        };
    };

    return new GiftModel([{ name: "Tall Hat", price: "39.95" }, { name: "Long Cloak", price: "120.00" }]);
});