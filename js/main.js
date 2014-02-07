//main.js
function WatchController ($scope, $http){

	$scope.items=[];

	$http.get('php/data.php?x=getall')
   		.then(function(res) {
	   		for (var i=0; i<res.data.length; i+=1)
	   		{
		   		$scope.items.push(res.data[i]);
		   		console.log($scope.items[i]);
		   		console.log($scope.items[i]['scans'].length);
	   		}
    	},
	    	function() 
	    	{
    	    	alert('Could not connect to the server!');
    		}
    	);

  	
   	$scope.resItemlist = function()
   	{
   		$scope.newItem = '';
   	}

	$scope.addItemlist = function()
	{	
		if (($scope.newItem != undefined)&&($scope.newItem !=' '))
		{	
			console.log($scope.newItem);
			$http({
			    url: 'php/data.php?x=add',
			    method: "PUT",
			    data: {message:$scope.newItem},
			    headers: {'Content-Type': 'application/json'}		    
			})
			.then(function(response) 
			{	$scope.items.push(
					{ metadata: $scope.newItem,
					});
				var count=$scope.items.length;
				$http.get('php/data.php?x=getid')
   				.then(function(res) {
   					console.log(res.data[0]);
   					console.log($scope.newItem);
					$scope.items[count]['item_id'] = res.data[0];
					console.log($scope.items)
				},
	    	function() 
	    		{
    	    		alert('Could not connect to the server!');
    			}
    		);
			    $scope.newItem = '';
			}, 
			 function(response) { // optional
			        console.log('failure');
			    }
			);

		}
	}

	$scope.resWatchlist = function(){
		$scope.newItem='';
	}

	$scope.downloadimg=function(index){
		console.log($scope.items[index]);
		var ch1 = 'marqo'+$scope.items[index]['metadata'];
		myurl='https://chart.googleapis.com/chart?cht=qr&chs=100&chl='+ch1+'&choe=UTF-8&chld=M';
		console.log(myurl);
		$http({
//		    url: 'php/data.php?x=update',
		    url: myurl,
		    method: "GET",
//		    data: {message:$scope.temp},
		    headers: {'Content-Type': 'image/png'}		    
		})
		.then(function(response) {
		        console.log(response);
		    }, 
		    function(response) { // optional
		        console.log('failure');
		    }
		);

	}

	$scope.downloadQR=function(index){
		console.log($scope.items[index]);
		var ch1 = 'marqo'+$scope.items[index]['metadata'];
		myurl='https://chart.googleapis.com/chart?cht=qr&chs=100&chl='+ch1+'&choe=UTF-8&chld=M';
		console.log(myurl);
		$http({
//		    url: 'php/data.php?x=update',
		    url: myurl,
		    method: "GET",
//		    data: {message:$scope.temp},
		    headers: {'Content-Type': 'image/png'}		    
		})
		.then(function(response) {
		        console.log(response);
		    }, 
		    function(response) { // optional
		        console.log('failure');
		    }
		);

	}


}