Step-1: Install NodeJS 									|
----------------------									|
														|											
	--> https://nodejs.org/download/					|
		Run node -v										|
														|
														|
														|
Step-2: Install NPM 									|	SALTARSE ESTOS PASOS SI YA TENEMOS
-------------------										|	ESTOS COMPONETES DE MANERA GLOBAL.
														|		(Se instalan una sola vez)
	--> $ sudo npm install npm -g 						| 		
		Run npm -v 										|		-- NodeJS
														|		-- NPM
														|		-- Bower
														|
Step-3: Install Bower 									|
---------------------									|
														|
	--> $ npm install -g bower 							|
		Run bower -v 									|



Step-4: Create redirecting Folder file
	--> '.bowerrc'
			{
			    "directory": "app/src/bower_components"
			}



Step-5: Open Project Folder (Console, Terminal)
-----------------------------------------------

	--> npm install
	--> npm install bower -g
	--> npm install gulp -g
	--> bower install
	--> gulp serve