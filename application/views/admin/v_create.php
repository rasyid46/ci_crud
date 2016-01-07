<?php 
	$success_msg = $this->session->flashdata("success_msg");
	$error_msg = $this->session->flashdata("error_msg");
	
	if($success_msg != "")
	{
		echo "<div class='alert alert-success'>";
		echo "$success_msg";
		echo "</div>";
	}elseif($error_msg != ""){
		echo "<div class='alert alert-error'>";
		echo "$error_msg";
		echo "</div>";	
	}
?>
<form class="form-horizontal" action="<?php echo base_url(); ?>index.php/admin/save_user" method="post">
    <table>
	<tr>
	    <td>UserName </td>
	    <td>: </td>
	    <td><input type="text"  name="username" value="" /> </td> 
	</tr>
	<tr>
	    <td>Nama </td>
	    <td>: </td>
	    <td><input type="text"  name="nama" value="" /> </td> 
	</tr>
	<tr>
	    <td>Password </td>
	    <td>: </td>
	    <td><input type="text"  name="password" value="" /> </td> 
	</tr>
	<tr>
	    <td></td>  
	    <td><button type="submit" >Tambah</button><button type="reset" >Reset</button></td>  
	</tr>
    </table>

</form>
<br> <a href =<?php echo base_url();?>index.php/admin/> Kembali </a>