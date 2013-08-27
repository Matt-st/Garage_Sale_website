<?php 
ob_start(); 
session_start();
$username = $_SESSION['username'];
$security = $_SESSION['security'];
if(!session_is_registered(username)){
header("location:login.html");
}
include_once 'connect.php';
//need to get the date for the access log
$now = getdate();
$time = "$now[hours]:$now[minutes]:$now[seconds]";
$date = "$now[year]:$now[mon]:$now[mday]";

//assuming these are not null
$location = $_SESSION['location'];
$t = $_SESSION['time'];//get here
$d = $_SESSION['date'];//get here
if($username != NULL && $location != NULL && $t != NULL && $d != NULL && $date != NULL && $time != NULL)
{
	mysql_query("LOCK TABLES ACCESS_LOG WRITE");
	$query = "INSERT INTO ACCESS_LOG VALUES('','$username','$location','$t','$d','$date','$time');";
	mysql_query($query);
	mysql_query("UNLOCK TABLES");
	
	
	$_SESSION['location'] = htmlentities($_SERVER['REQUEST_URI']);
	$_SESSION['time'] = $time;
	$_SESSION['date'] = $date;
}
else
{	

	$_SESSION['location'] = htmlentities($_SERVER['REQUEST_URI']);
	$_SESSION['time'] = $time;
	$_SESSION['date'] = $date;
}


//script to update auctions if they expire
$time_int= strtotime($time);
$date_int= strtotime($date);
//select all active auctions
$active_auctions=mysql_query("select * from AUCTIONS where ACTIVE='1'");

 while($active_row=mysql_fetch_array($active_auctions))
 {
 //get the end date and the end time
	$_date=$active_row['END_DATE'];
	$auction_date_int=strtotime($_date);
	
	$_time=$active_row['END_TIME'];
	$auction_time_int=strtotime($_time);
	$auction_num_update_active=$active_row['AUCTION_NUM'];
	
	//echo $auction_date_int."::auction date v todays date::".$date_int."<br>";
	//echo $auction_time_int."::auction time v todays time::".$time_int."<br>";

//compare the time of the auction with the time right now and determine if an auction has expired
 if(($auction_date_int < $date_int) || ($auction_date_int == $date_int && $auction_time_int <= $time_int))
	{
	//update auctions accordingly 
		//echo "the auction date is inactive";
		//lock needed here
		mysql_query("LOCK TABLES AUCTIONS WRITE;");
		mysql_query("UPDATE AUCTIONS SET ACTIVE='0' where AUCTION_NUM=$auction_num_update_active ");
		mysql_query("UNLOCK_TABLES;");
		//need to set the auction as sold in the buyer table also 
		//highest bidder username
		$bidder_query=mysql_query("select USERNAME, max(BID) AS BIDS from BIDS where AUCTION_NUM='$auction_num_update_active'");
		$bidder_row=mysql_fetch_row($bidder_query);
		$highest_bidder_uname=$bidder_row['0'];
		$highest_bidder_bid=$bidder_row['1'];
		$buyer_update=mysql_query("INSERT INTO BUYER VALUES('$highest_bidder_uname', '$auction_num_update_active', '', '', '$highest_bidder_bid', '$date')");
	
	}
	
 }

?>
<html>
<header>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js">
</script>

<link rel="stylesheet" type="text/css" href="style.css" />
</header>
<div id="head">
	<div id="logo">
		<a href="http://mattst.bugs3.com/GarageSale/User_Home.html">
		<img src="niceGSale.jpg" alt="Home image" /></a>
		
	</div>
	<div id="menu">
	<table>
	<tr>
			<?php
			if($security == 1)
			{
			?>
			<td><p><a href="Category_Maintenance.html">Category Maintenance</a></p></td>
			<td><p><a href="Comment_Maintenance.html">Comment Maintenance</a></p></td>
			<td><p><a href="Image_Maintenance.html">Image Maintenance</a></p></td>
			<td><p><a href="User_Maintenance.html">User Maintenance</a></p></td>
			<td><p><a href="Auctions_Maintenance.html">Auctions Maintenance</a></p></td>
			<td><p><a href="Reports.html">Reports</a></p></td>
			</tr>
			<tr>
			
			<?php
			}
			?>
			<td><p><a href="User_Home.html">Home</a></p></td>
			<td><p><a href="Active_Items.html">Active Items</a></p></td>
			<td><p><a href="Sell.html">Sell</a></p></td>
			<td><p><a href="Categories.html">Browse Categories</a></p></td>
			<td><p><a href="Edit_Profile.html">Edit Profile</a></p></td>
			<td><p><a href="Support.html">Support</a></p></td>
			<td><p><a href="Purchases.html">History</a></p></td>
			<td><p><a href="Logout.html">Logout</a></p></td>
			</tr>
			</table>
	</div>
</div>
<?php
//need to insert access log
 
?>
<div id="body">
	<div id="inner_body">
	<?php
	echo "Welcome ".$username;
	?>
<!--	</div>
</div>
<div id="footer">
<center>  Date Last Updated: 04/20/2013 Webmaster: pebcac@pebcac.com </center>
</div>
</html>-->