<?php
/*
	Question2Answer Ask Notices plugin, v1.0
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
			$json = qa_opt( 'ask_notices_data' );
			$data = json_decode( $json, true );
			if ( count($data) === 0 )
				return;

			$tmpl = file_get_contents( QA_HTML_THEME_LAYER_DIRECTORY.'/template.js' );
			$js_var = 'var ask_notices = ' . $json . ';';
			$js = str_replace( '//ASK_NOTICE_DATA', $js_var, $tmpl );
			$this->output_raw('<script>' . $js . '</script>');
		}
	}

}
