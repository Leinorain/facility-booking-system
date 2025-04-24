
<?php 
$records = getBookingRecords();

$utype = '';
$type = $_SESSION['calendar_fd_user']['type'];
if($type == 'admin') {
	$utype = 'on';
}
?>
<div class="col-md-12">
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">Event Booking Details</h3>
    </div>
    <!-- /.box-header -->
     
    <div class="box-body">
      <table class="table table-bordered">
        <tr>
          <th style="width: 10px">#</th>
          <th>Event Name</th>
          <th>Facility</th>
          <th>Booking Date</th>
          <th style="width: 140px">Number of People</th>
          <th style="width: 100px">Status</th>
          <th style="display: none">Reservation ID</th>
          <?php if($utype == 'on') { ?>
		        <th >Action</th>
		      <?php } ?>
        </tr>
        <?php
          $idx = 1;
          foreach($records as $rec) {
            extract($rec);
          $stat = '';
          if($status == "PENDING") {$stat = 'warning';}
          else if ($status == "APPROVED") {$stat = 'success';}
          else if($status == "DENIED") {$stat = 'danger';}
        ?>
        <tr>
          <td><?php echo $idx++; ?></td>
          <td>
            <!-- <a href="<?//php echo WEB_ROOT; ?>views/?v=USER&ID=<?//php echo $user_id; ?>"> -->
              <?//php echo strtoupper($reservationEventName); ?>
            <!-- </a> -->
              <?php echo $reservationEventName ; ?>
          </td>
          <td><?php echo $facility; ?></td>
          <td><?php echo $res_date; ?></td>
          <td><?php echo $count; ?></td>
          <td><span class="label label-<?php echo $stat; ?>"><?php echo $status; ?></span></td>
          <?php if($utype == 'on') { ?>
            
          <td style="display: none">
            <?php echo $reservationId; ?>
          </td>

		      <td><?php if($status == "PENDING") {?>
            <a href="javascript:approve('<?php echo $reservationId ?>');">Approve</a>&nbsp;/
            &nbsp;<a href="javascript:decline('<?php echo $reservationId ?>');">Denied</a>&nbsp;/
            &nbsp;<a href="javascript:deleteUser('<?php echo $reservationId ?>');">Delete</a>
          <?php }
            else
            {
          ?>
            <a href="javascript:deleteUser('<?php echo $reservationId ?>');">Delete</a>
          <?php
          }//else ?>
          </td>
          
		      <?php } ?>
        </tr>
        <?php } ?>
      </table>
    </div>
    <!-- /.box-body -->
    <div class="box-footer clearfix">
      <!--
	<ul class="pagination pagination-sm no-margin pull-right">
      <li><a href="#">&laquo;</a></li>
      <li><a href="#">1</a></li>
      <li><a href="#">2</a></li>
      <li><a href="#">3</a></li>
      <li><a href="#">&raquo;</a></li>
    </ul>
	-->
      <?php echo generatePagination(); ?> </div>
  </div>
  <!-- /.box -->
</div>

<script language="javascript">
function approve(reservationId) {
	if(confirm('Are you sure you wants to Approve it ?'+ reservationId)) {
		window.location.href = '<?php echo WEB_ROOT; ?>api/process.php?cmd=regConfirm&action=approve&reservationId='+ reservationId;
	}
}
function decline(reservationId) {
	if(confirm('Are you sure you wants to Decline the Booking ?')) {
		window.location.href = '<?php echo WEB_ROOT; ?>api/process.php?cmd=regConfirm&action=denide&reservationId='+ reservationId;
	}
}
function deleteUser(reservationId) {
	if(confirm('Deleting user will also delete it\'s booking from calendar.\n\nAre you sure you want to proceed ?' + reservationId)) {
		window.location.href = '<?php echo WEB_ROOT; ?>api/process.php?cmd=delete&reservationId='+reservationId;
	}
}

</script>
