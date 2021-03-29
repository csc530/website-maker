<h1>Assignment 2 - Web Dreamscapes</h1>
<a href="https://lamp.computerstudi.es/~Christofer1157052/PHP-comp1006/CM-site/index.php" target="_blank">Web Dreamscapes (Live site)</a>
<h2>Purpose</h2>
<p>This web app is made to allow registered users 'host' their websites. Allowing them to edit the website's name and individual page content. Also, allowing collaboration with other registered users.</p>
<h2>Clarification</h2>
<p>Throughout the project/pages I (try) to make distinction between <em>webpage</em> and website. The difference being</p>
<dl>
<dt>Webpage</dt>
<dd>An individual page displaying content like this README or a product page on the Walmart website</dd>
<dt>Website</dt>
<dd>The overall container for webpage's such as the Walmart website</dd>
</dl>
<p><small>(I know I was confused making the pages trying to distinguish and add distinction, thought I would just add this just to be safe)</small></p>
<h2>Code</h2>
<h3>Explaining ubiquitous code throughout website</h3>
<ul>
<li>I have placed an exit after each header to ensure that no other code is executed after leaving the page</li>
<li>For sites that need authentication or refer to the email in the session object no <code>session_start()</code> is needed as it's called in the authentication at the beginning (somewhere) on the page</li>
<li>If there is an <code>$error</code> or <code>_GET['error']</code> I am checking for an error (that I 'threw') to display to the user</li>
<li><code>$_GET['siteTitle']</code>,<code>$_GET['creator']</code>, and <code>$_GET['pageNumber']</code> are necessary variables to access the correct record in the database for the website or webpage. I choose GET instead of post as I think it's more elegant in the form opposed to having empty hidden inputs containg them and security wise I think it's the same as both values can be edited in developer's options</li>
<li>When querying the DB in a try catch block I often have an error beforehand of <q>Network error, please try again.</q> along those lines. This means I don't expect any specific error from the database so that's my catchall for any un-thought of error (which it probably will be)</li>
<li>I often switch between " " and ' ' quotes depending if I am/can add a variable directly with double quotes if not I try to use single quotes</li>
<li>Some pages include meta.php and authenticate.php this will not cause an error as authenticate in both cases use <code>require_once</code> which it means it will run only once in the page = <code>session_start()</code> is run only once per page</li>
<li>the <code>$title</code> variable on top of pages is the tab's title</li>
</ul>