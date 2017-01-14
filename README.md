# Angular Domain Search

An angularjs directive that performs a search for available domains. If the requested domain is unavailable, suggested domains are returned.

The current code uses the API of [ResellerClub](resellerclub.com/), but the service can be replaced with other domain search tools. Since the ResellerClub requires an authorized server endpoint to actually query for available domains, the code here also provides an endpoint in php that uses the ResellerClub api. You can plug in other languages and providers.

## Getting started:
- Include `otDomainSearch.js`:
```html
<script src="src/otDomainSearch.js"></script>
```
- Include `otDomainSearch` module into your app:
```javascript
angular.module('yourApp', ['otDomainSearch'])
```
- Configure domain API url with `otDomainSearchUrlProvider`:
```javascript
myApp.config(["otDomainSearchUrlProvider", function (otDomainSearchUrlProvider) {
        otDomainSearchUrlProvider.setUrl("php/test.php");
}]);
```
## How to use:
#### Domain availability checking
```html
<button ot-check-domain="domain" result="domainCheckResult">Check Availability</button>
```
Click on this button will check domain availability for the value in the `domain` variable in scope. Availability result will be saved to the `domainCheckResult` variable.

#### Get domain suggestions
```html
<button ot-get-domain-suggestions="domain" result="suggestions">Get Suggestions</button>
```
Click on this button will get domain suggestions for value in `domain` variable. Domain suggestions result will saved to `suggestions` array.
```html
<div ng-repeat="suggestion in suggestions">
    Domain <b>{{suggestion.domain}}</b> is {{suggestion.free && 'Is Available' || 'Is Not Available'}}!
</div>
```
Each object in `suggestions` array has two properties - `domain` and `free` - `domain` for the domain name and `free` for the availability result.

# Attributes
## `ot-check-domain`
* `ot-check-domain` - domain name for search
* `result` - availability result
* `is-ckecking`  (optional) - this variable will be set to `true` during search, and set to `false` afterwards

## `ot-get-domain-suggestions`
* `ot-get-domain-suggestions` - domain name for search
* `result` - domain suggestions result
* `is-ckecking`  (optional) - this variable will be set to `true` during search, and set to `false` afterwards
