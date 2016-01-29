
var magasin = angular.module('magasin', ['ui.router','ui.bootstrap']);

	magasin.controller('CategorieListController', function() {
		var categorieList = this;
		categorieList.categories = [
		{nom:'learn angular'},
		{nom:'build an angular app'}];

});


magasin.controller('DropdownCtrl', function ($scope, $log) {
  $scope.items = [
    'The first choice!',
    'And another choice for you.',
    'but wait! A third!'
  ];

  $scope.status = {
    isopen: false
  };

  $scope.toggled = function(open) {
    $log.log('Dropdown is now: ', open);
  };

  $scope.toggleDropdown = function($event) {
    $event.preventDefault();
    $event.stopPropagation();
    $scope.status.isopen = !$scope.status.isopen;
  };

  $scope.appendToEl = angular.element(document.querySelector('#dropdown-long-content'));
});