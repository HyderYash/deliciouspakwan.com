define(['plugins/router', 'knockout'], function(router, ko) {
    var childRouter = router.createChildRouter()
        .makeRelative({
            moduleId:'ko',
            fromParent:true
        }).map([
            { route: ['', 'helloWorld'],    moduleId: 'helloWorld/index',       title: 'Hello World',           type: 'intro',      nav: true },
            { route: 'clickCounter',        moduleId: 'clickCounter/index',     title: 'Click Counter',         type: 'intro',      nav: true },
			{ route: 'arrayList',          	moduleId: 'arrayList/index',       	title: 'Array List',           	type: 'intro',      nav: true },
            { route: 'simpleList',          moduleId: 'simpleList/index',       title: 'Simple List',           type: 'intro',      nav: true },
            { route: 'betterList',          moduleId: 'betterList/index',       title: 'Better List',           type: 'intro',      nav: true },
            { route: 'controlTypes',        moduleId: 'controlTypes/index',     title: 'Control Types',         type: 'intro',      nav: true },
            { route: 'collections',         moduleId: 'collections/index',      title: 'Collection',            type: 'intro' ,     nav: true },
            { route: 'pagedGrid',           moduleId: 'pagedGrid/index',        title: 'Paged Grid',            type: 'intro',      nav: true },
            { route: 'animatedTrans',       moduleId: 'animatedTrans/index',    title: 'Animated Transition',   type: 'intro',      nav: true },
			{ route: 'bidmodi',      moduleId: 'bidmodi/index',   title: 'Bid Modifier',       type: 'detailed',   nav: true },
			{ route: 'combo',      moduleId: 'combo/index',   title: 'Select All',       type: 'detailed',   nav: true },
			{ route: 'newShopping',      moduleId: 'newShopping/index',   title: 'New Cart',       type: 'detailed',   nav: true },
            { route: 'contactsEditor',      moduleId: 'contactsEditor/index',   title: 'Contacts Editor',       type: 'detailed',   nav: true },
            { route: 'gridEditor',          moduleId: 'gridEditor/index',       title: 'Grid Editor',           type: 'detailed',   nav: true },
			{ route: 'pagingTest',          moduleId: 'pagingTest/index',       title: 'Pagination',            type: 'detailed',   nav: true },
			{ route: 'simpleForm',           moduleId: 'simpleForm/index',        title: 'Simple Form',      type: 'detailed',   nav: true },
			{ route: 'dbRecords',           moduleId: 'dbRecords/index',        title: 'Datababe Records',      type: 'detailed',   nav: true },
			{ route: 'listFilter',          moduleId: 'listFilter/index',        title: 'Listing Filter',      type: 'detailed',   nav: true },
			{ route: 'gridValidation',      moduleId: 'gridValidation/index',   title: 'grid Validation',       type: 'detailed',   nav: true },
			{ route: 'dbtest',      		moduleId: 'dbtest/index',   		title: 'DB test',       		type: 'detailed',   nav: true },
			{ route: 'testValidation',      moduleId: 'testValidation/index',   title: 'Test Validation',       type: 'detailed',   nav: true },
            { route: 'shoppingCart',        moduleId: 'shoppingCart/index',     title: 'Shopping Cart',         type: 'detailed',   nav: true },
			{ route: 'MoreInfocontactsEditor',      moduleId: 'helloWorld/index',   	title: 'MoreInfo Contacts Editor',       type: 'morinfo',   nav: true },
            { route: 'MoreInfogridEditor',          moduleId: 'clickCounter/index',     title: 'MoreInfo Grid Editor',           type: 'morinfo',   nav: true },
            { route: 'MoreInfoshoppingCart',        moduleId: 'simpleList/index',     	title: 'MoreInfo Shopping Cart',         type: 'morinfo',   nav: true }			
        ]).buildNavigationModel();

    return {
        router: childRouter,
        introSamples: ko.computed(function() {
            return ko.utils.arrayFilter(childRouter.navigationModel(), function(route) {
                return route.type == 'intro';
            });
        }),
        detailedSamples: ko.computed(function() {
            return ko.utils.arrayFilter(childRouter.navigationModel(), function(route) {
                return route.type == 'detailed';
            });
        }),
        moreInfoSamples: ko.computed(function() {
            return ko.utils.arrayFilter(childRouter.navigationModel(), function(route) {
                return route.type == 'morinfo';
            });
        })		
    };
});