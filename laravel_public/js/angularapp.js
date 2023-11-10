var app = angular.module("ngApp", ['ui.bootstrap'], function ($interpolateProvider) {
  $interpolateProvider.startSymbol('<%');
  $interpolateProvider.endSymbol('%>');
});
app.controller("ngCtrl", function ($scope, $http) {
  $scope.setexercises = [];
  $scope.loadData = function (id) {

    $http.get("/exercisesetjson/" + id)
      .then(function (response) {
        $scope.setexercises = response.data;
      });
  };

  $scope.addExercise = function () {

    var newOb = jQuery.extend({}, $scope.setexercises[$scope.setexercises.length]);

    $http.get("/newexecisesetjson")
      .then(function (response) {
        newOb = response.data;
      });


    $scope.setexercises.push(newOb);
    newOb.id = $scope.setexercises.length;
  };

  $scope.deleteExercise = function (item) {
    $(this).parent('tr').fadeOut();
    $scope.setexercises.splice(item.currentTarget.getAttribute('data-itemid'), 1);
  };

});
