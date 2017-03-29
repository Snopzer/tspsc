QuizApp.controller('NotificationController',['$scope','$http',function($scope,$http){
	$http({'method':'POST',url:'assets/data/notification.json'}).
	then(function(response){
		$scope.notifications = response.data.notifications;
	},function(response){})
}]);