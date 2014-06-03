<?php
	
/*
	Question2Answer 1.0-beta-1 (c) 2010, Gideon Greenspan

	http://www.question2answer.org/

	
	File: qa-include/qa-db-post-update.php
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

	function qa_db_post_set_selchildid($db, $postid, $selchildid)
	{
		qa_db_query_sub($db,
			'UPDATE ^posts SET selchildid=# WHERE postid=#',
			$selchildid, $postid
		);
	}
	
	function qa_db_post_set_type($db, $postid, $type)
	{
		qa_db_query_sub($db,
			'UPDATE ^posts SET type=$, updated=NOW() WHERE postid=#',
			$type, $postid
		);
	}
	
	function qa_db_post_set_text($db, $postid, $title, $content, $tagstring, $notify)
	{
		qa_db_query_sub($db,
			'UPDATE ^posts SET title=$, content=$, tags=$, updated=NOW(), notify=$ WHERE postid=#',
			$title, $content, $tagstring, $notify, $postid
		);
	}
	
	function qa_db_posttags_get_post_wordids($db, $postid)
	{
		return qa_db_read_all_values(qa_db_query_sub($db,
			'SELECT wordid FROM ^posttags WHERE postid=#',
			$postid
		));
	}
	
	function qa_db_posttags_delete_post($db, $postid)
	{
		qa_db_query_sub($db,
			'DELETE FROM ^posttags WHERE postid=#',
			$postid
		);
	}

	function qa_db_titlewords_get_post_wordids($db, $postid)
	{
		return qa_db_read_all_values(qa_db_query_sub($db,
			'SELECT wordid FROM ^titlewords WHERE postid=#',
			$postid
		));
	}
	
	function qa_db_titlewords_delete_post($db, $postid)
	{
		qa_db_query_sub($db,
			'DELETE FROM ^titlewords WHERE postid=#',
			$postid
		);
	}

	function qa_db_contentwords_get_post_wordids($db, $postid)
	{
		return qa_db_read_all_values(qa_db_query_sub($db,
			'SELECT wordid FROM ^contentwords WHERE postid=#',
			$postid
		));
	}
	
	function qa_db_contentwords_delete_post($db, $postid)
	{
		qa_db_query_sub($db,
			'DELETE FROM ^contentwords WHERE postid=#',
			$postid
		);
	}

?>