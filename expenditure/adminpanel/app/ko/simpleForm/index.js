define(['durandal/system', 'durandal/app', 'jquery', 'knockout'], function(system, app, $, ko) {
var viewModel = new function()
{
    var self = this;
    self.firstName = ko.observable("default first");
    self.lastName = ko.observable("default last");
    self.response = ko.observable(null);
    self.onSubmit = function()
    {
        var data = JSON.stringify(
            {
                first : self.firstName(), last : self.lastName()        
            });
        
            // on success callback
            self.response(data);
        
    }
}

return viewModel;
});