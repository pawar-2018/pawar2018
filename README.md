# Welcome to Pawar 2018 Repo

## Getting started (Mac)
1. Install Docker (www.docker.com)
2. Clone the repo `git clone git@github.com:pawar-2018/pawar2018.git`
3. Start the docker container `docker-compose up -d`
4. In another terminal tab, `cd` in to your local clone of the repo and run `npm install`.
5. To start your dev server, run `npm start`. This proxies from localhost:8000, which is where Docker is running, so you do need Docker from step 3 running as well.
6. You might need to restart your vms (docker-compose restart) if you are getting a pawar2018 not found error
7. If you're just running onto merge conficts or want to make sure you build before pushing (this will be fixed when we get deployment more figured out, sorry, because this will get annoying really fast) you can just run `npm run build`.
8. Go through the Wordpress Installation and then head to "Setting up Wordpress Locally"

## Getting started (Windows)
1. Install Docker Toolbox on Windows (https://docs.docker.com/toolbox/toolbox_install_windows/), you'll need Python installed
2. Clone the repo
```
    git clone git@github.com:pawar-2018/pawar2018.git
```
3. Start the docker container with Docker Quickstart Terminal
  1. You will need to change the volumes for Wordpress in `docker-compose.yml` to:
       ```
       volumes:
         - /wp-content/themes/pawar2018:/var/www/html/wp-content/themes/pawar2018
       ```
  2. Navigate in the CL to your local file path where `docker-compose.yml` is located
```
    docker-compose up -d
```
4. Open Oracle VM and look for your Docker VM(default)
5. Right click docker VM(default) and select Settings > Shared Folders
6. Mount the location where you clone the repo into by adding a shared folder
    Folder Path EX: C:\github\username\pawar2018\wp-content
    Folder Name EX: wp-content
7. You might need to restart the VM if it doesn't mount the folder path
8. Verify the files in the VM console(double click the VM for console)
9. The VM terminal will have the path `root@default:~` navigate with `cd ..` to the root and `cd wp-content/themes/` check the for the folder `pawar2018` with `ls`
10. Fetch your IP in the Docker terminal
with docker-machine ip
11. Go to http://docker-machine-ip-here:8000
12. Run `npm install` in the repo's root directory through terminal where `package.json` exists
13. `npm run build` in terminal, this will run the script for gulp to compile Sass/SCSS

## Setting up Wordpress locally

### Choose the Pawar2018 theme

1. Go to `http://localhost:3000/wp-admin/themes.php`
2. Choose `Activate` on the `Pawar2018` theme

### Set up permalinks

1. Go to `http://localhost:3000/wp-admin/options-permalink.php`
2. Choose the `Post name` option
3. Save Changes

### Activate Members

1. Go to plugins.
2. Choose `Activate` on the Members Plugin, so your Admin user has the right permissions.

### Importing Content

Currently, the quickest way to get the content is to export an XML copy of the
staging site's content and import into your local Wordpress. It sucks. We know. We're working on it.

1. Get Mica or John to give you a Staging Login. They can be Slacked.
2. Once logged in, click on CPT UI on the left hand side and then Import/Export Post Types.
3. Copy the JSON in Export Post Types
4. Go back to your local version of Wordpress at http://localhost:3000 and click on CPT UI
5. Paste that into Import Post Types and then Import. If there is an error here, it's fine, just tap in #webdev and we can see what's up.

This next part is going to be pretty hit or miss. We have to figure out how to import the larger-than-the-max staging XML file.

1. Go to staging, and under Tools on the left there, choose Export. If you download All Content, and the file is larger than 2MB, then we'll have to grab only what you need. That is: Pages, Field Groups, Fields, Events, Issues, and Pillars. You might need Press Releases if you're working on something for that section, but not in general.
2. Go back to your local, Tools > Import. You will sadly have to upload these one at a time. Again, this blows and we're working to fix it. If there is an error here, it's fine, just tap in #webdev and we can see what's up.

## URLS

* Production (master) http://ameyapawar2018.wpengine.com
* Staging (dev) http://ameyapawar2018.staging.wpengine.com
* Localhost http://localhost:3000 or 8000, depending

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
