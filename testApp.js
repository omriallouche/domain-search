var domainSearchApp = angular.module('domainSearchApp', ['otDomainSearch']);

domainSearchApp.config(["otDomainSearchUrlProvider", function (otDomainSearchUrlProvider) {
        otDomainSearchUrlProvider.setUrl("php/test.php");
    }]);

domainSearchApp.controller('DomainSearchController', function DomainSearchController($scope, otDomainSearch) {
    $scope.getSuggestions = function () {
        $scope.availabilityChecked = false;
        $scope.suggestionsChecked = false;
        otDomainSearch.getSuggestions($scope.domain).then(function (results) {
            $scope.suggestionsChecked = true;
            $scope.suggestions = results;
        });
    }
});