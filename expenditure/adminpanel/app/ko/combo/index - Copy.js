define(['durandal/system', 'knockout'], function(system, ko) {
		var categories = [
            { name: 'Fruit',mcheck: false, items: [ { id: 1, checkval: 'Orange', checked: false },
							{ id: 2, checkval: 'Mango', checked: false },
							{ id: 3, checkval: 'Amrood', checked: false },
							{ id: 4, checkval: 'banana', checked: false }
							] },
            { name: 'Vegetables', mcheck: false, items: [ { id: 5, checkval: 'Orange22', checked: false },
							{ id: 6, checkval: 'Mango33', checked: true },
							{ id: 7, checkval: 'Amrood33', checked: false },
							{ id: 8, checkval: 'banana22', checked: false } 
							]}
			
        ];
	var viewModel = function (categories) {
		var self = this;
		
		self.categories = ko.observableArray(ko.utils.arrayMap(categories, function (contact) {
            return { name: contact.name, mcheck: contact.mcheck, items: ko.observableArray(contact.items) };
        }));

		
		self.doselAll = function(chk,kname){
				
				
				ko.toJS(self.categories).forEach(function(stateArr){
				if(stateArr.name == kname)
				{
					
					stateArr.items.forEach(function(cityArr){
						cityArr.checked=chk;
						console.log(cityArr.checked + '--------'+ chk);
					});
				}
			});
			console.log('YYYYYYYYYYYYY..........'+ko.toJSON(self.categories));

		};
    };
    return new viewModel(categories)
});