<?php
if (!defined('ABSPATH')) die('Restricted Access');

$msgkey = JSVEHICLEMANAGERincluder::getJSModel('vehicle')->getMessagekey();
JSVEHICLEMANAGERmessages::getLayoutMessage($msgkey);
JSVEHICLEMANAGERbreadcrumbs::getBreadcrumbs();
include_once(jsvehiclemanager::$_path . 'includes/header.php');

if (jsvehiclemanager::$_error_flag_message == null) {

wp_enqueue_script('jquery-ui-tabs');
wp_enqueue_style('jsauto-ratingstyle', jsvehiclemanager::$_pluginpath . 'includes/css/jsvehiclemanagerrating.css');
wp_enqueue_script( 'picEyes', jsvehiclemanager::$_pluginpath . 'includes/js/jquery.picEyes.js', array( 'jquery' ), false, true );
wp_enqueue_style( 'picEyes', jsvehiclemanager::$_pluginpath . 'includes/css/jquery.picEyes.css', false, false, false );
wp_enqueue_script('jquery-ui-datepicker');
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
wp_enqueue_style('jquery-ui-css', $protocol.'ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
$dateformat = jsvehiclemanager::$_config['date_format'];
if ($dateformat == 'm/d/Y' || $dateformat == 'd/m/y' || $dateformat == 'm/d/y' || $dateformat == 'd/m/Y') {
    $dash = '/';
} else {
    $dash = '-';
}
$firstdash = strpos($dateformat, $dash, 0);
$firstvalue = substr($dateformat, 0, $firstdash);
$firstdash = $firstdash + 1;
$seconddash = strpos($dateformat, $dash, $firstdash);
$secondvalue = substr($dateformat, $firstdash, $seconddash - $firstdash);
$seconddash = $seconddash + 1;
$thirdvalue = substr($dateformat, $seconddash, strlen($dateformat) - $seconddash);
$js_dateformat = '%' . $firstvalue . $dash . '%' . $secondvalue . $dash . '%' . $thirdvalue;
$js_scriptdateformat = $firstvalue . $dash . $secondvalue . $dash . $thirdvalue;
$js_scriptdateformat = str_replace('Y', 'yy', $js_scriptdateformat);

$vehicle = jsvehiclemanager::$_data[0];
$vehicleoptions = json_decode($vehicle->vehicleoptions,true);

function getRowForVideoView($value, $vtype) {
    $html = '<div class="jsvehiclemanager-vehicle-detail-video">';
    if (!empty($value)) {
        parse_str(parse_url($value, PHP_URL_QUERY), $my_array_of_vars);
        if ($vtype == 1 && !empty($my_array_of_vars)) { // youtube video link
            $value = $my_array_of_vars['v'];
            $html .= '<object title="YouTube video player" width="100%;" height="400" data="https://www.youtube.com/embed/' . $value . '"></object>';
        } else { //Embed code
            $html .= str_replace('\"', '', $value);
        }
    }
    $html .= '</div>';
    return $html;
}

function getOverviewItem($title,$value){
	$html='<div class="jsvehiclemanager-vehicle-details-wrapper">
   			<div class="jsvehiclemanager-vehicle-detail">
   				<span class="jsvehiclemanager-detail-title">'.__($title,'js-vehicle-manager').'</span>
   			</div>
   		</div><!--end of jsvm_vehicle-details-wrapper -->
   		<div class="jsvehiclemanager-vehicle-details-wrapper">
   			<div class="jsvehiclemanager-vehicle-detail">
   			<span class="jsvehiclemanager-details-value">'.__($value,'js-vehicle-manager').'</span>
   			</div>
   		</div>';
	return $html;
}
function checkLinks($name) {
    $print = false;
    $configname = $name;

    $config_array = jsvehiclemanager::$_data['config'];
    if ($config_array["$configname"] == 1) {
        $print = true;
    }
    return $print;
}
$fieldmodel = JSVEHICLEMANAGERincluder::getJSModel('fieldordering');

?>
<?php
	include_once( 'vehiclepopups.php' );
?>
<div id="jsvehiclemanager-wrapper">
<div id="jsauto-popup-background"></div>
<div id="jsvehiclemanager-listpopup">
    <span class="popup-title"><span class="title"></span><img id="popup_cross" src="<?php echo jsvehiclemanager::$_pluginpath; ?>includes/images/popup-close.png"></span>
    <div class="jsauto-contentarea"></div>
</div>
        <div class="control-pannel-header">
            <span class="heading">
                <?php echo __('Vehicle Detail', 'js-vehicle-manager'); ?>
            </span>
        </div>

	        <div id="jsvehiclemanager-date-section">
		 		<div id="jsvehiclemanager-date-area">
		 			<span id="jsvehiclemanager-date-text">
						<?php
							echo date_i18n(jsvehiclemanager::$_config['date_format'],strtotime($vehicle->created));
						?>
		 			</span>
		 		</div>
		 		<div id="jsvehiclemanager-social-icons">
		 			<?php do_action('jsvm_vehicledetail_top_btns', $vehicle); // For Tell a Friend button, make an offer, shortlist, test drive, finance calculator,print?>
		 			<?php if(!empty(jsvehiclemanager::$_data[0]->brochure)){ ?>
						<a title="<?php echo esc_attr(__('Brochures','car-manager')); ?>" class="text-muted" target="_blank" href="<?php echo esc_url(jsvehiclemanager::$_data[0]->brochure); ?>"><img src="<?php echo jsvehiclemanager::$_pluginpath.'includes/images/broucher.png'; ?>" alt="<?php echo esc_attr(__('Brochures ICon','car-manager')); ?>" title="<?php echo esc_attr(__('Brochures','car-manager')); ?>"/></a>
					<?php } ?>


		 		</div>
	 		</div>
	 		<div id="jsvehiclemanager-model-price">
		 		<div id="jsvehiclemanager-model">
		 			<span id="jsvehiclemanager-model-text">
		 			<?php $vehicletitle = JSVEHICLEMANAGERincluder::getJSModel('common')->returnVehicleTitle($vehicle->maketitle,$vehicle->modeltitle,$vehicle->modelyeartitle);
		 				echo $vehicletitle;
		 			?>
		 			</span>
		 		</div>
		 		<div id="jsvehiclemanager-price">
		 		    <span id="jsvehiclemanager-price-text">
		 		    <?php $v_price = JSVEHICLEMANAGERincluder::getJSModel('common')->getPrice($vehicle->price,$vehicle->currencysymbol, $vehicle->isdiscount, $vehicle->discounttype, $vehicle->discount, $vehicle->discountstart, $vehicle->discountend); ?>
		 		    	<?php echo esc_html($v_price); ?>
		 		    </span>
		 		</div>
	 		</div>
	 		<div id="jsvehiclemanager-veh-detail-wrapper">
		 	   <div id="jsvehiclemanager-detail-images-wrapper">
		 	   	   <div id="jsvehiclemanager-left-side">
		 	   	   <?php
						if(isset($vehicle->images[0])){
							$imgpath =  $vehicle->images[0]->file .'lw_'. $vehicle->images[0]->filename;
						}else{
							$imgpath = jsvehiclemanager::$_pluginpath.'includes/images/vehicle-image.png';
						}
					?>
		 	   	   		<a href="#">
		 	   	   			<img alt="<?php echo esc_attr(__('Vehicle Image','js-vehicle-manager')); ?>" title="<?php echo esc_attr(__('Vehicle Image','js-vehicle-manager')); ?>" src="<?php echo esc_attr($imgpath); ?>" id="jsvehiclemanager-main-image" class="jsvehiclemanager-vehcileimage"  data-imagenumber="<?php echo isset($vehicle->images[0]) ? 0 : -1; ?>" />
			 			</a>
		 			<?php do_action('jsvm_vehiclelist_mark_vehicle_sold',$vehicle); ?>
			 		</div><!--end of jsvehiclemanager-left-side -->
		 	    	<div id="jsvehiclemanager-right-side">
		 	    		<div id="jsvehiclemanager-thumbs">
		 	    			<div id="jsvehiclemanager-thumbnails">

		 	    				<?php
		 	    				$count = 0;
		 	    				foreach($vehicle->images as $img){
		 	    				?>
		 	    					<div class="jsvehiclemanager-thumbsetting">
			 	    					<a href="#">
			 	    						<img src="<?php echo $img->file.$img->filename?>" class="jsvehiclemanager-vehcileimage" data-imagenumber="<?php echo $count;?>" />
			 	    					</a>
			 	    				</div><!--end of jsvehiclemanager-thumbsetting -->
		 	    				<?php
		 	    				$count += 1;
		 	    				}
		 	    				?>

		 	    			</div><!--end of jsvehiclemanager-thumbnails -->
		 	    		</div><!--end of jsvehiclemanager-thumbs -->
		 	    	</div><!--end of jsvehiclemanager-right-side -->
			   </div><!--end of jsvehiclemanager-detail-images-wrapper -->
		 	   <div id="jsvehiclemanager-data">
		 	   		<div class="jsvehiclemanager-condition-featured-detail">
		 	   			<a href="#">
			 	   			<img src="<?php echo jsvehiclemanager::$_pluginpath.'includes/images/status.png'; ?>"/>
			 	   			<span class="jsvehiclemanager-middle-sec-value"><?php echo (trim($vehicle->conditiontitle)!='') ? __($vehicle->conditiontitle,'js-vehicle-manager') : '---';?></span><!--end of jsvm_middle-sec-value -->
							<span class="jsvehiclemanager-bottom-sec-value"><?php echo __('Condition','js-vehicle-manager');?></span><!--end of jsvm_bottom-sec-value -->
		 	   			</a>
		 	   		</div><!--end of jsvehiclemanager-condition-featured-detail -->
		 	   		<div class="jsvehiclemanager-condition-featured-detail">
		 	   			<a href="#">
		 	   				<img src="<?php echo jsvehiclemanager::$_pluginpath.'includes/images/milage.png'; ?>"/>
			 	   			<span class="jsvehiclemanager-middle-sec-value"><?php echo (trim($vehicle->mileages)!='') ? __($vehicle->mileages,'js-vehicle-manager')."/".__($vehicle->mileagesymbol,'js-vehicle-manager') : '---';?></span><!--end of jsvm_middle-sec-value -->
							<span class="jsvehiclemanager-bottom-sec-value"><?php echo __('Mileage','js-vehicle-manager');?></span><!--end of jsvm_bottom-sec-value -->
		 	   			</a>
		 	   		</div><!--end of jsvehiclemanager-condition-featured-detail -->
		 	   		<div class="jsvehiclemanager-condition-featured-detail">
		 	   			<a href="#">
		 	   				<img src="<?php echo jsvehiclemanager::$_pluginpath.'includes/images/fuel.png'; ?>"/>
			 	   			<span class="jsvehiclemanager-middle-sec-value"><?php echo (trim($vehicle->fueltypetitle)!='') ? __($vehicle->fueltypetitle,'js-vehicle-manager') : '---';?></span><!--end of jsvm_middle-sec-value -->
							<span class="jsvehiclemanager-bottom-sec-value"><?php echo __('Fuel Type','js-vehicle-manager');?></span><!--end of jsvm_bottom-sec-value -->
		 	   			</a>
		 	   		</div><!--end of jsvehiclemanager-condition-featured-detail -->
		 	   		<div class="jsvehiclemanager-condition-featured-detail">
		 	   			<a href="#">
		 	   				<img src="<?php echo jsvehiclemanager::$_pluginpath.'includes/images/engine.png'; ?>"/>
			 	   			<span class="jsvehiclemanager-middle-sec-value"><?php echo (trim($vehicle->enginecapacity)!='') ? __($vehicle->enginecapacity,'js-vehicle-manager')." ".__("CC",'js-vehicle-manager') : '---';?></span><!--end of jsvm_middle-sec-value -->
							<span class="jsvehiclemanager-bottom-sec-value"><?php echo __('Engine Capacity','js-vehicle-manager');?></span><!--end of jsvm_bottom-sec-value -->
		 	   			</a>
		 	   		</div><!--end of jsvehiclemanager-condition-featured-detail -->
		 	   		<div class="jsvehiclemanager-condition-featured-detail">
		 	   			<a href="#">
		 	   				<img src="<?php echo jsvehiclemanager::$_pluginpath.'includes/images/transmission.png'; ?>"/>
			 	   			<span class="jsvehiclemanager-middle-sec-value"><?php echo (trim($vehicle->transmissiontitle)!='') ? __($vehicle->transmissiontitle,'js-vehicle-manager') : '---';?></span><!--end of jsvm_middle-sec-value -->
							<span class="jsvehiclemanager-bottom-sec-value"><?php echo __('Transmission','js-vehicle-manager');?></span><!--end of jsvm_bottom-sec-value -->
		 	   			</a>
		 	   		</div><!--end of jsvehiclemanager-condition-featured-detail -->
		 	   </div><!--end of jsvehiclemanager-data -->
		 	   <div id="jsvehiclemanager-flex-autoz-wrapper">
		 	   		<div id="jsvehiclemanager-detail-flex-autoz">
		 	   			<div id="jsvehiclemanager-flex-autoz-image">
		 	   				<a href="<?php echo jsvehiclemanager::makeUrl(array('jsvmme'=>'user','jsvmlt'=>'viewsellerinfo','jsvehiclemanagerid'=>$vehicle->uid,'jsvehiclemanagerpageid'=>jsvehiclemanager::getPageid()));?>">
		 	   					<img alt="<?php echo esc_attr(__('Seller Profile Icon','js-vehicle-manager')); ?>" title="<?php echo esc_attr(__('Seller Profile','js-vehicle-manager')); ?>" src="<?php echo esc_attr($vehicle->sellerphoto != '' ? $vehicle->sellerphoto : jsvehiclemanager::$_pluginpath.'includes/images/default-images/profile-image.png'); ?>"/>
		 	   				</a>
		 	   			</div><!--end of jsvehiclemanager-flex-image -->
		 	   			<div id="jsvehiclemanager-middle-sec-wrap">
		 	   				<div id="jsvehiclemanager-flex-autoz-middle-sec">
			 	   				<div id="jsvehiclemanager-flex-header">
			 	   					<a href="<?php echo jsvehiclemanager::makeUrl(array('jsvmme'=>'user','jsvmlt'=>'viewsellerinfo','jsvehiclemanagerid'=>$vehicle->uid,'jsvehiclemanagerpageid'=>jsvehiclemanager::getPageid()));?>">
			 	   						<?php echo __($vehicle->sellerinfoname,'js-vehicle-manager');?>
			 	   					</a>
			 	   				</div>
			 	   				<div id="jsvehiclemanager-flex-det-row">
			 	   					<div id="jsvehiclemanager-flex-company-link">
			 	   							<?php echo __($vehicle->sellerinfoweblink,'js-vehicle-manager');?>
			 	   					</div>
			 	   				</div>
			 	   				<div id="jsvehiclemanager-flex-det-row">
			 	   					<div id="jsvehiclemanager-flex-com-address">
			 	   						<?php echo __($vehicle->sellerlocation,'js-vehicle-manager');?>
			 	   					</div>
			 	   				</div>
		 	   				</div><!--end of jsvehiclemanager-flex-autoz-middle-section -->
			 	   			<div id="jsvehiclemanager-flex-autoz-dealer-information-wrapper">
			 	   				<div id="jsvehiclemanager-flex-autoz-dealer-information">
			 	   					<a href="<?php echo jsvehiclemanager::makeUrl(array('jsvmme'=>'user','jsvmlt'=>'viewsellerinfo','jsvehiclemanagerid'=>$vehicle->uid,'jsvehiclemanagerpageid'=>jsvehiclemanager::getPageid()));?>">
				 	   					<span id="jsvehiclemanager-flex-autoz-dealer-information-text">
				 	   						<?php echo __('Seller Information','js-vehicle-manager'); ?>
				 	   					</span>
			 	   					</a>
			 	   				</div>
			 	   			</div><!--end of flex-autoz-dealer-information-wrapper-->
		 	   			</div><!--end of jsvehiclemanager-midle sec wrap -->
		 	   		</div><!--end of jsvehiclemanager-detail-flex-autoz-wrapper -->
		 	   </div><!--end of jsvehiclemanager-flex-Autoz-wrapper -->
		 	    <div id="tabs" class="tabs jsvehiclemanager-vehicle-detail-tabs">
		 	   	    <ul>
		 	   	        <li><a href="#jsvehiclemanager-overview"><?php echo __('Overview', 'js-vehicle-manager'); ?></a></li>
		 	   	        <li><a href="#jsvehiclemanager-features"><?php echo __('Features', 'js-vehicle-manager'); ?></a> </li>
		 	   	        <li><a href="#jsvehiclemanager-gallery"><?php echo __('Gallery', 'js-vehicle-manager'); ?></a></li>
		 	   	        <?php if(checkLinks('vehicledetail_sellerdetail') == true) { ?>
		 	   	        <li><a href="#jsvehiclemanager-sellerinformation" onclick="reDrawMap();"><?php echo __('Seller Information', 'js-vehicle-manager'); ?></a></li>
		 	   	        <?php } ?>
		 	   	    </ul>
		 	   	   	<div id="tabInner">
		 	   	   		<div id="jsvehiclemanager-overview">

				 	<div class="jsvehiclemanager-feature-detail-list">
					 	   		<div class="jsvehiclemanager-feature-detail-sub-heading">
					 	   			<span class="jsvehiclemanager-sub-heading-text"><?php echo __('Specifications','js-vehicle-manager');?></span>
					 	   		</div><!--end of feature-detail-sub-heading -->
					 <?php
		      			foreach (jsvehiclemanager::$_data[4]['main'] as $field) {
							switch ($field->field) {
					      		case 'make':
					      			echo getOverviewItem($field->fieldtitle,$vehicle->maketitle);
				      			break;
					      		case 'model':
					      			echo getOverviewItem($field->fieldtitle,$vehicle->modeltitle);
				      			break;
					      		case 'modelyear':
					      			echo getOverviewItem($field->fieldtitle,$vehicle->modelyeartitle);
				      			break;
					      		case 'vehicletype':
					      			echo getOverviewItem($field->fieldtitle,$vehicle->vehicletitle);
				      			break;
					      		case 'registrationnumber':
					      			echo getOverviewItem($field->fieldtitle,$vehicle->registrationnumber);
				      			break;
				      			case 'stocknumber':
					      			echo getOverviewItem($field->fieldtitle,$vehicle->stocknumber);
				      			break;
				      		}
						}
		      		?>
		            </div><!--end of feature-detail-list -->
		            <?php
					if(
						$fieldmodel->getFieldPublishStatusByfield('feultype', 1) ||
						$fieldmodel->getFieldPublishStatusByfield('transmission', 1) ||
						$fieldmodel->getFieldPublishStatusByfield('cylinder', 1) ||
						$fieldmodel->getFieldPublishStatusByfield('chasisnumber', 1) ||
						$fieldmodel->getFieldPublishStatusByfield('engincenumber', 1) ||
						$fieldmodel->getFieldPublishStatusByfield('acceleration', 1) ||
						$fieldmodel->getFieldPublishStatusByfield('maxspeed', 1) ||
						$fieldmodel->getFieldPublishStatusByfield('co2', 1)
					):
					?>
					<div class="jsvehiclemanager-feature-detail-list">
					 	   		<div class="jsvehiclemanager-feature-detail-sub-heading">
					 	   			<span class="jsvehiclemanager-sub-heading-text"><?php echo __('Mechanical Details','js-vehicle-manager');?></span>
					 	   		</div><!--end of feature-detail-sub-heading -->
		      		<?php
		      			foreach (jsvehiclemanager::$_data[4]['main'] as $field) {
							switch ($field->field) {
					      		case 'feultype':
					      			echo getOverviewItem($field->fieldtitle,$vehicle->fueltypetitle);
				      			break;
					      		case 'transmission':
					      			echo getOverviewItem($field->fieldtitle,$vehicle->transmissiontitle);
				      			break;
					      		case 'cylinder':
					      			echo getOverviewItem($field->fieldtitle,$vehicle->cylindertitle);
				      			break;
					      		case 'chasisnumber':
					      			echo getOverviewItem($field->fieldtitle,$vehicle->chasisnumber);
				      			break;
					      		case 'engincenumber':
					      			echo getOverviewItem($field->fieldtitle,$vehicle->enginenumber);
				      			break;
					      		case 'enginecapacity':
					      			echo getOverviewItem($field->fieldtitle,$vehicle->enginecapacity);
				      			break;
								case 'acceleration':
					      			echo getOverviewItem($field->fieldtitle,$vehicle->acceleration);
				      			break;
					      		case 'maxspeed':
					      			echo getOverviewItem($field->fieldtitle,$vehicle->maxspeed);
				      			break;
					      		case 'co2':
					      			echo getOverviewItem($field->fieldtitle,$vehicle->co2);
				      			break;
				      		}
						}
		      		?>
					</div>

					<?php endif; ?>
					<?php
					if(
						$fieldmodel->getFieldPublishStatusByfield('condition', 1) ||
						$fieldmodel->getFieldPublishStatusByfield('exteriorcolor', 1) ||
						$fieldmodel->getFieldPublishStatusByfield('interiorcolor', 1)
					):
					?>

					<div class="jsvehiclemanager-feature-detail-list">
					 	   		<div class="jsvehiclemanager-feature-detail-sub-heading">
					 	   			<span class="jsvehiclemanager-sub-heading-text"><?php echo __('Appearance And Condition','js-vehicle-manager');?></span>
					 	   		</div><!--end of feature-detail-sub-heading -->
		      		<?php
		      			foreach (jsvehiclemanager::$_data[4]['main'] as $field) {
							switch ($field->field) {
					      		case 'condition':
					      			echo getOverviewItem($field->fieldtitle,$vehicle->conditiontitle);
				      			break;
					      		case 'exteriorcolor':
					      			echo getOverviewItem($field->fieldtitle,$vehicle->exteriorcolor);
				      			break;
					      		case 'interiorcolor':
					      			echo getOverviewItem($field->fieldtitle,$vehicle->interiorcolor);
				      			break;
				      		}
						}
		      		?>
					</div>
					<?php endif; ?>
					<?php
					if(
						$fieldmodel->getFieldPublishStatusByfield('bargainprice', 1) ||
						$fieldmodel->getFieldPublishStatusByfield('exportprice', 1) ||
						$fieldmodel->getFieldPublishStatusByfield('price', 1)
					):
					?>
					<div class="jsvehiclemanager-feature-detail-list">
					 	   		<div class="jsvehiclemanager-feature-detail-sub-heading">
					 	   			<span class="jsvehiclemanager-sub-heading-text"><?php echo __('Pricing','js-vehicle-manager');?></span>
					 	   		</div><!--end of feature-detail-sub-heading -->

		      		<?php
						$vehicle->bargainprice = JSVEHICLEMANAGERincluder::getJSModel('common')->getPrice($vehicle->bargainprice,$vehicle->currencysymbol, 0, 1, 0, '', '');
						$vehicle->exportprice = JSVEHICLEMANAGERincluder::getJSModel('common')->getPrice($vehicle->exportprice,$vehicle->currencysymbol, 0, 1, 0, '', '');
						$vehicle->price = JSVEHICLEMANAGERincluder::getJSModel('common')->getPrice($vehicle->price,$vehicle->currencysymbol, 0, 1, 0, '', '');
		      			foreach (jsvehiclemanager::$_data[4]['main'] as $field) {
							switch ($field->field) {
	      		      			case 'bargainprice':
	      			      			echo getOverviewItem($field->fieldtitle,$vehicle->bargainprice);
	      		      			break;
	      			      		case 'exportprice':
	      			      			echo getOverviewItem($field->fieldtitle,$vehicle->exportprice);
	      		      			break;
	      			      		case 'price':
	      			      			echo getOverviewItem($field->fieldtitle,$vehicle->price);
	      		      			break;
				      		}
						}
		      		?>
					</div>
					<?php endif; ?>
		      		<?php
		      		    $customfields = JSVEHICLEMANAGERincluder::getObjectClass('customfields')->userFieldsData(1,10);// 10 for main section of vehicle
		      		    if(!empty($customfields)){ ?>
		      		    <div class="jsvehiclemanager-feature-detail-list">
					 	   		<div class="jsvehiclemanager-feature-detail-sub-heading">
					 	   			<span class="jsvehiclemanager-sub-heading-text"><?php echo __('Additional Features','js-vehicle-manager');?></span>
					 	   		</div><!--end of feature-detail-sub-heading -->
		      		     <?php
		      		    	foreach($customfields AS $field){
			      		        $array = JSVEHICLEMANAGERincluder::getObjectClass('customfields')->showCustomFields($field, 1,$vehicle->params);
		      		            echo getOverviewItem($array[0],$array[1]);
		      		        }
		      		       ?>
		      		       </div>
		      		       <?php
		      		    }
		      		?>
					<?php
					if(
						$fieldmodel->getFieldPublishStatusByfield('streetaddress', 1) ||
						$fieldmodel->getFieldPublishStatusByfield('registrationnumber', 1) ||
						$fieldmodel->getFieldPublishStatusByfield('locationcity', 1)
					):
					?>
					<div class="jsvehiclemanager-feature-detail-list">
					 	   		<div class="jsvehiclemanager-feature-detail-sub-heading">
					 	   			<span class="jsvehiclemanager-sub-heading-text"><?php echo __('Location','js-vehicle-manager');?></span>
					 	   		</div><!--end of feature-detail-sub-heading -->

		      		<?php
		      			foreach (jsvehiclemanager::$_data[4]['main'] as $field) {
							switch ($field->field) {
	      		      			case 'streetaddress':
	      			      			echo getOverviewItem($field->fieldtitle,$vehicle->streetaddress);
	      		      			break;
								case 'registrationcity':
									$name = JSVEHICLEMANAGERincluder::getJSModel('city')->getLocationDataForView($vehicle->regcity);
									echo getOverviewItem($field->fieldtitle,$name);
								break;
								case 'locationcity':
									$name = JSVEHICLEMANAGERincluder::getJSModel('city')->getLocationDataForView($vehicle->loccity);
									echo getOverviewItem($field->fieldtitle,$name);
								break;
		      			    }
						}
		      		?>
					</div>
					<?php endif;
						foreach (jsvehiclemanager::$_data[4]['main'] as $field) {
							switch ($field->field) {
								case 'map': ?>
					 	   	   		<div class="jsvehiclemanager-feature-detail-list">
							 	   		  <div class="jsvehiclemanager-feature-detail-sub-heading">
							 	   			 <span class="jsvehiclemanager-sub-heading-text"><?php echo __('Map','js-vehicle-manager'); ?></span>
							 	   		  </div><!--end of feature-detail-sub-heading -->
							 	   		  <div id="jsvehiclemanager-description-wrapper">
							 	   		  	<div id="jsvehiclemanager-map_container">
							 	   		  	</div>
							 	   		  </div><!--end of description-wrapper -->
							 	   	</div><!--end of feature-detail-list -->
						 	   	<?php
					 	   		break;
					 	   		case 'description': ?>
						 	   	    <div class="jsvehiclemanager-feature-detail-list">
							 	   		  <div class="jsvehiclemanager-feature-detail-sub-heading">
							 	   			 <span class="jsvehiclemanager-sub-heading-text"><?php echo __('Description','js-vehicle-manager'); ?></span>
							 	   		  </div><!--end of feature-detail-sub-heading -->
							 	   		  <div id="jsvehiclemanager-description-wrapper">
							 	   		  		<?php echo __($vehicle->description,'js-vehicle-manager')?>
							 	   		  </div><!--end of description-wrapper -->
							 	   	</div><!--end of feature-detail-list -->
						 	   	<?php
								break;
								case 'video': ?>
							 	    <div class="jsvehiclemanager-feature-detail-list">
							 	   		  <div class="jsvehiclemanager-feature-detail-sub-heading">
							 	   			 <span class="jsvehiclemanager-sub-heading-text"><?php echo __('Video','js-vehicle-manager'); ?></span>
							 	   		  </div><!--end of feature-detail-sub-heading -->
							 	   		  <div id="jsvehiclemanager-description-wrapper">
							 	   		   <?php
							 	   		   		echo getRowForVideoView($vehicle->video,$vehicle->videotype);
							 	   		   ?>
							 	   		  </div><!--end of description-wrapper -->
							 	   	</div><!--end of feature-detail-list -->
						 	   	<?php
						 	   	break;
					 	   	}
				 	   	} ?>
		 	   	   		</div><!-- end jsvm_overview div -->

		 	   	   		<div id="jsvehiclemanager-features">
		 	   	   		<div class="jsvehiclemanager-feature-presnet-text">
		 	   	   			<?php echo __('Following features are present in selected vehicle.','js-vehicle-manager'); ?>
		 	   	   		</div>
		 	   	   		<!-- body --> <!-- interior --> <!-- exterior  --> <!-- etc -->
		 	   	   		<?php
		 	   	   			$featureHeadArray = array(
		 	   	   					'body','drivetrain','exterior','interior','electronics','safety'
		 	   	   				);

		 	   	   			foreach ($featureHeadArray as $feature) {

		 	   	   				if(is_array(jsvehiclemanager::$_data[4][$feature]) && count(jsvehiclemanager::$_data[4][$feature])>0):
		 	   	   				?>
	 	   	   					<div class="jsvehiclemanager-feature-detail-list">
						 	   		<div class="jsvehiclemanager-feature-detail-sub-heading">
						 	   			<span class="jsvehiclemanager-sub-heading-text"><?php echo __(jsvehiclemanager::$_data[4][$feature][0]->fieldtitle,'js-vehicle-manager');?></span>
						 	   		</div><!--end of feature-detail-sub-heading -->
						 	   		<?php
						      			foreach (jsvehiclemanager::$_data[4][$feature] as $field) {
							      			if(isset($vehicleoptions[$feature][$field->field])){ ?>
									      	<div class="jsvehiclemanager-vehicle-details-wrapper">
								 	   			<div class="jsvehiclemanager-vehicle-detail">
								 	   				<span class="jsvehiclemanager-detail-title"><?php echo __($field->fieldtitle,'js-vehicle-manager'); ?></span>
								 	   			</div>
								 	   		</div><!--end of jsvm_vehicle-details-wrapper -->
							      			<?php }else
											echo  JSVEHICLEMANAGERincluder::getObjectClass('customfields')->viewCustomFieldOfVehicleSection($field,$vehicle->params);
					      				}
						      		?>
								</div><!--end of feature-detail-list-wrapper -->
		 	   	   				<?php
		 	   	   				endif;

		 	   	   			}
		 	   	   		?>
		 	   	   		</div><!-- end jsvm_features div -->

		 	   	   		<div id="jsvehiclemanager-gallery">

		 	   	   		<div class="jsvehiclemanager-feature-detail-list">
					 	   		<div class="jsvehiclemanager-feature-detail-sub-heading">
					 	   			<span class="jsvehiclemanager-sub-heading-text"><?php echo __('Gallery','js-vehicle-manager');?></span>
					 	   		</div><!--end of feature-detail-sub-heading -->
					 	   		<div id="jsvehiclemanager-vehicle-detail-galary">
					 	   		<?php
				      			$imgcount = 0;
			      				foreach ($vehicle->images as $img) {
				      			?>
					      			<div class="jsvehiclemanager-auto-gallerythumbssetting" >
					      			 <a href="#">
				      					<img alt="<?php echo esc_attr(__('Vehicle Image','js-vehicle-manager')); ?>" title="<?php echo esc_attr(__('Vehicle Image','js-vehicle-manager')); ?>" class="jsvehiclemanager-vehcileimage" src="<?php echo esc_attr($img->file.'s_'.$img->filename); ?>" data-imagenumber="<?php echo $imgcount; ?>" />
				      				</a>
					      			</div>
				      			<?php
			  						$imgcount += 1;
				      			}
				      			?>
					 	   		</div><!--end of feature-detail-sub-heading -->
			 	   		</div>

		 	   	   		</div><!-- end jsvm_gallery div -->

		 	   	   		<?php  if(checkLinks('vehicledetail_sellerdetail') == true) { ?>
		 	   	   		<div id="jsvehiclemanager-sellerinformation">

		 	   	   		<div class="jsvehiclemanager-feature-detail-list">
		 	   	   		  <div class="jsvehiclemanager-feature-detail-sub-heading">
			 	   			 <span class="jsvehiclemanager-sub-heading-text"><?php echo __('Seller Information','js-vehicle-manager'); ?></span>
			 	   		  </div><!--end of feature-detail-sub-heading -->
			 	   		</div><!--end of feature-detail-list -->

			 	   		<div class="jsvehiclemanager-dealer-information-wrapper">

			 	   		<div class="jsvehiclemanager-dealer-info-name-wrapper">
			 	   			<div class="jsvehiclemanager-dealer-info-img">
			 	   				<a href="#">
	 	    						<img alt="<?php echo esc_attr(__('Seller Profile Icon','js-vehicle-manager')); ?>" title="<?php echo esc_attr(__('Seller Profile','js-vehicle-manager')); ?>" src="<?php echo esc_attr($vehicle->sellerphoto != '' ? $vehicle->sellerphoto : jsvehiclemanager::$_pluginpath.'includes/images/Users.png'); ?>"/>
	 	    					</a>
			 	   			</div>
			 	   			<div class="jsvehiclemanager-dealer-info-name-right">
			 	   				 <div class="jsvehiclemanager-dealer-info-prop-label">
					 	   			 <span class="jsvehiclemanager-dealer-info-prop-label-text"><?php echo __('Name','js-vehicle-manager'); ?></span>
					 	   		  </div>
					 	   		  <div class="jsvehiclemanager-dealer-info-prop-value">
					 	   			 <span class="jsvehiclemanager-dealer-info-prop-value-text"><?php echo $vehicle->sellerinfoname?></span>
					 	   		  </div>
					 	   		  <div class="jsvehiclemanager-dealer-info-prop-label">
					 	   			 <span class="jsvehiclemanager-dealer-info-prop-label-text"><?php echo __('Website','js-vehicle-manager'); ?></span>
					 	   		  </div>
					 	   		  <div class="jsvehiclemanager-dealer-info-prop-value">
					 	   			 <span class="jsvehiclemanager-dealer-info-prop-value-text"><?php echo $vehicle->sellerinfoweblink?></span>
					 	   		  </div>
					 	   		  <div class="jsvehiclemanager-dealer-info-prop-label">
					 	   			 <span class="jsvehiclemanager-dealer-info-prop-label-text"><?php echo __('Address','js-vehicle-manager'); ?></span>
					 	   		  </div>
					 	   		  <div class="jsvehiclemanager-dealer-info-prop-value">
					 	   			 <span class="jsvehiclemanager-dealer-info-prop-value-text"><?php echo __($vehicle->sellerlocation,'js-vehicle-manager'); ?></span>
					 	   		  </div>
			 	   			</div>
			 	   		</div>

	 	   	   		<div class="jsvehiclemanager-feature-detail-list">
			 	   		  <div class="jsvehiclemanager-feature-detail-sub-heading">
			 	   			 <span class="jsvehiclemanager-sub-heading-text"><?php echo __('Map','js-vehicle-manager'); ?></span>
			 	   		  </div><!--end of feature-detail-sub-heading -->
			 	   		  <div id="jsvehiclemanager-description-wrapper">
			 	   		  	<div id="jsvehiclemanager-map_container-seller">
			 	   		  	</div>
			 	   		  </div><!--end of description-wrapper -->
			 	   	</div><!--end of feature-detail-list -->
		 	   	    <div class="jsvehiclemanager-feature-detail-list">
			 	   		  <div class="jsvehiclemanager-feature-detail-sub-heading">
			 	   			 <span class="jsvehiclemanager-sub-heading-text"><?php echo __('Description','js-vehicle-manager'); ?></span>
			 	   		  </div><!--end of feature-detail-sub-heading -->
			 	   		  <div id="jsvehiclemanager-description-wrapper">
			 	   		  		<?php echo __($vehicle->sellerdescription,'js-vehicle-manager')?>
			 	   		  </div><!--end of description-wrapper -->
			 	   	</div><!--end of feature-detail-list -->
			 	    <div class="jsvehiclemanager-feature-detail-list">
			 	   		  <div class="jsvehiclemanager-feature-detail-sub-heading">
			 	   			 <span class="jsvehiclemanager-sub-heading-text"><?php echo __('Video','js-vehicle-manager'); ?></span>
			 	   		  </div><!--end of feature-detail-sub-heading -->
			 	   		  <div id="jsvehiclemanager-description-wrapper">
			 	   		   <?php
			 	   		   		echo getRowForVideoView($vehicle->sellervideo,$vehicle->sellervideotypeid);
			 	   		   ?>
			 	   		  </div><!--end of description-wrapper -->
			 	   	</div><!--end of feature-detail-list -->
			 	   	</div>
		 	   	   		</div><!-- end jsvm_sellerinformation div -->
		 	   	   		<?php } ?>
		 	   	   	</div>
			   	</div><!--end of tab div -->
				<?php echo do_action('jsvm_vehicledetail_socialshare_btns',$vehicletitle); // For social share ?>
			</div><!--end of jsvehiclemanager-detail-main-wrapper -->




<?php echo JSVEHICLEMANAGERformfield::hidden("jsvm_srtvehicleid",$vehicle->id) ?>


<?php $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://"; ?>
<script type="text/javascript" src="<?php echo $protocol; ?>maps.googleapis.com/maps/api/js?key=<?php echo jsvehiclemanager::$_config['google_map_api_key']; ?>"></script>
<script type="text/javascript">
var marker;
var smarker;
function loadMap() {
    var defaultLatitude = '<?php echo $vehicle->latitude; ?>';
    var defaultLongitude = '<?php echo $vehicle->longitude; ?>';
    var latlng = new google.maps.LatLng(defaultLatitude, defaultLongitude);

    var myOptions = {
        zoom: 10,
        center: latlng,
        scrollwheel: false,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map(document.getElementById("jsvehiclemanager-map_container"), myOptions);
    addMarker(latlng);

}

function loadMapSeller() {
    var defaultLatitude = '<?php echo $vehicle->sellerlatitude; ?>';
    var defaultLongitude = '<?php echo $vehicle->sellerlongitude; ?>';
    var slatlng = new google.maps.LatLng(defaultLatitude, defaultLongitude);

    var myOptions = {
        zoom: 10,
        center: slatlng,
        scrollwheel: false,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    smap = new google.maps.Map(document.getElementById("jsvehiclemanager-map_container-seller"), myOptions);
    addMarkerSeller(slatlng);

}

function addMarker(latlang){
        marker = new google.maps.Marker({
            position: latlang,
            map: map,
            draggable: false,
            scrollwheel: false,
        });
}

function addMarkerSeller(latlang){
        smarker = new google.maps.Marker({
            position: latlang,
            map: smap,
            draggable: false,
            scrollwheel: false,
        });
}

</script>

<script>
/*
when page loads, overview tab is displayed by default and hence seller information tab is hidden
and because of this, map is not rendered, so we need to trigger resize event of map when seller information tab is clicked
to show map, and that only one time
*/
var hasMapBeenReDrawn = false;
function reDrawMap(){
	if( hasMapBeenReDrawn == false ){
		setTimeout(function(){
			var center = smap.getCenter();
	        google.maps.event.trigger(smap, "resize");
	        smap.setCenter(center);
			hasMapBeenReDrawn = true;
		},10);
	}
}
</script>


<script>
	var ajaxurl = '<?php echo admin_url("admin-ajax.php") ?>';
    jQuery(document).ready(function () {
    	if(jQuery('#jsvehiclemanager-map_container').length){
    		loadMap();
    	}
    	<?php if( checkLinks('vehicledetail_sellerdetail') == true ) { ?>
    	loadMapSeller();
    	<?php } ?>
        jQuery("#tabs").tabs();
        jQuery(".custom_date").datepicker({dateFormat: "<?php echo $js_scriptdateformat; ?>"});
    });
</script>
<ul class="clearfix jsvehiclemanager_demo" style="display:none;" >
	<?php
	$count = 0;
	foreach (jsvehiclemanager::$_data[0]->images as $img) { ?>
		<li class="jsvehiclemanager_slid-img" ><img class="jsvehiclemanager_mainimage jsvm_mainimage" src="<?php echo esc_attr($img->file.$img->filename); ?>" data-src="<?php echo esc_attr($img->file.'s_'.$img->filename); ?>" data-src-main="<?php echo $img->file.$img->filename; ?>" data-fuel="<?php echo esc_attr(jsvehiclemanager::$_data[0]->fueltypetitle); ?>" data-transmission="<?php echo esc_attr(jsvehiclemanager::$_data[0]->transmissiontitle); ?>" data-mileage="<?php echo esc_attr(jsvehiclemanager::$_data[0]->mileages.'/'.jsvehiclemanager::$_data[0]->mileagesymbol); ?>" data-price="<?php echo esc_attr($v_price); ?>" data-imagenumber="<?php echo $count; ?>" /></li>
	<?php
	$count += 1;
	} ?>
</ul>


<?php
} else {
    $msg = __('No record found','js-vehicle-manager');
    echo JSVEHICLEMANAGERlayout::getNoRecordFound($msg);
}
?>
