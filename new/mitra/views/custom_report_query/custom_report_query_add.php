<?= $side_menu?> 
<div class="grid_10">
    <div class="box round first">
        <h1 class="custom_reportheadlineadd"><?= $site_title ?></h1>
        <div class="block">
        <?= form_open(site_url('custom_report_query/save'), array('class'=>"","id"=>"formAdd","name"=>"formAdd")) ?>        
        <table class="accountsetting">
        <tr>
        <td class="tdcaption">
            <label class="labelcaption">
                ID :
            </label>
        </td>
        <td>
            <input type="text" name="id_custom_report_query" id="id_custom_report_query" readonly="readonly" value="<?=isset($row) ? htmlspecialchars($row->id_report, ENT_QUOTES) : 'automatic';?>" class="textinputsetting txtsmall readonly" />
        </td>
        </tr>
        <tr>
            <td class="tdcaption">
                <label class="labelcaption">
                    Nama Report :
                </label>
            </td>
            <td>
                <input tabindex="1" type="text" id="report_name" name="report_name" maxlength="200" value="<?=isset($row) ? htmlspecialchars($row->report_name, ENT_QUOTES) : '';?>" class="textinputsetting txtsmall validate[required] autoF" />
            </td>
        </tr>
		<tr>
            <td class="tdcaption">
                <label class="labelcaption">
                    Request By :
				</label>
            </td>
            <td>
                <input tabindex="2" type="text" id="req_by" name="req_by" maxlength="200" value="<?=isset($row) ? htmlspecialchars($row->req_by, ENT_QUOTES) : '';?>" class="textinputsetting txtsmall validate[required]" />
            </td>
        </tr> 
		<tr>
            <td class="tdcaption" style="vertical-align:top;">
                <label class="labelcaption">
                    Field :
                </label>
            </td>
            <td>
				<textarea tabindex="3" id="field" name="field" class="textinputsetting txtlarge validate[required]" 
				style="height:100px;"><?=isset($row) ? htmlspecialchars($fld, ENT_QUOTES) : '';?></textarea>
            </td>
        </tr>
		<tr>
            <td class="tdcaption" style="vertical-align:top;">
                <label class="labelcaption">
                    Table :
                </label>
            </td>
            <td>
                <textarea tabindex="4" id="table" name="table" class="textinputsetting txtlarge validate[required]" 
				style="height:100px;"><?=isset($row) ? htmlspecialchars($tbl, ENT_QUOTES) : '';?></textarea>
            </td>
        </tr>
		<tr>
            <td class="tdcaption" style="vertical-align:top;">
                <label class="labelcaption">
                    Condition :
                </label>
            </td>
            <td>
                <textarea tabindex="5" id="cond" name="cond" class="textinputsetting txtlarge" 
				style="height:100px;"><?=isset($row) ? htmlspecialchars($whr, ENT_QUOTES) : '';?></textarea>
            </td>
        </tr> 
		</table>
    <div class="submitarea">
        <input type="submit" class="btn" value="Save" tabindex="6" />
    </div>
    </form>	
	 
</div>
</div>
</div>