!function(){var e=angular.module("myDirective",[]);e.directive("searchBox",function(){return{restrict:"E",templateUrl:"./templates/searchbox.html"}}),e.directive("inputBox",function(){return{restrict:"E",templateUrl:"./templates/inputbox.html"}}),e.directive("navBar",function(){return{restrict:"E",templateUrl:"./templates/navbar.html"}}),e.directive("onReadFile",[function(){return{scope:{fileread:"="},link:function(e,t){t.bind("change",function(t){e.$apply(function(){e.fileread=t.target.files[0]})})}}}])}();