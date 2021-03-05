<?php

class DIDM_MainMenuCreator {


	function __construct() {

		add_action( 'admin_menu', array( $this, 'didm__register_my_custom_submenu_page' ) );

	}

	function didm__register_my_custom_submenu_page() {
		add_menu_page( 'Database Management', 'Database Management', 'manage_options', 'didm' );

		/*
		add_submenu_page(
			'tools.php',
			'Master Data Upload',
			'My Custom Submenu Page',
			'manage_options',
			'my-custom-submenu-page',
			array($this,'didm__my_custom_submenu_page_callback' ));
		*/
		add_submenu_page( 'didm', 'Master Data Upload', 'Master Data Upload',
			'manage_options', 'didm-master-data-upload', array( $this, 'didm__my_custom_submenu_page_callback' ) );
		add_submenu_page( 'didm', 'Transaction Data Upload', 'Transaction Data Upload',
			'manage_options', 'didm-txn-data-upload' );

	}

	function didm__my_custom_submenu_page_callback() {
		echo '<div class="wrap"><div id="icon-tools" class="icon32"></div>';
		echo '<h2>Master Data Upload</h2>';
		echo '<div class="options">
                <form method="post" action="#" enctype="multipart/form-data">
				<p>
                    <label>Upload Sub Region CSV</label>
                    <br />
                    <input type="file" name="upload-subregion" value="" />
                </p>
                <input type="submit" name="upload" value="Submit" />
				</form>
        </div><!-- #universal-message-container -->';
		echo '</div>';


		if ( isset( $_POST['upload'] ) ) {
			global $wpdb;
			$datafile = $_FILES['upload-subregion']['tmp_name'];
			$file     = wp_upload_dir()['path'] . '/' . $_FILES['upload-subregion']['name'];
			$fileUrl  = wp_upload_dir()['url'] . '/' . $_FILES['upload-subregion']['name'];
			if ( ! move_uploaded_file( $_FILES['upload-subregion']['tmp_name'], $file ) ) {
				print_r( 'Failed to move uploaded file.' );
			}


			$sql   = "
	        LOAD DATA LOCAL INFILE '" . $fileUrl . "'
	        INTO TABLE wp_staging_subregion
	        FIELDS TERMINATED BY ',' 
	        LINES TERMINATED BY '\r\n'
	        (subregion_code,subregion_desc,region_code)
	        ";
			$query = $wpdb->query( $sql );
		}

		echo '<h2>Staging Data Upload</h2>';
		echo '<div class="options">
                <form method="post" action="#" enctype="multipart/form-data">
				<p>
                    <label>Upload Staging table data</label>
                    <br />
                </p>
                <input type="submit" name="submitbutton" value="Transfer staging data" />
				</form>
        </div><!-- #universal-message-container -->';
		echo '</div>';
		if ( isset( $_POST['submitbutton'] ) ) 
		{
			global $wpdb;
			$sql   = "
	        INSERT INTO wp_main_subregion(subregion_code,subregion_desc,region_id) 
	        SELECT wp_staging_subregion.subregion_code,wp_staging_subregion.subregion_desc,wp_main_region.region_id
	        FROM wp_staging_subregion NATURAL JOIN wp_main_region 
	        WHERE wp_staging_subregion.region_code=wp_main_region.region_code LIMIT 10 OFFSET 0
	        ";
			$query = $wpdb->query( $sql );
		}

	}
}

?>