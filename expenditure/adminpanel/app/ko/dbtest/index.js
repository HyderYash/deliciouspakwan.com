define(['durandal/system', 'durandal/app', 'jquery', 'knockout'], function (system, app, $, ko) {
function Product(data) {
    this.Id = ko.observable(data.Id);
    this.ProductId = ko.observable(data.ProductId);
    this.CategotyName = ko.observable(data.CategotyName);
    this.ProductName = ko.observable(data.ProductName);
    this.Price = ko.observable(data.Price);
    this.CategotyId = ko.observable(data.CategotyId);
    this.Quantity = ko.observable(data.Quantity)
    this.TotalAmount = ko.observable(data.TotalAmount);
}

function ProductViewModel() {
    //Make the self as 'this' reference
    var self = this;
    self.Id = ko.observable();
    self.ProductId = ko.observable();
    self.CategotyName = ko.observable();
    self.ProductName = ko.observable();
    self.Price = ko.observable(0);
    self.CategotyId = ko.observable();
    self.Quantity = ko.observable();
    self.TotalAmount = ko.computed(function () {
        return this.Quantity() * this.Price();
    }, this);

    self.Products = ko.observableArray();
    self.ProductList = ko.observableArray();
    self.CategoryList = ko.observableArray();
    self.CustomerProducts =ko.observableArray();
    //self.errors = ko.validation.group(this, { deep: true, observable: false });
    $.getJSON("/Product/GetCategoty", function (data) {
        self.CategoryList(data);
    });
    self.ChangeCategory = function () {
        $.getJSON("/Product/GetProduct", { categoryId: self.CategotyId }, function (data) {
            self.ProductList(data);
        });
    }
    self.ProductCategory = function () {
        $.getJSON("/Product/GetProductDetails", { ProductId: self.ProductId }, function (data) {
            self.Price(data[0].Price);
            self.ProductName(data[0].ProductName);           
self.CategotyName(data[0].CategotyName);
        });
    }
    //Calculate Total of Price After Initialization
    self.Total = ko.computed(function () {
        var sum = 0;
        var arr = self.Products();
        for (var i = 0; i < arr.length; i++) {
            sum += arr[i].Price();
        }
        return sum;
    });
    }
    //Add New Item in List

   self.Insert = function () {
       if (self.errors().length > 0) {
            self.errors.showAllMessages();
            return;
        }
        self.Products.push(new Product({
            Id: self.Id(),
            ProductId: self.ProductId(),
            ProductName: self.ProductName(),
            CategotyName: self.CategotyName(),
            CategotyId: self.CategotyId(),
            Price: self.Price(),
            Quantity: self.Quantity(),
            TotalAmount: self.TotalAmount()
        }));
        self.Id(null);
        self.ProductId(null);
        self.CategotyName(null);
        self.ProductName(null);
        self.Price(null);
        self.CategotyId(null);
        self.Quantity(null);
        self.errors.showAllMessages(false);
    }
//save product in database

         self.Save = function () {
        var data = ko.toJSON(self.Products());
        $.ajax({
            url: '/Product/Insert',
            cache: false,
            type: 'POST',
            contentType: 'application/json; charset=utf-8',
            data: data,
            success: function (data) {
                self.Products.removeAll(); 
             $('#AddNewModel').modal('hide');
            }
        }).fail(

 function (xhr, textStatus, err) {
     alert(err);
 });

   //Delete product details
    self.Cancel = function () {
        self.Products.removeAll();  
    }
}
$(function () {
    var productViewModel = new ProductViewModel();
     return productViewModel;
});

});
