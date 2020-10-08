<html>
<head>
	<style type="text/css">
		table{
			margin:auto;
			margin-top: 100px;
		}
		body{
			background-image: url("https://wallpaperplay.com/walls/full/2/5/1/14019.jpg");
		}
		table,tr,td{
			height: 100px;
			width: 50%;
			text-align: center;
			font-size: 40px;
			border: 1px solid #ddd;
			border-collapse: collapse;
		}
		th{
			background-color: rgb(59, 165, 121);
			color: white;
		}
		table{
			border-radius: 5px;
		}
		tr{background-color: rgb(134, 212, 189);}
		tr:hover{
			background-color: rgb(112, 161, 141);
		}
		#prev{
			border-radius: 5px;
			height: 30px;
			border:1px solid #ddd;
			color:black;
			font-size: 20px;
			background-color: #f2f2f2;
			float: right;
			position: relative;
			right:1500px;
			top:800px;
		}
		#prev:hover{
			color: white;
			background-color: green; 
		}
	</style>
</head>
<input id = "prev" type = "button" onclick="window.location.href = 'http://localhost/interface_page_moh.php';" value = "Back to previous page" style="background-color: rgb(134, 212, 189);">
<body title="Image does not load">
<div id="chartContainer" style="height: 370px; width: 100%;"></div>
	<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>
</html>


<?php

session_start();

$host = 'localhost';
$user = 'root';
$pass = '';

$conn = mysqli_connect($host,$user,$pass);

if(!$conn)
{
	die("connection error".$mysqli_error());
}
else
{
	$start_date = $_POST['from'];
	$end_date = $_POST['to'];
	$field = $_POST['field'];

	//echo isset($_POST['mn']);
	//echo isset($_POST['ag']);

	if(isset($start_date) && isset($end_date) && isset($field)){
		mysqli_select_db($conn,'miniproj');
		echo "<table><tr>";
		echo "<th>Label</th>";
		echo "<th>Value</th></tr>";
		if(isset($_POST['mx'])){
			$sql = 	'select MAX(`'.$field.'`) as max from `pollution` where date(dat)>='.'\''.$start_date.'\''.'and date(dat)<='.'\''.$end_date.'\'' ;
			$result = mysqli_query($conn,$sql);
			$rows = mysqli_fetch_object($result);
			$max = $rows->max;
			echo "<tr><td>maximum</td>";
			echo "<td>".$max."</td></tr>";
			$sql1 = 'select year(dat),month(dat),day(dat),t from pollution';
			
}
		if(isset($_POST['mn'])){
			$sql = 	'select MIN(`'.$field.'`) as min from `pollution` where date(dat)>='.'\''.$start_date.'\''.'and date(dat)<='.'\''.$end_date.'\'' ;
			$result = mysqli_query($conn,$sql);
			$rows = mysqli_fetch_object($result);
			$min = $rows->min;
			echo "<tr><td>minimum</td>";
			echo "<td>".$min."</td></tr>";
		}
		//else{echo "";}
		if(isset($_POST['ag'])){
			$sql = 	'select avg(`'.$field.'`) as avg from `pollution` where date(dat)>='.'\''.$start_date.'\''.'and date(dat)<='.'\''.$end_date.'\'' ;
			$result = mysqli_query($conn,$sql);
			$rows = mysqli_fetch_object($result);
			$avg = $rows->avg;
			echo "<tr><td>average</td>";
			echo "<td>".$avg."</td></tr>";
		}
		echo "</table>";
		if(!isset($_POST['mx']) && !isset($_POST['mn']) && !isset($_POST['ag']))
		{
			header("location:interface_page_moh.php?message= 'no checkbox selected'");
			$_SESSION['st_date'] = $start_date;
			$_SESSION['e_date'] = $end_date;

		 }
		
	}
}
$sql2 = 'select '.$field.' as m from `pollution` where date(dat)>='.'\''.$start_date.'\''.'and date(dat)<='.'\''.$end_date.'\'' ;
			$result1 = mysqli_query($conn,$sql2);
			// $rows1 = mysqli_fetch_object($result1);
			// $names[] = $rows1->m;
   while($row = mysqli_fetch_array($result1)) {
   $names[] = $row['m'];
}
mysqli_close($conn);?> 

<script type = "text/javascript">

// 	if (navigator.onLine) {
//   console.log("You are online");
// } else {
// 	alert("You are onffline now!");
// }
var complex =<?php echo json_encode($names); ?>;	
var dd = "<?php echo($field); ?>";
// console.log("Array 1",complex);
var obj=[];
let min=100000,max=-1;
complex.forEach((el)=>{
	let num= parseFloat(el);
	obj.push({y:num});
	if(num>max){
		max=num;
	}
	if(num<min){
		min=num;
	}
});
let f=-1,f1=-1;
for(i=0;i<obj.length;i++){
	if(obj[i].y===min && f==-1){
		obj[i]={y:min,indexLabel:"lowest",markerColor:"DarkSlateGrey",markerType:"cross"};
		f=1;
	}
	if(obj[i].y===max && f1==-1){
		obj[i]={y:max,indexLabel:"highest",markerColor:"red",markerType:"triangle"};
		f1=1;
	}
}

switch(dd){
	case 't':dd="Temperature";break;
	case 'o3':dd="O3 Concentration";break;
	case 'co':dd="CO Concentration";break;
	case 'h':dd="Humidity";break;
	case 'no2':dd="NO2 Concentration";break;
	case 'p':dd="Pressure";break;
	case 'pm_10':dd="Particulate Matter(<=10 micrometers)";break;
	case 'pm_1_0':dd="Particulate Matter(<=1 micrometers)";break;
	case 'pm_2_5':dd="Particulate Matter(<=2.5 micrometers)";break;
	case 'so2':dd="SO2 concentration";break;
	default: dd="Field";break;
}
// obj.forEach((el)=>{
// 	let {y}=el;
// 	if(y==min){el={y:min,indexLabel:lowest,markerColor:"DarkSlateGrey",markerType:"cross"}
// 		el={y:min,indexLabel:"lowest",markerColor:"DarkSlateGrey",markerType:"cross"}
// 	}
// 	if(y==max){
// 		el={y:max,indexLabel:"highest",markerColor:"red",markerType:"triangle"}
// 	}
// })
console.log(obj);window.onload = function () {
var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	theme: "light2",
	title:{
		text: dd+" vs Time"
	},
	axisY:{
		title:dd,	
		includeZero: false,
	},
	axisX:{
		title:"Time"
	},
	data: [{        
		type: "line",       
		dataPoints: obj,
	}]
});
chart.render();

}

</script>