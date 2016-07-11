Step-1: Install NodeJS
----------------------
	--> https://nodejs.org/download/
		Run node -v


Step-2: Install NPM
-------------------
	--> $ sudo npm install npm -g
		Run npm -v


Step-3: Install Bower
---------------------
	--> $ npm install -g bower
		Run bower -v


Step-4: Create redirecting file to bower_components
-----------------------------------------------
	create directory '.bowerrc'
		--> put this into file
				{
				    "directory": "app/src/bower_components"
				}


Step-5: Open project folder (Console, Terminal)
-----------------------------------------------
	--> npm install
	--> npm install bower -g
	--> npm install gulp -g
	--> bower install
	--> gulp serve