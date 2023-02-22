define(['durandal/system', 'durandal/app', 'jquery', 'knockout','knockoutvalidation'], function(system, app, $, ko, koval) {
var Note = function() {
    var self = this;
    self.name = ko.observable().extend({required: true});
};

var viewModel = function() {
    var self = this;
    self.notes = ko.observableArray([new Note()]);
    self.errors = 
        ko.computed(function() {
            return ko.validation.group(self.notes(), { deep: true })}); 

    self.submit = function() {
		if (self.errors()().length == 0) {
          app.showMessage('You could now send this to server: ' + ko.utils.stringifyJson(self.notes), 'Grid Editor - Results');
			// To actually transmit to server as a regular form post, write this: ko.utils.postJson($("form")[0], self.gifts);
        }
		else
		{
			self.errors().showAllMessages();

		}
    };
    self.add = function () {
        self.notes.push(new Note());
    };
};

return new viewModel();
});