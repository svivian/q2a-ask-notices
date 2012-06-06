<?php
/*
	Question2Answer Ask Notices plugin, v0.9
	License: http://www.gnu.org/licenses/gpl.html
*/

class qa_html_theme_layer extends qa_html_theme_base
{
	// theme replacement functions

	function head_script()
	{
		qa_html_theme_base::head_script();

		if ( $this->template == 'ask' )
		{
			$data = json_decode( qa_opt( 'ask_notices_data' ) );
			if ( count($data) < 2 )
				return;

			$triggers_js = '["' . str_replace( ',', '","', $data[0] ) . '"]';
			$notice_js = '"' . addslashes( $data[1] ) . '"';

			$JS = file_get_contents( QA_HTML_THEME_LAYER_DIRECTORY.'/template.js' );

			// do replacements here
			$search = array( 'QA_NOTICE_TRIGGERS', 'QA_NOTICE_MESSAGE' );
			$replace = array( $triggers_js, $notice_js );
			$this->output_raw('<script>' . str_replace( $search, $replace, $JS ) . '</script>');
		}

	}


}