<?php

/*
	Question2Answer 1.2.1 (c) 2010, Gideon Greenspan

	http://www.question2answer.org/

	
	File: qa-include/qa-page-login.php
	Version: 1.2.1
	Date: 2010-07-29 03:54:35 GMT
	Description: Controller for login page


	This software is free to use and modify for public websites, so long as a
	link to http://www.question2answer.org/ is displayed on each page. It may
	not be redistributed or resold, nor may any works derived from it.
	
	More about this license: http://www.question2answer.org/license.php


	THIS SOFTWARE IS PROVIDED "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES,
	INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY
	AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL
	THE COPYRIGHT HOLDER BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
	SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED
	TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR
	PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF
	LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
	NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
	SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

	if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
		header('Location: ../');
		exit;
	}


//	Check we're not using single-sign on integration and that we're not logged in
	
	if (QA_EXTERNAL_USERS)
		qa_fatal_error('User login is handled by external code');
		
	if (isset($qa_login_userid))
		qa_redirect('');
		

//	Process submitted form after checking we haven't reached rate limit
	
	require_once QA_INCLUDE_DIR.'qa-app-limits.php';

	$passwordsent=qa_get('ps');

	if (qa_limits_remaining($qa_db, null, 'L')) {
		if (qa_clicked('dologin')) {
			require_once QA_INCLUDE_DIR.'qa-db-users.php';
			require_once QA_INCLUDE_DIR.'qa-db-selects.php';
		
			$inemailhandle=qa_post_text('emailhandle');
			$inpassword=qa_post_text('password');
			$inremember=qa_post_text('remember');
			
			$errors=array();
			
			if (strpos($inemailhandle, '@')===false) // handles can't contain @ symbols
				$matchusers=qa_db_user_find_by_handle($qa_db, $inemailhandle);
			else
				$matchusers=qa_db_user_find_by_email($qa_db, $inemailhandle);
	
			if (count($matchusers)==1) { // if matches more than one (should be impossible), don't log in
				$inuserid=$matchusers[0];
				$userinfo=qa_db_select_with_pending($qa_db, qa_db_user_account_selectspec($inuserid, true));
				
				if (strtolower(qa_db_calc_passcheck($inpassword, $userinfo['passsalt'])) == strtolower($userinfo['passcheck'])) { // login and redirect
					require_once QA_INCLUDE_DIR.'qa-app-users.php';
	
					qa_set_logged_in_user($qa_db, $inuserid, $userinfo['handle'], $inremember ? true : false);
					qa_db_user_logged_in($qa_db, $inuserid, @$_SERVER['REMOTE_ADDR']);
					
					$topath=qa_get('to');
					
					if (isset($topath))
						qa_redirect_raw($topath); // path already provided as URL fragment
					elseif ($passwordsent)
						qa_redirect('account');
					else
						qa_redirect('');
	
				} else
					$errors['password']=qa_lang('users/password_wrong');
	
			} else
				$errors['emailhandle']=qa_lang('users/user_not_found');
				
			qa_limits_increment($qa_db, null, 'L'); // only get here if we didn't log in successfully

		} else
			$inemailhandle=qa_get('e');
		
	} else
		$pageerror=qa_lang('users/login_limit');

	
//	Prepare content for theme
	
	qa_content_prepare();

	$qa_content['title']=qa_lang_html('users/login_title');
	
	$qa_content['error']=@$pageerror;

	if (empty($inemailhandle) || isset($errors['emailhandle']))
		$forgotpath=qa_path('forgot');
	else
		$forgotpath=qa_path('forgot', array('e' => $inemailhandle));
	
	$forgothtml='<A HREF="'.qa_html($forgotpath).'">'.qa_lang_html('users/forgot_link').'</A>';
	
	$qa_content['form']=array(
		'tags' => ' METHOD="POST" ACTION="'.qa_self_html().'" ',
		
		'style' => 'tall',
		
		'ok' => $passwordsent ? qa_lang_html('users/password_sent') : null,
		
		'fields' => array(
			'email_handle' => array(
				'label' => qa_lang_html('users/email_handle_label'),
				'tags' => ' NAME="emailhandle" ID="emailhandle" ',
				'value' => qa_html(@$inemailhandle),
				'error' => qa_html(@$errors['emailhandle']),
			),
			
			'password' => array(
				'type' => 'password',
				'label' => qa_lang_html('users/password_label'),
				'tags' => ' NAME="password" ID="password" ',
				'value' => qa_html(@$inpassword),
				'error' => empty($errors['password']) ? '' : (qa_html(@$errors['password']).' - '.$forgothtml),
				'note' => $passwordsent ? qa_lang_html('users/password_sent') : $forgothtml,
			),
			
			'remember' => array(
				'type' => 'checkbox',
				'label' => qa_lang_html('users/remember_label'),
				'tags' => ' NAME="remember" ',
				'value' => @$inremember ? true : false,
			),
		),
		
		'buttons' => array(
			'login' => array(
				'label' => qa_lang_html('users/login_button'),
			),
		),
		
		'hidden' => array(
			'dologin' => '1',
		),
	);
	
	$qa_content['focusid']=(isset($inemailhandle) && !isset($errors['emailhandle'])) ? 'password' : 'emailhandle';


/*
	Omit PHP closing tag to help avoid accidental output
*/