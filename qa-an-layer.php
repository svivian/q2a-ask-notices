<?php
/*
	Question2Answer Ask Notices plugin
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
			$json = qa_opt('ask_notices_data');
			$data = json_decode( $json, true );
			if ( count($data) === 0 )
				return;

			$tmpl = file_get_contents( QA_HTML_THEME_LAYER_DIRECTORY.'/template.js' );

			// import matching option
			$match_anywhere = qa_opt('ask_notices_match') === '1' ? 'true' : 'false';
			$js_match = 'var match_anywhere = ' . $match_anywhere . ';';
			$tmpl = str_replace('//ASK_NOTICE_MATCH', $js_match, $tmpl);

			// import notices
			$js_notices = 'var notices = ' . $json . ';';
			$tmpl = str_replace( '//ASK_NOTICE_DATA', $js_notices, $tmpl );

			$this->output_raw('<script>' . $tmpl . '</script>');
		}
	}

}
