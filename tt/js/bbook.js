var bbook = angular.module('bbook', ['ngRoute','ngAnimate']);

// configure our routes
bbook.config(function($routeProvider) {
    $routeProvider        
        // Top nav bar
        .when('/profile', {
            templateUrl : 'pages/profile.php',
            controller  : 'profileController'
        })

        .when('/settings', {
            templateUrl : 'pages/404.html',
            controller  : 'Controller'
        })

        .when('/help', {
            templateUrl : 'pages/help.php',
            controller  : 'helpController'
        })

        // Side bar
        .when('/', {
            templateUrl : 'pages/dashboard.php',
            controller  : 'mainController'
        })

        .when('/browse/:skillLevel', {
            templateUrl : 'pages/browse.php',
            controller  : 'browseController'
        })

        .when('/browse/:skillLevel/:skillId', {
            templateUrl : function(params){ 
                return 'pages/skill_details.php?level=' + params.skillLevel + '&skill_id=' + params.skillId; },
            // controller  : 'skillDetailsController'
        })

        .when('/tree', {
            templateUrl : 'pages/tree.php',
            controller  : 'treeController'
        })

        // Manage section
        .when('/add', {
            templateUrl : 'pages/add.php',
            controller  : 'addController'
        })

        .when('/edit/:skillLevel', {
            templateUrl : 'pages/edit.php',
            controller  : 'editController'
        })

        .when('/edit/:skillLevel/:skillId', {
            templateUrl : function(params){ 
                return 'pages/edit_skill.php?level=' + params.skillLevel + '&skill_id=' + params.skillId; },
            // controller  : 'editController'
        })

        .when('/more', {
            templateUrl : 'pages/404.html',
            controller  : 'Controller'
        })

        .otherwise({
            templateUrl: 'pages/404.html'
        });

});

// create the controller and inject Angular's $scope
bbook.controller('mainController', function($scope) {
    // create a message to display in our view
    $scope.message = 'Everyone come and see how good I look!';
});

bbook.controller('browseController', ['$scope', '$routeParams', '$location', function($scope, $routeParams, $location){

    if($routeParams.skillLevel)
        changeLevelByName($routeParams.skillLevel);

    // for changing page from code
    $scope.go = function ( path ) {
      $location.path( path );
    };
}]);

bbook.controller('treeController', ['$scope', '$routeParams',
  function($scope, $routeParams) {
    $scope.skillId = $routeParams.skillId;
  }
]);

bbook.controller('addController', ['$scope', '$routeParams',
  function($scope, $routeParams) {
    $scope.skillId = $routeParams.skillId;
  }
]);

bbook.controller('editController', ['$scope', '$routeParams', '$location', function($scope, $routeParams, $location){

    if($routeParams.skillLevel)
        changeLevelByName($routeParams.skillLevel);

    // for changing page from code
    $scope.go = function ( path ) {
      $location.path( path );
    };
}]);

