
INSTALATION:


1. Create required database. Use "utf8_unicode_ci" as default collation.

2. Import "42cms-v1-0.sql" file into your database (you can use phpMyAdmin or some other database administration tool)

3. Move all the files (except "42cms-v1-0.sql") into desired directory on your web hosting

4. Edit "config.php" file (located in "includes" folder). You need to change at least all url and 
	database settings and insert cookie and session salt and if you would like to use reCaptcha 
	fill recaptcha keys (otherwise set "public $attemptsBeforeCaptcha='-1';")
	
5. Change in database, in "chunks" table "weburl" body to contain apropriate url 
	(do not forget it mast beggin with "http://" or "https://" and end with "/"

6. You should be up and running

7. In your browser visit http://www.[yourweb].com/members/ 
	Your default login data are:
		Username:	admin
		Password:	42cmsadmin
	!!! Change your password ASAP !!!



NOTES:

-to see the list of plugins pre-installed on your 42cms site se "plugins.TXTfile"

-if you would like to edit inserted plugin via Page editor, for safety reason you must use text editor (click "Use text editor")

-your templates are located in "includes/templates" folder
	template 1 is default administration interface template
	template 2 is the same but with tree
	template 10 is your default 'public' template

-there is one more interresting thing in 42cms-chunks-plain text (or CSS, HTML, JavaScript) from database (table "chunks") inserted 
	into page text by replacing chunk placeholder with its text (e.g. [{weburl}] replace with URL of your web from database




