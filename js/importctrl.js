(function(){
    var app = angular.module('app');     
    // import controller
    app.controller('ImportController', function($scope, dataFactory){     

        // private function, no $scope.init is required. 
        var init = function() {
            //$scope.data = sharing; // sharing $scope.data.sharingRows btw controllers
            $scope.selectedCity='Chiayi';   // default city
            $scope.cities =[
                'Changhua', 'Chiayi', 'Chiayi City', 'Hsinchu', 'Hsinchu City', 'Hualien',
                'Kaohsiung','Keelung','Kinmen','Mazu','Miaoli', 'Nantou','New Taipei City',
                'Penghu','Pingtung','Taichung', 'Tainan', 'Taipei City', 'Taitung', 'Taoyuan',
                'Yilan','Yunlin'
            ];             
            //$scope.onApiLoad(); // for google drive           
        }; 
        
        init(); // won't be called outside of the controller

        $scope.importCsv = function(fileName, city){
            /* for security reason browsers don't allow us to get file's full url.*/ 
            //console.log($scope.uploadme);
            //path = document.getElementById('myFileInput').value; 
            //$csv = csvfilename;
            //console.log('$csv');

            dataFactory.importCsv(fileName, city)     //pass csv content to php       
            .success(function (data, status, headers, config) {
                console.log(data);
                alert(data);
            })
            .error(function(data, status, headers, config){
                console.log('fail to import csv');
            });              
            //$scope.data.sharingRows = dataFactory.importCsv();
        };
    });
})();