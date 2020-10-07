<?php
if (!defined('ABSPATH')) die('Restricted Access');
JSVEHICLEMANAGERbreadcrumbs::getBreadcrumbs();
include_once(jsvehiclemanager::$_path . 'includes/header.php');

if (jsvehiclemanager::$_error_flag == null) {

	//$show_country_name = JSVEHICLEMANAGERincluder::getJSModel('configuration')->getConfigValue('vehiclebycity_show_country_name');
    $show_country_name = jsvehiclemanager::$_data['config']['sellerbycity_countryname'];

?>
    <div id="jsvehiclemanager-wrapper">
        <div class="control-pannel-header">
            <span class="heading"><?php echo __('Sellers By City', 'js-vehicle-manager'); ?></span>
        </div>
        <?php
        	//echo '<pre>';print_r(jsvehiclemanager::$_data[0]); echo '</pre>';
        ?>
        <?php if(!empty(jsvehiclemanager::$_data[0])){ ?>
            <div id="jsvehiclemanager-vehicles-details">
            	<?php
            	if( count(jsvehiclemanager::$_data[0]) > 0){
            		foreach(jsvehiclemanager::$_data[0] as $row ) {
            			if( $show_country_name == 1 ){
            				$cityname = $row->cityname.', '.$row->countryname;
            			}
            			else{
            				$cityname = $row->cityname;
            			}
    	            ?>
    	            <a class="jsvehiclemanager_record_perrow jsvehiclemanager-width-3" href="<?php echo jsvehiclemanager::makeUrl(array('jsvmme'=>'user','jsvmlt'=>'sellerlist','jsvehiclemanagerpageid'=>jsvehiclemanager::getPageid(),'cityid'=>$row->cityid)); ?>">
    	        		<div class="jsvehiclemanager-record-wraper">
    	        			<div class="jsvehiclemanager-record-title"><?php echo __($cityname, 'js-vehicle-manager'); ?></div>
                            <?php if(jsvehiclemanager::$_data['config']['sellerbycity_sellercount'] == 1){ ?>
    	        			<div class="jsvehiclemanager-record-number"> (<span class="jsvehiclemanager-record-number-text"><?php echo __($row->totaluserbycity,'js-vehicle-manager');  ?></span>)</div>
                            <?php } ?>
    	        		</div>
    	        	</a>
    	        	<?php
    	        	}
            	}
            	?>
            </div>
        <?php }else{
            $msg = __('No record found','js-vehicle-manager');
            echo JSVEHICLEMANAGERlayout::getNoRecordFound($msg);
        } ?>
    </div>
<?php
}
?>
