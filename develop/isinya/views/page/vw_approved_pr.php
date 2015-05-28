<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
$this->load->view('header');
?>
<style>
    #Kembali:hover{
        cursor:pointer;
    }
</style>
<script type="text/javascript">
    $(document).ready(function(){
        $("#fkategori").validate();
    });
    $(function(){
        $("#addPR").click(function(){
            $("#listPurchaseRequest").fadeOut("fast",function(){
                $("#formTitle").fadeIn("fast",function(){
                    $("#formBody").fadeIn("fast");
                });
            });
            return false;
        });
        $("#Kembali").click(function(){
            $("#formBody").fadeOut("fast",function(){
                $("#formTitle").fadeOut("fast",function(){
                    $("#listPurchaseRequest").fadeIn("fast");
                });
            }); 
            return false;
        });
        
    })
    
    function isNumberKey(evt)
    {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
		       
        return true;
    }

</script>
	<div id="content">
		<div class="wrapper">
				<div class="widget">
					<div class="whead"><h6>APPROVAL PURCHASE REQUEST</h6><div class="clear"></div></div>
					<table cellpadding="0" cellspacing="0" width="100%" class="tDefault" id="resize2">
                            <thead>
                                <tr>
                                    <td>No</td>
                                    <td class="sortCol"><div>No. PR<span></span></div></td>
                                    <td class="sortCol"><div>Subject / keterangan<span></span></div></td>
                                    <td class="sortCol"><div>Tgl dibuat<span></span></div></td>
                                    <td class="sortCol"><div>Dibuat oleh<span></span></div></td>
                                    <td class="sortCol"><div>Status<span></span></div></td>
                                    <td>Detail</td>
                                </tr>
                            </thead> 
                            <tbody> 
								<? $no=$this->uri->segment(3);
								foreach($rcapproval_pr->result() as $row)  
								{ $no=$no+1                    
								?>
									<tr>
										<td align="center"><?echo $no?></td>
										<td align="center"><?echo $row->no_pr?></td>  
										<td><?echo $row->subject?></td>  
										<td align="center"><?echo $row->created_date?></td>                     
										<td><?echo $row->created_by?></td>                                          
										<td align="left"><?echo $row->status?></td>  
										<td>
									<a href="<?php echo base_url(); ?>approval_pr/daftar_produkpr_edit/<?echo $row->id_pr?>" class="cblsprodukpr" />
									<input type="button" style="float: center;margin-top:-10px;margin-right:5px;" name="detailpr" id="detailpr" value="Detail" class="buttonM bBlue" />
									</a>
										</td>  
									</tr>         
								<?}?>         
							</tbody>
                        </table>
						<script>
							  $(document).ready(function(){
								  //Examples of how to assign the ColorBox event to elements
								  $(".cblsprodukpr").colorbox({rel:'group', iframe:true, width:"900", height:"70%"});
						
							  });
						</script>
                </div>
		</div>

	<div class="wrapper">
	<div class="widget" style="height:40px;margin-top:0px;">
		<div class="grid4" align="center" style="height:40px;margin-top:0px;">
		<p align="center"><?=$this->pagination->create_links();?></p>
		</div>
	</div>
	</div>
</div>

<?php $this->load->view('footer'); ?>