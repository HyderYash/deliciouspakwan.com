define(['durandal/system', 'knockout'], function(system, ko) {
function VM(types){
	var self = this;
    
    function updateTypes(types){
    	types.forEach(function(type){
        	type.members.forEach(function(member){
            	member.isSelected = ko.observable(false);
            });
            type.isSelected = ko.computed({
            	read: function(){
                	return type.members.every(function(member){
                    	return member.isSelected();
                    });
                },
                write: function(selected){
                	type.members.forEach(function(member){
                    	member.isSelected(selected);
                    });
                }
            });
        });
        self.types(types);
    }
	// Data
	self.types = ko.observableArray();
    updateTypes(types);
}


var data = [{
"rid": 4,
"members": [
    {
        "last_modified_date": "2016-08-04T14:59:25.958Z",
        "id": 31,
        "label": "Backgrounders & Information Notes"
    },
    {
        "last_modified_date": "2016-08-04T14:59:25.961Z",
        "id": 32,
        "label": "Biographies"
    },
],
"label": "Event-/Stakeholder related documentation",
},
{
"rid": 2,
"members": [
    {
        "last_modified_date": "2016-08-04T14:59:25.875Z",
        "id": 1,
        "label": "Books"
    },
    {
        "last_modified_date": "2016-08-04T14:59:25.878Z",
        "id": 2,
        "label": "Briefs, Summaries, Synthesis, Highlights"
    },
],
"label": "Publications"
}];
    return new VM(data)
});