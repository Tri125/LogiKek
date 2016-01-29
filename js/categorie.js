
var magasin = angular.module('magasin', []);

	magasin.controller('CategorieListController', function($scope) {
		$scope.categories = categories;

});


var categories = [
		{nom:'learn aaaa'},
		{nom:'build an angular app'}
		];