<h1 align="center">
  <br>
  CP476 - Internet Computing Final
  <br>
</h1>

## Prerequisites
1. Download and install [Docker Desktop](https://www.docker.com/products/docker-desktop/)
2. Have Docker running
3. Have [git installed](https://git-scm.com/book/en/v2/Getting-Started-Installing-Git) and setup (see [Your Identity](https://git-scm.com/book/en/v2/Getting-Started-First-Time-Git-Setup) for setup)

## How to Install

1. Navigate to where you want to store your code in terminal and clone your new repository with `git clone <repoURL>` (you can find this url by clicking the gree "Code" button)
2. Once your repo is done downloading, navigate inside of it using `cd <name-of-repo>`
3. Run `docker compose up`
4. Once docker finishes, your site will be live at `localhost:8000`

That's it! You're done.

Whenever you need to shutdown the container, do `control + C` in the terminal running it, and to reboot it use `docker compose up`.

## How to Use
Now that docker is setup and running, you'll want to open a [VSCode](https://code.visualstudio.com/docs/setup/setup-overview) instance inside your repo.
Navigate to `index.php` this is where you will be coding.

To use MySQL to create tables and data, you'll need to get inside the docker container to access MySQL. Open Docker Desktop and select the 3 dots beside `db` and "Open in terminal"
<img src="https://i.imgur.com/E32bZo6.png">

From inside you can run `mysql -uroot -ppassword` to access your instance of MySQL, in `index.php` mysqli is currently looking for a database called `test`, why don't we make that database as practice. 
