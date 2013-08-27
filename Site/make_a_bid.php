<?php
include_once 'connect.php';
session_start();
//get starting price
$starting_price=$_SESSION['starting_price'];
//get high bid
$highest_bid=$_SESSION['high_bid'];
if($highest_bid < $starting_price)
{
	$highest_bid =$starting_price - 1;


}
//get username
$username=$_SESSION['username'];
//get auction num
$auction_num=$_SESSION['auction_num'];
//get info about the auction
$query=mysql_query("select * from AUCTIONS where AUCTION_NUM='$auction_num'");
//break up the array
$query_row=mysql_fetch_array($query);
//get bin price
$BIN_price=$query_row['BUY_IT_NOW_PRICE'];
//retrieve bid from former file
$bid=$_POST['bid'];
if($BIN_price <= $bid)
{
	echo "The bid, $".$bid." that you have made is greater than the buy it now price of $".$BIN_price."."; 
	echo "Please use the buy it now option.";

}
else if($highest_bid < $bid)
{//need to check for the highest bidder and make sure that the user isnt bidding below that 

echo "Thank you for this wonderful bid of $".$bid;
mysql_query("INSERT INTO BIDS VALUES('$username','$auction_num','$bid')");
}
else if($bid == NULL)
{
echo "Please enter a bid";
}
else
{
echo "Please bid higher then the current bidder.";
echo "<br>".$highest_bid;
}
?>