<?php

/*
	Question2Answer 1.0-beta-1 (c) 2010, Gideon Greenspan

	http://www.question2answer.org/

	
	File: qa-include/qa-db-users.php
	Version: 1.0-beta-1
	Date: 2010-02-04 14:10:15 GMT


	This software is licensed for use in websites which are connected to the
	public world wide web and which offer unrestricted access worldwide. It
	may also be freely modified for use on such websites, so long as a
	link to http://www.question2answer.org/ is displayed on each page.


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

	function qa_db_calc_passcheck($password, $salt)
	{
		return sha1(substr($salt, 0, 8).$password.substr($salt, 8));
	}
	
	function qa_db_user_create($db, $email, $password, $handle, $level, $ip)
	{	
		require_once QA_INCLUDE_DIR.'qa-util-string.php';
		
		$salt=qa_random_alphanum(16);
		
		qa_db_query_sub($db,
			'INSERT INTO ^users (created, createip, email, passsalt, passcheck, level, handle, loggedin, loginip) '.
			'VALUES (NOW(), COALESCE(INET_ATON($), 0), $, $, UNHEX($), #, $, NOW(), COALESCE(INET_ATON($), 0))',
			$ip, $email, $salt, qa_db_calc_passcheck($password, $salt), (int)$level, $handle, $ip
		);
		
		return qa_db_last_insert_id($db);
	}
		
	function qa_db_user_find_by_email($db, $email)
	{
		return qa_db_read_all_values(qa_db_query_sub($db,
			'SELECT userid FROM ^users WHERE email=$',
			$email
		));
	}

	function qa_db_user_find_by_handle($db, $handle)
	{
		return qa_db_read_all_values(qa_db_query_sub($db,
			'SELECT userid FROM ^users WHERE handle=$',
			$handle
		));
	}
	
	function qa_db_user_count($db)
	{
		return qa_db_read_one_value(qa_db_query_sub($db,
			'SELECT COUNT(*) FROM ^users'
		));
	}

	function qa_db_user_set_info($db, $userid, $name, $location, $about)
	{
		qa_db_query_sub($db,
			'UPDATE ^users SET name=$, location=$, about=$ WHERE userid=$',
			$name, $location, $about, $userid
		);
	}
	
	function qa_db_user_set($db, $userid, $field, $value)
	{
		qa_db_query_sub($db,
			'UPDATE ^users SET '.mysql_real_escape_string($field, $db).'=$ WHERE userid=$',
			$value, $userid
		);
	}

	function qa_db_user_set_password($db, $userid, $password)
	{
		require_once QA_INCLUDE_DIR.'qa-util-string.php';
		
		$salt=qa_random_alphanum(16);

		qa_db_query_sub($db,
			'UPDATE ^users SET passsalt=$, passcheck=UNHEX($) WHERE userid=$',
			$salt, qa_db_calc_passcheck($password, $salt), $userid
		);
	}
	
	function qa_db_user_rand_resetcode()
	{
		require_once QA_INCLUDE_DIR.'qa-util-string.php';
		
		return qa_random_alphanum(8);
	}
	
	function qa_db_user_profile_set($db, $userid, $field, $value)
	{
		qa_db_query_sub($db,
			'REPLACE ^userprofile (title, content, userid) VALUES ($, $, $)',
			$field, $value, $userid
		);
	}
	
	function qa_db_user_logged_in($db, $userid, $ip)
	{
		qa_db_query_sub($db,
			'UPDATE ^users SET loggedin=NOW(), loginip=COALESCE(INET_ATON($), 0) WHERE userid=$',
			$ip, $userid
		);
	}
	
	function qa_db_user_written($db, $userid, $ip)
	{
		qa_db_query_sub($db,
			'UPDATE ^users SET written=NOW(), writeip=COALESCE(INET_ATON($), 0) WHERE userid=$',
			$ip, $userid
		);
	}

?>