<html>
    <head>
        <title>User</title>
    </head>
<body>
<p>DAFTAR User</p>
<br> <a href =<?php echo base_url();?>index.php/admin/create/> Tambah Data </a>
<table width="415" border="1">
        <thead>
          <tr>
            <th width="24">No.</th>
            <th width="133">Nama</th>
            <th width="85">Username</th>
            <th width="56">password</th>
            <th width="38">Edit</th>
            <th width="45">Delete</th>
          </tr>
        </thead>
        <tbody>
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
