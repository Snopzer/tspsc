QuizApp.controller('RealtimeTestController', ['$scope','$http','$stateParams',function($scope,$http,$stateParams) {
	
		var testurlid = $stateParams.id;
		var testId = testurlid.toString();
		$http({method:'POST',url:'assets/data/test.json'}).
		then(function(response) {
			$scope.tests = response.data.tests;
			jQuery.map($scope.tests, function(obj) {
			if(obj.id_test === testId)
			$scope.test = obj;
			});
		});
	$scope.selected_options = [];
	$scope.testStarted=false;
	$scope.testSubmitted=false;
	
	$http({'method':'POST',url:'assets/data/tests/'+ testId +'.json'}).
	then(function(response){
		var id = 1;
		var detailId = id.toString();
		//$scope.questions = response.data.questions.slice(0,10);
		$scope.questions = response.data.questions;
		jQuery.map($scope.questions, function(obj) {
			
			if(obj.id_question === detailId)
			$scope.question = obj;
		});
	},function(response){});
	
	$scope.showQuestion = function(id_question){
		var id = id_question;
		var detailId = id.toString();
		jQuery.map($scope.questions, function(obj) {
			if(obj.id_question === detailId)
			$scope.question = obj;
		});
	}
	$scope.submitTest = function(){
		deleteUser = confirm("Are you sure .? You want to Submit the Test");
		if(deleteUser){
			$scope.testSubmitted=true;
			$scope.selected_options = [];
			var attempt =0;
			var correct=0;
			angular.forEach($scope.questions, function(question) {
			if(question.option_selected){
					if(question.option_correct == question.option_selected){
						$scope.selected_options.push(question.option_selected);
						correct++;
					}
					attempt++;
				}
			});
			
			$scope.testScore = correct *10;
			$scope.attempt = attempt;
			$scope.correct = correct;
		}
	}
	$scope.startTest =  function(){
		$scope.testStarted = true;
	}
	
}]);
