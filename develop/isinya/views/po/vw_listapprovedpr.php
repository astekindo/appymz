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

    <fieldset>
        <div class="wrapper">
            <div class="fluid">
                <div class="widget">
                    <div class="whead"><h6>CREATE PURCHASE ORDER</h6><div class="clear"></div>
                        <!--<input type="submit" style="float: right;margin-top:-33px;margin-right:5px;" name="addPR" id="addPR" value="Tambah" class="buttonM bBlue" /> -->
                    </div>
                    <div id="dyn" class="hiddenpars">
                        
                        <table cellpadding="0" cellspacing="0" border="0" class="record dTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No. PR</th>
                                    <th>Subject / keterangan</th>
                                    <th>Tgl dibuat</th>
                                    <th>Dibuat oleh</th>
                                    <th>Action</th>
                                </tr>
                            </thead> 
                            <tbody> 
								<?=$rcapprovedpr;?>
							</tbody>
                        </table>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </fieldset>

<?php $this->load->view('footer'); ?>