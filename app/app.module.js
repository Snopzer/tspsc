var QuizApp = angular.module('QuizApp',['ui.router']);
QuizApp.config(['$stateProvider', '$urlRouterProvider', function($stateProvider, $urlRouterProvider){
	$urlRouterProvider.otherwise("/")
	
	$stateProvider
	.state('/', {
		url: "/",
		templateUrl: "app/components/home/homeView.html",
	})
	.state('test', {
		url: "/test/:id",
		templateUrl: "app/components/test/testView.html",
	})
	.state('practicetest', {
		url: "/practicetest",
		templateUrl: "app/components/test/practiceListView.html",
	})
	.state('notification', {
		url: "/notification",
		templateUrl: "app/components/notification/notificationView.html",
	})
	.state('notificationDetails', {
		url: "/notification/:id",
		templateUrl: "app/components/notification/notificationDetailsView.html",
	})
	.state('comingsoon', {
		url: "/comingsoon",
		templateUrl: "app/components/home/comingsoonView.html",
	})
	.state('syllabus', {
		url: "/syllabus",
		templateUrl: "app/components/syllabus/syllabusView.html",
	})
	.state('syllabusdetails', {
		url: "/syllabusdetails",
		templateUrl: "app/components/syllabus/syllabusDetailsView.html",
	})
	
	.state('questionpapers', {
		url: "/questionpapers",
		templateUrl: "app/components/home/questionpapersView.html",
	}).state('subjects', {
		url: "/subjects",
		templateUrl: "app/components/home/subjectsView.html",
	})
	.state('contact', {
		url: "/contact",
		templateUrl: "app/components/home/contactView.html",
	})
	.state('about', {
		url: "/about",
		templateUrl: "app/components/home/aboutView.html",
	})
	
	.state('callcenter', {
		url: "/callcenter",
		templateUrl: "app/components/home/callcenterView.html",
	})
	
	.state('technicalteam', {
		url: "/technicalteam",
		templateUrl: "app/components/home/technicalteamView.html",
	})
	.state('postaladdress', {
		url: "/postaladdress",
		templateUrl: "app/components/home/postalAddressView.html",
	})
	.state('mocktest', {
		url: "/mocktest",
		templateUrl: "app/components/test/mocktestView.html",
	})
	.state('realtimetest', {
		url: "/realtimetest/:id",
		templateUrl: "app/components/test/realtimetestView.html",
	})
}]);