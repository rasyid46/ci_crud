<table border="1">
    <tr>
	<td>No</td>    
	<td>Nama</td>    
	<td>User</td>    
	<td>Password</td>    	
	<td>edit</td>    	
	<td>delete</td>    	
    </tr>
    <?php
    if($list_user->result()){
	
    foreach ($list_user->result() as $key => $row)
    {
	$key = $key+1;
	echo "<tr>";
	echo "<td>".$key."</td>";
	echo "<td>".$row->nama."</td>";
	echo "<td>".$row->user_name."</td>";
	echo "<td>".$row->password."</td>";
	?>
	<td><a href =<?php echo base_url();?>index.php/admin/edit_user/<?php echo $row->id; ?>> Edit </a></td>
	<td><a href =<?php echo base_url();?>index.php/admin/delete_user/<?php echo $row->id; ?>> Hapus </a></td>
    <?php
 
    }
    }else{
echo "<tr><td colspan=6>Data tidak ada </td></tr>";
    }
    ?>
</table>
<br> <a href =<?php echo base_url();?>index.php/admin/create/> Tambah Data </a>