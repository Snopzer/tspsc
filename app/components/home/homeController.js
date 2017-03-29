QuizApp.controller('HomeController',['$scope','$http',function($scope,$http){
	$http({method:'POST',url:'assets/data/test.json'}).
	then(function(response) {
		$scope.tests = response.data.tests;
	}, function(response) {});
}]);