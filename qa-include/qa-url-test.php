<?php
	
/*
	Question2Answer 1.2-beta-1 (c) 2010, Gideon Greenspan

	http://www.question2answer.org/

	
	File: qa-include/qa-url-test.php
	Version: 1.2-beta-1
	Date: 2010-06-27 11:15:58 GMT
	Description: Sits in an iframe and shows a green page with word 'OK'


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

	if (qa_gpc_to_string(@$_GET['param'])==QA_URL_TEST_STRING) {
		require_once QA_INCLUDE_DIR.'qa-app-admin.php';
	
		echo '<HTML><BODY STYLE="margin:0; padding:0;">';
		echo '<TABLE WIDTH="100%" HEIGHT="100%" CELLSPACING="0" CELLPADDING="0">';
		echo '<TR VALIGN="middle"><TD ALIGN="center" STYLE="border-style:solid; border-width:1px; background-color:#fff; ';
		echo qa_admin_url_test_html();
		echo 'TD></TR></TABLE>';
		echo '</BODY></HTML>';
	}


/*
	Omit PHP closing tag to help avoid accidental output
*/