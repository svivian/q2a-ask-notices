$(function(){
	var $input = $('#title');
	var err_style = 'display: none; font-size: 13px; font-weight: normal';

	var poll_words = QA_NOTICE_TRIGGERS;
	var poll_msg = QA_NOTICE_MESSAGE;

// 	var poll_words = ['best','worst','should','better'];
// 	var poll_msg = 'Your question appears to be subjective and may be closed.<br>Please check the guidelines to the right and the <a href="/pokebase/rules">rules page</a>.';

	var moveset_words = ['moveset'];
	var moveset_msg = 'If you are looking for Pokemon movesets, we already have them! Please use the search box above or check the <a href="/pokebase/rules">rules page</a> for more information.';

	var team_words = ['team'];
	var team_msg = 'All <q>Rate My Team</q> type questions should be asked on the <a href="/pokebase/rmt/">Battle Subway section here</a>.';


	function make_error( msg )
	{
		return $('<div id="qa-error-title" class="qa-error" style="' + err_style + '">'+msg+'</div>');
	}

	function find_triggers( triggers, words )
	{
		for ( var i in triggers )
		{
			if ( $.inArray( triggers[i], words ) >= 0 )
			{
				return true;
			}
		}
	}

	$input.change(function(){
		$('#qa-error-title').remove();
		var title = $(this).val();
		var words = title.split(/\s+/);

		$err = null;

		if ( find_triggers(poll_words, words) )
		{
			$err = make_error(poll_msg);
		}
		else if ( find_triggers(moveset_words, words) )
		{
			$err = make_error(moveset_msg);
		}

		if ( $err )
			$err.insertAfter($input).fadeIn('slow');
	});

});
