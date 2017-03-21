# Welcome to Pawar 2018 Repo

## Getting started (Mac)
1. Install Docker (www.docker.com)
2. Clone the repo `git clone git@github.com:pawar-2018/pawar2018.git`  
3. Start the docker container `docker-compose up -d`    
4. In another terminal tab, `cd` in to your local clone of the repo and run `npm install`.
5. To start your dev server, run `npm start`. This proxies from localhost:8000, which is where Docker is running, so you do need Docker from step 3 running as well.
6. You might need to restart your vms (docker-compose restart) if you are getting a pawar2018 not found error
7. If you're just running onto merge conficts or want to make sure you build before pushing (this will be fixed when we get deployment more figured out, sorry, because this will get annoying really fast) you can just run `npm run build`.

## Getting started (Windows)
1. Install Docker Toolbox on Windows (https://docs.docker.com/toolbox/toolbox_install_windows/)
2. Clone the repo
````
    git clone git@github.com:pawar-2018/pawar2018.git
````
3. Start the docker container
  1. You might need to change the volumes for Wordpress to:
       ```- /wp-content/themes/pawar2018:/var/www/html/wp-content/themes/pawar2018```
````
    docker-compose up -d
````
4. Open Oracle VM and look for your Docker VM(default)
5. Right click docker VM(default) and select Settings > Shared Folders
6. Mount the location where you clone the repo into by adding a shared folder
    Folder Path EX: C:\github\username\pawar2018\wp-content
    Folder Name EX: wp-content
7. You might need to restart the VM if it doesn't mount the folder path
8. Once you see the files in the VM console(double click the VM for console), fetch your IP in the Docker terminal
with docker-machine ip
9. Go to http://docker-machine-ip-here:8000

## Setting up Wordpress locally

### Choose the Pawar2018 theme

1. Go to `http://localhost:8000/wp-admin/themes.php`
2. Choose `Activate` on the `Pawar2018` theme

### Set up permalinks

1. Go to `http://localhost:8000/wp-admin/options-permalink.php`
2. Choose the `Post name` option
3. Save Changes

### Importing Content

Currently, the quickest way to get the content is to export an XML copy of the
staging site's content and import into your local Wordpress.

## URLS

* Production (master) http://ameyapawar2018.wpengine.com
* Staging (dev) http://ameyapawar2018.staging.wpengine.com
* Localhost http://localhost:8000

## GIT Strategy
For this project, we will follow the [GitHub Standard Fork & Pull Request Workflow]
(https://gist.github.com/Chaser324/ce0505fbed06b947d962). All work should be done on branches in a developers fork. Note that we do our work on the "dev" branch, rather than "master"


## Built by some really awesome people
* [@mephraim](https://github.com/mephraim)
* [@micada](https://github.com/micada)
* [@vwampage](https://github.com/vwampage)
* [@brianmontanaweb](https://github.com/brianmontanaweb)
* [@peterbinks](https://github.com/peterbinks)
* [@john-telford] (https://github.com/john-telford)
* [@gensym] (https://github.com/gensym)

## README TODOS

* Update dev setup instructions
* Add coding standards
* Add integration standards
* Add git strategy
