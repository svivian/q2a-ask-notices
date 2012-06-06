<?php
/*
	Question2Answer Ask Notices plugin, v0.9
	License: http://www.gnu.org/licenses/gpl.html
*/

class qa_ask_notices
{
	private $directory;
	private $urltoroot;

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
// 		echo '<pre>', print_r($post,true), '</pre>';

		$saved_msg = '';
		$form_btn = array(
			array(
				'label' => 'Add notice',
				'tags' => 'name="ask_notices_add"',
			),
			array(
				'label' => 'Save Changes',
				'tags' => 'name="ask_notices_save"',
			),
		);

		if ( qa_clicked('ask_notices_add') )
		{
			// add a blank field and save current data
			$post[] = array(
				'keys' => '',
				'text' => '',
			);

			$data = $this->_save_notices( $post );
			$saved_msg = 'Settings saved.';
		}

		if ( qa_clicked('ask_notices_save') )
		{
			$data = $this->_save_notices( $post );
			$saved_msg = 'Settings saved.';
		}

		if ( empty($data) )
		{
			return array(
				'note' => 'testing notes',
				'custom' => 'notes testing',
				'html' => 'qwerty',
				'style' => 'wide',
				'ok' => $saved_msg,
				'fields' => $this->_notice_fields(0),
				'buttons' => $form_btn,
			);
		}


		// data already exists: set up array of fields
		$fields = array(
			array(
				'style' => 'tall',
				'type' => 'static',
				'note' => 'Keywords: the trigger words, separated by commas, e.g. <code>best,worst</code>.<br>Notice: the message you wish to display (HTML allowed), e.g. <code>Your question appears to be subjective and &lt;em&gt;may be closed&lt;/em&gt;.</code>',
			),
		);
		for ( $i = 0, $len = count($data); $i < $len; $i++ )
		{
			$fields[] = array(
				'label' => 'Keywords #'.($i+1),
				'tags' => 'name="ask_notices['.$i.'][keys]"',
				'value' => qa_html($data[$i]['keys']),
			);
			$fields[] = array(
				'label' => 'Notice #'.($i+1),
				'tags' => 'name="ask_notices['.$i.'][text]"',
				'value' => qa_html($data[$i]['text']),
				'note' => '<label><input type="checkbox" name="ask_notices['.$i.'][delete]"> Delete</label>',
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

/*
best,worst,should,better
Your question appears to be subjective and may be closed.<br>Please check the guidelines to the right and the <a href="/pokebase/rules">rules page</a>.

moveset
If you are looking for Pokemon movesets, we already have them! Please use the search box above or check the <a href="/pokebase/rules">rules page</a> for more information.

team
All <q>Rate My Team</q> type questions must be asked on the <a href="/pokebase/rmt/">Battle Subway section here</a>.
*/
