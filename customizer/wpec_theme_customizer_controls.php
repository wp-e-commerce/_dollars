<?php
/**
 * Base class to enqueue common JS files extends the core WP_Customize_Control
 * This class is only available when the Core Theme Customizer is open
 */
class WPEC_Theme_Customizer_Base_Control extends WP_Customize_Control{
	public function enqueue() {
		wp_enqueue_script('wpec-tc-utils', WPEC_TC_DIRECTORY . '/js/wpec-theme-customizer-utils.js', array('jquery','jquery-ui'));
		wp_enqueue_script('jquery-ui', WPEC_TC_DIRECTORY . '/js/jquery-ui-1.8.20.custom.min.js', array('jquery'));
		wp_enqueue_style('jquery-ui-aristo', WPEC_TC_DIRECTORY . '/css/aristo/aristo.css');
		wp_enqueue_style('wpec-tc-custom-controls', WPEC_TC_DIRECTORY . '/css/wpec-tc-custom-controls.css');
		
	}
}
/**
 * Array conntrol
 */	
class WPEC_Theme_Customizer_Array_Control extends WPEC_Theme_Customizer_Base_Control{
	public $fields;
	public $id;
	public function __construct( $manager, $id, $args, $fields) {
		parent::__construct( $manager, $id, $args );
		$this->id = $id;
		$this->fields = $fields;
	}	
	
	public function render_content() {
		?>
		<label>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<div class='wpec-tc-array-control'>
				<?php
				if($this->fields)
				{
					echo '<table><form>';
					foreach($this->fields as $field)
					{
						echo "
						<tr>
							<td>$field</td>
							<td><input type='text' class='$this->id'/></td>
						</tr>
						";
					}
					echo '</form></table>';
				}
				?>
				<input id='<?php echo $this->id;?>' value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?>/>
			</div>
		</label>
		<script type='text/javascript'>
		jQuery('.<?php echo $this->id; ?>').each().bind("propertychange keyup input paste", function(event){
			alert('changed!!');
		});
		</script>
		<?php
	}
}
/**
 * Slider control
 */
class WPEC_Theme_Customizer_Slider_Control extends WPEC_Theme_Customizer_Base_Control {
	public $type    = '';
	public $removed = '';
	public $context;
	public $input_id;
	public $slider_id;
	
	/**
	 * Constructor
	 * @param $dimens keyyed array of 'min' and 'max' values
	 */
	public function __construct( $manager, $id, $args, $dimens = array('min'=> 0, 'max'=> 10)) {
		parent::__construct( $manager, $id, $args );
		$this->dimens = $dimens;
		$this->input_id = 'amount-'.$id;
		$this->slider_id = 'slider-'.$id;
	}

	public function render_content() {
		?>
		<label>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<div class='wpec-tc-control-wrapper wpec-tc-slider'>
				<p>
				<div id='<?php echo $this->slider_id; ?>'></div>
				</p>
				<p>
					Value <input id='<?php echo $this->input_id;?>' value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?>/>
				</p>
			</div>
		</label>
		<script type='text/javascript'>
		var display = jQuery("#<?php echo $this->input_id;?>");
		var slider = jQuery("#<?php echo $this->slider_id; ?>");
		//setup slider
		jQuery(function() {
			slider.slider(
				{  
				range: "min",
				value: <?php echo $this->value() ?  esc_attr( $this->value()) : $this->dimens['min'] ; ?>,
				min: <?php echo $this->dimens['min']; ?>,
				max: <?php echo $this->dimens['max']; ?>,
				slide: function( event, ui ) {
					display.val(ui.value); //set value of display to match slider
					var e = jQuery.Event("keyup");
					e.which = 50; // Some key code value
					display.trigger(e); //trigger typing event to prompt ajax
							}
						}
					)
			});
		//listen for typed input and adjust slider
		display.keyup(function() {
			slider.slider( 'value' , display.val() );
		});
		
		
		</script>
		<?php
	}
}
/**
 * Control for rendering information panels
 */	
class WPEC_Theme_Customizer_Info_Control extends WPEC_Theme_Customizer_Base_Control{
	public $body;
	public function __construct( $manager, $id, $args, $body) {
		parent::__construct( $manager, $id, $args );
		$this->body = $body;
	}	
	
	public function render_content() {
		?>
		<label>
			<div class='wpec-tc-info-control'>
				<?php echo $this->body; ?>
			</div>
		</label>
		<?php
	}
}
/**
 * Theme Switcher
 */	
class WPEC_Theme_Customizer_Code_Mirror extends WPEC_Theme_Customizer_Base_Control{
	
	public function __construct( $manager, $id, $args) {
		parent::__construct( $manager, $id, $args );
	}	
	
	public function enqueue(){
		parent::enqueue();
		//code mirror files
		wp_enqueue_script('code-mirror-library', WPEC_TC_DIRECTORY . '/libraries/CodeMirror-2.24/lib/codemirror.js');
		wp_enqueue_script('code-mirror-mode-css', WPEC_TC_DIRECTORY . '/libraries/CodeMirror-2.24/mode/css/css.js');
		wp_enqueue_style('code-mirror-css', WPEC_TC_DIRECTORY . '/libraries/CodeMirror-2.24/lib/codemirror.css'); 
	}
	
	public function render_content() {
		?>
		<div class="customize-control-title">CSS Editor</div>
			<div id='wpec-tc-code-mirror-wrapper' class='wpec-tc-control-wrapper wpec-tc-code-mirror'>
				
				<textarea id='wpec-tc-code-mirror' name='wpec-tc-code-mirror'><?php 
					$code_mirror = split('<LINEBREAK>', $this->value());
					foreach($code_mirror as $code)
					{
						echo "$code".PHP_EOL;  
					}  
					?></textarea>
				
				<div class='code-mirror-controls'>
					
					<span class='code-mirror-credits'>Powered By CodeMirror</span>
					<a id='code-mirror-refresh' class='button'>Refresh</a>
				 </div> 
					<input hidden='hidden' value="<?php echo esc_attr( $this->value() ); ?>" id='wpec-tc-code-mirror-hidden-value' <?php $this->link(); ?>/>
			</div>    
			     
		<script type='text/javascript'>
		jQuery(document).ready(function(){
			var e = jQuery.Event("keyup");
			e.which = 50; // Some key code value
			var textarea = document.getElementById('wpec-tc-code-mirror');
			var myCodeMirror = CodeMirror.fromTextArea(textarea,    
				{  
				onUpdate: codemirrorcallback,
				
				});
			function codemirrorcallback(){
				myCodeMirror.save();
				jQuery('#wpec-tc-code-mirror-hidden-value').val(textarea.value.replace(/\n\r?/g, '<LINEBREAK>'));
			}
			jQuery('#code-mirror-refresh').click(
			function(){
				var display = jQuery('#wpec-tc-code-mirror-hidden-value');
				display.trigger(e); //trigger typing event to prompt ajax
			});
		});
		</script>
	
	<?php
	
	}
}
/**
 * Theme Switcher
 */	
class WPEC_Theme_Customizer_Theme_Switcher_Control extends WPEC_Theme_Customizer_Base_Control{
	
	public function __construct( $manager, $id, $args) {
		parent::__construct( $manager, $id, $args );
	}	
	
	public function render_content() {
		?>
			<div class='wpec-tc-control-wrapper wpec-tc-theme-control'>
				<?php
				$themes = get_themes();
				echo '<form>';
				foreach($themes as $theme){
					$name = $theme->name;
					echo "<input name='theme-radio' value='$name' type='radio'/><b>$name</b><br/>";  
				}
				echo '</form>';  
				?>  
			</div>
			
		<input value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?>/>
		<script type='text/javascript'>
			jQuery("input[name='controlQuestion']").change(function(){
				radioValue = jQuery(this).val();
        		alert(radioValue);

			});
		</script>
	
	<?php
	
	}
}
		
class WPEC_Theme_Customizer_Sortable_Sidebar_Control extends WPEC_Theme_Customizer_Base_Control {
	
	public function __construct( $manager, $id, $args) {
		parent::__construct( $manager, $id, $args );
	}
	
	public function render_content() {
		?>
		<label>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<div class='wpec-tc-control-wrapper wpec-tc-sortable-wrapper'>
				<?php
				$sortable_ui_ids = array();
				//var_dump( get_option('sidebars_widgets') );
				$sidebars = wp_get_sidebars_widgets();
				foreach($sidebars as $sidebar)
				{
					if(count($sidebar) > 0)
					{
					$key = array_keys($sidebars, $sidebar);
					if(count($key)>0)
						$key = $key[0];
					echo "<ul class='sidebar-sortable-ui-widget' data-sidebar-name='$key'>";//with data attr of sidebar name
						foreach($sidebar as $widget)
						{
							$id = $widget; 
							$name = $widget;						
							echo "<li id='$id' class='ui-state-default sortable-item'>
									<div class='sortable-item-inner'>
									<span class='ui-icon ui-icon-arrowthick-2-n-s sortable-icon'></span>
									<span class='sortable-name'>$name</span>
									</div>
								</li>";
						}
					echo '</ul>';  
					}
					  
				}
				?>
				<input id='sidebar-widget-order-value' hidden='hidden' value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?>/>	
			</div>
		</label>
		<script>
		jQuery(function() {
			jQuery( ".sidebar-sortable-ui-widget" ).sortable( //setup all the sortable widgets
					{
					update: function(event, ui) { 
						var sidebars = '';
						var count = 0;  
						jQuery('.sidebar-sortable-ui-widget').each(function(){
							if(count>0)
								sidebars+='||';  
							sidebars+=jQuery(this).attr('data-sidebar-name')+'==';
							var this_sidebar_order = jQuery(this).sortable('toArray');
							var i = 0;
							for(i = 0 ; i < this_sidebar_order.length ; i++)
							{
								if(i>0)
									sidebars+=', ';
								sidebars+=this_sidebar_order[i];
							}
							 	
						});
						var display = jQuery('#sidebar-widget-order-value');
						display.val(sidebars);
						var e = jQuery.Event('keyup');  
						e.which = 50; // Some key code value
						display.trigger(e); //trigger typing event to prompt ajax
					}
				}
			);
			jQuery( "#sortable" ).disableSelection();
		});
		
		</script>
		<?php
	}
} 		 

class WPEC_Theme_Customizer_Sortable_Control extends WPEC_Theme_Customizer_Base_Control {
	
	
	public function __construct( $manager, $id, $args, $objects) {
		parent::__construct( $manager, $id, $args );
	}

	public function render_content() {
		?>
		<label>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<div class='wpec-tc-control-wrapper wpec-tc-sortable-wrapper'>
					<?php
					echo "<ul class='sortable-ui-widget'>";//with data attr of sidebar name
						foreach($sidebar as $object)
						{
							$id = $widget; 					
							echo "<li id='$id' class='ui-state-default sortable-item'>
									<div class='sortable-item-inner'>
									<span class='ui-icon ui-icon-arrowthick-2-n-s sortable-icon'></span>
									<span class='sortable-name'>$name</span>
									</div>
								</li>";
						}
					echo '</ul>';  
					
					  
				
				?>
				<input id='sidebar-widget-order-value' hidden='hidden' value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?>/>	
			</div>
		</label>
		<script>
		
		</script>
		<?php
	}
} 		 
?>