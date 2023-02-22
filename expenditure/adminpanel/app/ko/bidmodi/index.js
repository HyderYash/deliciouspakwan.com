define(['durandal/system', 'knockout'], function(system, ko) {
	var self;
	var bidModel = function () {
		self = this;
		self.tierListData = ko.observableArray([{tierKey: 41513, tierName: 'Tier1'}, {tierKey: 41893, tierName: 'Tier2'},{tierKey: 40001, tierName: 'Tier3'}]);
		
		self.demoBidData = ko.observableArray([{
				41513:{ WindowsProjectScope:[
					 {
						bidModifierKey:37254503,
						maxRange:null,
						minRange:null,
						name:"WindowsProjectScopeInstall",
						paramValue:"Install",
						percentageBidModifier:98,
						stdAttributeName:"WindowsProjectScope"
					 },
					 {
						bidModifierKey:37254513,
						maxRange:null,
						minRange:null,
						name:"WindowsProjectScopeRepair",
						paramValue:"Repair",
						percentageBidModifier:45,
						stdAttributeName:"WindowsProjectScope"
					 }
				  ],
				  OwnHome:[
					 {
						bidModifierKey:37254504,
						maxRange:null,
						minRange:null,
						name:"OwnHomeYes",
						paramValue:"Yes",
						percentageBidModifier:12,
						stdAttributeName:"OwnHome"
					 }
				  ]
			   },   
			   41893:{
				  WindowsProjectScope:[
					 {
						bidModifierKey:37254506,
						maxRange:null,
						minRange:null,
						name:"WindowsProjectScopeInstall",
						paramValue:"Install",
						percentageBidModifier:30,
						stdAttributeName:"WindowsProjectScope"
					 },
					 {
						bidModifierKey:37254517,
						maxRange:null,
						minRange:null,
						name:"WindowsProjectScopeRepair",
						paramValue:"Repair",
						percentageBidModifier:10,
						stdAttributeName:"WindowsProjectScope"
					 }
				  ]
			   },
			   40001:{
				  WindowsProjectScope:[
					 {
						bidModifierKey:37254355,
						maxRange:null,
						minRange:null,
						name:"WindowsProjectScopeInstall",
						paramValue:"Install",
						percentageBidModifier:50,
						stdAttributeName:"WindowsProjectScope"
					 },
					 {
						bidModifierKey:37675544,
						maxRange:null,
						minRange:null,
						name:"WindowsProjectScopeRepair",
						paramValue:"Repair",
						percentageBidModifier:4,
						stdAttributeName:"WindowsProjectScope"
					 }
				  ],
				  OwnHome:[
					 {
						bidModifierKey:37254504,
						maxRange:null,
						minRange:null,
						name:"OwnHomeYes",
						paramValue:"Yes",
						percentageBidModifier:10,
						stdAttributeName:"OwnHome"
					 }
				  ]
			   }, 			   
		}]);
		self.activeTierKey = ko.observable(40001);
		self.bidCritObj = ko.observableArray();
		self.activeTierKey.subscribe(function(newValue) {
			
			ko.toJS(self.demoBidData[newValue]).forEach(function(bidDataArr){
				console.log(ko.toJS(bidDataArr) + '---------->');
				/* bidDataArr.forEach(function(bidValueArr){
					console.log(ko.toJSON(bidValueArr));
				}); */
				//leadCritObj.sectionOptions.forEach(function(selOpt){
			});
		   
		});					
	};
    return  new bidModel();
});