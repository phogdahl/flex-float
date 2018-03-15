<html>
 <head>
  <title>PHP Test</title>
 <meta charset="utf-8">
<title>Untitled Document</title>
<link href="greeting_2.css" rel="stylesheet" type="text/css">
</head>

<body>
     	<div class="page">
		<h1>Per's first tinkerings</h1>
	</div>

    <div class="topdata">
<?php
if (date('G') < 12) {
$greeting = 'Good Morning!';
}
else {
$greeting = 'Good Afternoon!';
}

// echo '<p>Hello Great World</p>';
  echo $greeting;

  echo '<p> </p>';

?>
<?php
$mysqli  = new mysqli("localhost", "flexfloatuser1", "SHDTDT", "flex_float");
$result = $mysqli->query("SELECT Datetime, Level FROM level_history", MYSQLI_USE_RESULT);

if ($result) {
   while ($row = $result->fetch_assoc()) {
//       echo $row['Datetime']. '&nbsp&nbsp&nbsp';
//       echo $row['Level'] . '<br />';
       $saved_level = substr( $row['Level'], 0, 2 );
       $saved_level_bar = $saved_level * 0.9 + 0;
   }
}
$result->close();
$mysqli  = new mysqli("localhost", "flexfloatuser1", "SHDTDT", "flex_float");
$result2 = $mysqli->query("SELECT Batvolt FROM level_history", MYSQLI_USE_RESULT);

if ($result2) {
   while ($row = $result2->fetch_assoc()) {
       $saved_batvolt = ( $row['Batvolt'] - 3.25 ) * 74;
       $saved_batvolt_bar = $saved_batvolt * .89 + 0;
   }
}
$result2->close();

?>
    <div class="testingth">
        <?php
//        echo "Level is $saved_level";
        ?>    
    </div>
</div>
    <ul class="bar-graph">
      <li class="bar-graph-axis">
        <div class="bar-graph-label">100%</div>
        <div class="bar-graph-label">80%</div>
        <div class="bar-graph-label">60%</div>
        <div class="bar-graph-label">40%</div>
        <div class="bar-graph-label">20%</div>
        <div class="bar-graph-label">0%</div>
        <div class="bar-graph-label-bottom"></div>
      </li>
      <li class="bar primary" style="height: <?php echo "$saved_level_bar"; ?>%;" title="95">
        <div class="percent"><?php echo "$saved_level"; ?><span>%</span></div>
        <div class="description">Kittredge</div>
      </li>
      <li class="bar secondary" style="height: <?php echo "$saved_batvolt_bar"; ?>%;" title="90">
        <div class="percent"><?php echo "$saved_batvolt"; ?><span>%</span></div>
        <div class="description">Battery</div>
      </li>
      <li class="bar success" style="height: 80%;" title="80">
        <div class="percent">80<span>%</span></div>
        <div class="description">Cows</div>
      </li>
      <li class="bar warning" style="height: 75%;" title="75">
        <div class="percent">75<span>%</span></div>
        <div class="description">Cows that think they're Yetis</div>
      </li>
      <li class="bar alert" style="height: 40%;" title="40">
        <div class="percent">40<span>%</span></div>
        <div class="description">Who knows</div>
      </li>
    </ul>

 </body>
</html>