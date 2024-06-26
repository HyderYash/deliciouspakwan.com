﻿define(['durandal/app', 'durandal/system', 'knockout'], function (app, system, ko) {
    var name = ko.observable();
    var canSayHello = ko.computed(function () {
	system.log('Lifecycle : canSayHello : canSayHello');
		return name() ? true : false;
    });

    return {
        displayName: 'What your name?',
        name: name,
        sayHello: function() {
			system.log('Lifecycle : sayHello : sayHello');
            app.showMessage('Hello ' + name() + '!', 'Warm Greetings');
        },
        canSayHello: canSayHello,
        activate: function() {
            system.log('Lifecycle : activate : hello');
        },
        binding: function () {
            system.log('Lifecycle : binding : hello');
            return { cacheViews:false }; //cancels view caching for this module, allowing the triggering of the detached callback
        },
        bindingComplete: function () {
            system.log('Lifecycle : bindingComplete : hello');
        },
        attached: function (view, parent) {
            system.log('Lifecycle : attached : hello');
        },
        compositionComplete: function (view) {
            system.log('Lifecycle : compositionComplete : hello');
        },
        detached: function (view) {
            system.log('Lifecycle : detached : hello');
        }
    };
});