<?php ?>

<form class="form-horizontal" action="<?php echo base_url(); ?>index.php/admin/update_user" method="post">
    <table>
<?php
foreach ($dataedit->result() as $row) {
    $userName = $row->user_name;
    $nama = $row->nama;
    $password = $row->password;
    ?>
    	<tr>
    	    <td>UserName </td>
    	    <td>: </td>
	    <input type="hidden"  name="id" value="<?php echo $row->id; ?>" />
    	    <td><input type="text"  name="username" value="<?php echo $userName; ?>" /> </td> 
    	</tr>
    	<tr>
    	    <td>Nama </td>
    	    <td>: </td>
    	    <td><input type="text"  name="nama" value="<?php echo $nama; ?>" /> </td> 
    	</tr>
    	<tr>
    	    <td>Password </td>
    	    <td>: </td>
    	    <td><input type="text"  name="password" value="<?php echo $password; ?>" /> </td> 
    	</tr>
    	<tr>
    <?php
}
?>
	    <td></td>  
	    <td><button type="submit" >Tambah</button><button type="reset" >Reset</button></td>  
	</tr>
    </table>

</form>
<br> <a href =<?php echo base_url();?>index.php/admin/> Kembali </a>