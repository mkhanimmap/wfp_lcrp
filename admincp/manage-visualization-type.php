<?php
define('MAIN',realpath('../'));
include MAIN.'/includes/config.php';
include MAIN.'/includes/class.db-pg.php';
include MAIN.'/includes/pager-pg.php';
include MAIN.'/includes/Functions.class.php';

$Fun = new Functions();
$Fun->check_admin();
	
$pages = new Pager();

$pages->pageEnd = 20;	
$pages->pageSet = 15;

	$mss = "";
	$msg = isset($_REQUEST['msg'])?$_REQUEST['msg']:"";
	$pag = isset($_REQUEST["pagenum"]) && !empty($_REQUEST["pagenum"])?"&pagenum=".$_REQUEST["pagenum"]:"";
	$act = isset($_REQUEST['act'])?$_REQUEST['act']:"";
	$search = isset($_REQUEST['search'])?trim($_REQUEST['search']):"";
	$clear = isset($_REQUEST['btnclear'])?$_REQUEST['btnclear']:"";
	
   if($act == "active")
	{
		$stat = isset($_GET["status"])?$Fun->d($_GET["status"]):"";
		$uid  = isset($_GET["uid"])?$Fun->d($_GET["uid"]):"";
		if($stat == 1)
		 {
		  $stat = 0;
		  $goto = $Fun->e(5);
		 }
		else
		 {
		   $stat = 1;
		   $goto = $Fun->e(4);
		 }
		
		pg_query("update wfp_visualization_type set status=".$stat." where id=".$uid);
		
		 header("location:manage-visualization-type.php?msg=".$goto);
	}
	
 $qry = "";
 if(!empty($search) and empty($clear))
  {
	$qry  = "select * from wfp_visualization_type ";
	$qry  .= " where name like '%".$search."%'";
	$qry  .= " order by id desc";  
  }
 else
  {
    $search = "";
 	$qry  = "select * from wfp_visualization_type order by id desc";
  }

 //$pages->baseQry = $qry;
 //$sql = $pages->getPagingQry();
 $rows = $Fun->RunQueryObj($qry);




?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Administration Panel</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../styles/admin.css"/>
<script language="javascript" src="../js/lib/jquery.js"></script>
<link rel="stylesheet" type="text/css" href="../styles/jquery.dataTables.css"/>
<script type="text/javascript" src="../js/lib/jquery.dataTables.min.js"></script>
</head>
<body>
	<table cellspacing="0" cellpadding="0" class="maintbl" align="center">
		<tr>
			<td class="logo">
				Administration Panel</td>
		</tr>
		<tr>
			<td class="topnav" align="left">&nbsp;</td>
		</tr>
		<tr>
			<td class="middlearea" valign="top">
			<table cellspacing="0" cellpadding="10" width="100%" height="100%">
				<tr>
			    	<td width="180px" valign="top" id="leftnav" align="left"><?php include("side-menu.php");?></td>
			        <td valign="top" align="center">
                    <form name="frmsearch" method="post">
               		<table width="100%" border="0" cellpadding="5" cellspacing="0" class="tbllisting">
                    	<tr class="mainhead">
                    		<td colspan="4">
                            	<table width="100%" border="0" cellpadding="5" cellspacing="0">
                                  <tr>
                                    <td width="71%" align="left"><h1>Manage Visualization Type</h1></td>
                                    <td width="29%">&nbsp;</td>
                                  <td width="29%">&nbsp;
                                                                    
                                  </td>
                                 </table>
                            </td>
                        </tr>
                       <tr>

                          	<td colspan="4" align="right" valign="middle"  >
							<a href="add_visualization_type.php">Add new</a></td>
                          </tr> 
                     <?php 
						if($msg || $mss)
						 {
							
							if(!empty($msg))
								$msg = $Fun->d($msg);
							else
							 	$msg = $mss;
							  
							  ?>
						  
						  <tr>

                          	<td colspan="4" align="left" valign="middle"  id="msg1" class="error1" >
						<?php echo  $Fun->getMsgTxt($msg); ?></td>
                          </tr> 
                          <?php
						 }
						 ?>
                        
                         <?php
					
					if(!empty($rows)){
                       ?>
                       	<tr> 
                        <td colspan="3" align="right" valign="middle"  >
                        <table id="example" class="display" cellspacing="0" width="100%">
                        <thead>
                      
                    	<tr>
							<th align="left">#</th>
							<th align="left">Visualization Type</th>
                            <th align="left">Country</th> 
                    	  	<th align="left">Action</th>
                        </tr>
                        </thead>
                       <tbody>
                        <?php
                     $j = 0;
                      	foreach($rows as $row){
							
							?>
                       	<tr>
					    	<td>&nbsp;
							<?php echo $j+1;?></td>
					    	<td><?php 
									echo $row->name;
								?></td>
                     		<td>&nbsp;
							<?php 
									echo $Fun->getField("name","wfp_country",$row->country_id);
								?>&nbsp;</td> 
								
                       
                       
                       
                      <td>&nbsp;
                      	<a href="manage-visualization-type.php?uid=<?php echo $Fun->e($row->id); ?>&act=active&status=<?php echo $Fun->e($row->status)?>" class="hea_txt"><?php if($row->status == 1) echo "DeActivate"; else echo "Activate"; ?></a> | <a href="edit_visualization_type.php?stid=<?php echo $Fun->e($row->id)?>"><img src="../images/edit.gif" /></a>
                        
                                    
                       </td>
                    </tr>
                    <?php 
					$j++;
					} ?> 
                     </tbody>
                                </table>
                                   </td>
                                  </tr> 
					<?php }else{ ?>
                    
                    <tr>
                      <td colspan="4" align="center">No record found!</td>
                    </tr>
                    
                    <?php } ?>
                </table>
              </form>
              </td>
          </tr>
      </table>
      </td>
    </tr>
    
    <tr>
        <td class="footer">&nbsp;</td>
    </tr>
	</table>
</body>
</html>
<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery('#example').DataTable();
} );
</script>
<?php
$Fun->DB_close();
?>