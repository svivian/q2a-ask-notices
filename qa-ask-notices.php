<?php
/*
	Question2Answer Ask Notices plugin, v1.0
	License: http://www.gnu.org/licenses/gpl.html
*/

class qa_ask_notices
{
	private $directory;
	private $urltoroot;
	private $opt_match = 'ask_notices_match';

	public function load_module($directory, $urltoroot)
	{
		$this->directory = $directory;
		$this->urltoroot = $urltoroot;
	}

	public function admin_form( &$qa_content )
	{
		// data is stored as JSON array of fields
		$json = qa_opt('ask_notices_data');
		$data = json_decode($json, true);
		$post = @$_POST['ask_notices'];

		$an_match = qa_opt($this->opt_match);

		$saved_msg = '';
		$form_btn = array(
			array(
				'label' => qa_lang_html('asknotices/admin_add_notice'),
				'tags' => 'name="ask_notices_add"',
			),
			array(
				'label' => qa_lang_html('admin/save_options_button'),
				'tags' => 'name="ask_notices_save"',
			),
		);

		if ( qa_clicked('ask_notices_add') )
		{
			// save current data then add a blank field
			$data = $this->_save_notices( $post );
			$data[] = array(
				'keys' => '',
				'text' => '',
			);
		}

		if ( qa_clicked('ask_notices_save') )
		{
			$an_match = qa_post_text($this->opt_match) ? '1' : '0';
			qa_opt($this->opt_match, $an_match);

			$data = $this->_save_notices( $post );
			$saved_msg = qa_lang_html('admin/options_saved');
		}

		// data already exists: set up array of fields
		$fields = array(
			array(
				'type' => 'checkbox',
				'label' => qa_lang('asknotices/admin_match_label'),
				'tags' => 'name="ask_notices_match"',
				'value' => $an_match === '1',
				'note' => qa_lang('asknotices/admin_match_note'),
			),
			array(
				'style' => 'tall',
				'type' => 'static',
				'note' => qa_lang('asknotices/admin_data_note'),
			),
		);
		for ( $i = 0, $len = count($data); $i < $len; $i++ )
		{
			$fields[] = array(
				'label' => qa_lang_html_sub('asknotices/admin_keywords_n', ($i+1)),
				'tags' => 'name="ask_notices['.$i.'][keys]"',
				'value' => qa_html($data[$i]['keys']),
			);
			$fields[] = array(
				'label' => qa_lang_html_sub('asknotices/admin_notice_n', ($i+1)),
				'tags' => 'name="ask_notices['.$i.'][text]"',
				'value' => qa_html($data[$i]['text']),
				'note' => '<label style="white-space:nowrap"><input type="checkbox" name="ask_notices['.$i.'][delete]"> ' .
					qa_lang_html('asknotices/admin_delete') . '</label>',
			);
			$fields[] = array(
				'type' => 'blank',
			);
		}

		return array(
			'style' => 'wide',
			'ok' => $saved_msg,
			'fields' => $fields,
			'buttons' => $form_btn,
		);
	}


	private function _notice_fields( $n )
	{
		return array(
			array(
				'label' => 'Keywords #'.($n+1),
				'tags' => 'name="ask_notices['.$n.'][keys]"',
				'note' => 'Trigger keywords, separated by commas',
			),
			array(
				'label' => 'Notice #'.($n+1),
				'tags' => 'name="ask_notices['.$n.'][text]"',
				'note' => 'Error message (HTML allowed)',
			)
		);
	}

	private function _save_notices( $post )
	{
		$data = array();
		foreach ( $post as $i=>$note )
		{
			if ( !isset( $note['delete'] ) )
			{
				$data[$i]['keys'] = preg_replace( '/\r\n?/', "\n", trim( qa_gpc_to_string($note['keys']) ) );
				$data[$i]['text'] = preg_replace( '/\r\n?/', "\n", trim( qa_gpc_to_string($note['text']) ) );
			}
		}

		qa_opt( 'ask_notices_data', json_encode($data) );

		return $data;
	}

}
