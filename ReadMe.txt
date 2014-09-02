This app has been developed with wamp server on windows. The same is recommended for testing.

Steps : 
	Install wamp server.
	The email field should remain you@yourdomain when asked during installation.(Required later)

	Install test mail server tool  from 
			http://www.toolheap.com/test-mail-server-tool/
	and start it.

	Unzip the project file in the default html folder for wamp. Now you should have a aceretail folder in there. (C:\wamp\www\aceretail)

	Now open a browser and go to the following url ---> localhost/phpmyadmin

	Create a database "aceretail" and click on it.

	Now click on import and navigate to the unzipped project folder. In there open the dbFiles folder.
		C:\wamp\www\aceretail\dbFiles

	select the .sql provided inside and click open folowed by Go in phpmyadmin

	The username password for mysql has been set to "root" and ""(default values). You can change it by editing aceretail\constants.php file.

	Now go to the following url ---> localhost/aceretail  in your browser(google chrome recommended)

	Now you can signup and then login and test the online retail application.


