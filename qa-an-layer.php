<?php
/*
	Question2Answer Ask Notices plugin
	License: http://www.gnu.org/licenses/gpl.html
*/

class qa_html_theme_layer extends qa_html_theme_base
{
	// add JavaScript
	function head_script()
	{
		qa_html_theme_base::head_script();

		if ( $this->template != 'ask' )
			return;

		$json = qa_opt('ask_notices_data');
		$data = json_decode( $json, true );
		if ( count($data) === 0 )
			return;

		$js = file_get_contents( QA_HTML_THEME_LAYER_DIRECTORY.'/template.js' );

		// import matching option
		$matchAnywhere = qa_opt('ask_notices_match') === '1' ? 'true' : 'false';
		$js = str_replace('ASK_NOTICE_MATCH', $matchAnywhere, $js);

		// import notices
		$js = str_replace('ASK_NOTICE_DATA', $json, $js);

		$this->output_raw('<script>'.$js.'</script>');
	}

}
