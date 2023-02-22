define(['durandal/system', 'durandal/app', 'jquery', 'knockout','knockoutvalidation'], function(system, app, $, ko, koval) {
ko.validation.rules['minArrayLength'] = {
    validator: function (obj, params) {
        return obj.length >= params.minLength;
    },
    message: "Must have at least {0} {1}"
};

ko.validation.registerExtenders();

function HRAdmin() {
    this.FirstName = ko.observable("").extend({
        validatable: true,
        required: true,
        minLength: 1,
        maxLength: 50
    });
    this.LastName = ko.observable("").extend({
        validatable: true,
        required: true,
        minLength: 1,
        maxLength: 50
    });
    this.Email = ko.observable("").extend({
        validatable: true,
        required: true,
        minLength: 1,
        maxLength: 100,
        email: true
    });
}

var CompanySignUpModel = function () {
    // PROPERTIES
    var self = this;

    self.HrAdmins = ko.observableArray([]).extend({
        minArrayLength: {
            params: {
                minLength: 1,
                objectType: "Account Manager"
            },
            message: 'Must specify at least one Account Manager'
        }
    });

    self.HrAdminsErrors = ko.validation.group(self.HrAdmins, {
        deep: true,
        live: true
    });

    self.AddHrAdmin = function (data, event) {
        self.HrAdmins.push(new ko.observable(new HRAdmin()));
    };

    self.GoToStep3 = function (data, event) {
        var isValid = true;
        if (self.HrAdminsErrors().length !== 0) {
            self.HrAdminsErrors.showAllMessages();
            isValid = false;
        }

        if (!self.HrAdmins.isValid()) {
            self.HrAdmins.notifySubscribers();
            isValid = false;
        }

        if (!isValid) return;

        $("#step-3").removeClass("disabled");
        //ScrollTo("step-3");
    };
};

return new CompanySignUpModel();
});