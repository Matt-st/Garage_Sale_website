<?php
include_once 'connect.php';

//get the category
$category = mysql_real_escape_string($_POST['category']);
//need to match category with auctions and then display all that are appropriate
$category_num_query=mysql_query("select CATEGORY_NUM from CATEGORIES where CATEGORY='$category'");
$category_num_row=mysql_fetch_row($category_num_query);
$category_num=$category_num_row['0'];
$auction_num_query=mysql_query("SELECT DISTINCT AUCTIONS.AUCTION_NUM FROM `AUCTIONS`, `BUYER`, `CATEGORY` WHERE AUCTIONS.ACTIVE='1' AND CATEGORY.CATEGORY_NUM='$category_num' AND CATEGORY.AUCTION_NUM=AUCTIONS.AUCTION_NUM;");
?>
<script>
//view an auction onclick function
/*$(document).ready(function(){
	$("#view").click(function(){
		$.ajax({
			type: "POST",
			url: "view_auction.php",
			data: "auctionnum="+$("#auctionnum").val(),
			success: function(msg){alert(msg);
			//$('#sidescroll3').html(msg);
			
			
			}
		});
	})
});*/	
</script>
<?php
while($row=mysql_fetch_array($auction_num_query))
{
//now that i have the auction number I can get all of the auctions
 $auction_num=$row['AUCTION_NUM'];
 $query=mysql_query("select * from AUCTIONS where AUCTION_NUM='$auction_num'");
  
	while($query_row=mysql_fetch_array($query))
	{//print out all of the auctions in tables
	$image_query=mysql_query("select * from IMAGE where AUCTION_NUM='$auction_num'");
	$bids_q=mysql_query("select USERNAME, max(BID) AS BIDS from BIDS where AUCTION_NUM='$auction_num'");
	$bids_q_row=mysql_fetch_row($bids_q);
	$high_bid=$bids_q_row['1'];
	$bids_query=mysql_query("select USERNAME, BID AS BIDS from BIDS where AUCTION_NUM='$auction_num' and BID='$high_bid'");
	 $bids_row=mysql_fetch_row($bids_query);
	  $image_row=mysql_fetch_row($image_query);
	   $image=$image_row['0'];
	   
	?>
	<form method='post'>
	<table>
	 <tr>
	  <td><?php echo "Item name: "; echo "{$query_row['ITEM_NAME']}"; ?></td>
	  </tr>
	 <tr>
		  <td><?php echo "Buy it now price: "; echo "{$query_row['BUY_IT_NOW_PRICE']}"; ?></td>
		  <td><?php echo "Seller: "; echo "{$query_row['USERNAME']}"; ?></td>
		  <td><?php echo "Start Date: "; echo "{$query_row['START_DATE']}"; ?></td>
		  <td><?php echo "End Date: "; echo "{$query_row['END_DATE']}"; ?></td>
		  <td><?php echo "End Time: "; echo "{$query_row['END_TIME']}"; ?></td>
	 </tr>
	 <tr>
<td><?php echo "Current Bidder: "; echo "{$bids_row['0']}"; ?></td>	
	<td><?php echo "Current Bid: "; echo "{$bids_row['1']}"; ?></td>
		<td><?php echo "Starting price: "; echo "{$query_row['PRICE']}"; ?></td>
	</tr>
	  <tr>
		  <td><img src="getimage.php?id=<?=$image?>" width="175" height="200"alt="Image not found"></a></td>
	 </tr>
	</table>	  
		<!--table for description I made a new table for formating purposes-->
	<table>	
	 <tr>
		  <td><?php echo "Description: "; echo "{$query_row['DESCRIPTION']}"; ?></td>
	 </tr>
	 </table>
	<table>	 
	  <tr>
		<input type="hidden" name="auctionnum" id="auctionnum" value="<?=$auction_num?>" />
		<input type='submit' value='View Auction'  name='view' id='view' />
   	 </tr>
	
	</table>
	</form>
	<?php
	
	}





}
?>

