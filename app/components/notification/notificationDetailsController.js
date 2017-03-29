
QuizApp.controller('NotificationDetailsController',['$scope','$http','$stateParams',function($scope,$http,$stateParams){
	// console.log($stateParams.id);
	$http({'method':'POST',url:'assets/data/notification.json'}).
	then(function(response){
		var id = $stateParams.id;
		var detailId = id.toString();
		jQuery.map(response.data.notifications, function(obj) {
			if(obj.id_notification === detailId)
			$scope.notification = obj;
		});
		}, function(response) {
		$scope.notification = {};
	});
}]);