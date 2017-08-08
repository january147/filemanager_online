# A simple php online filemanager

## Let's get started

​	This is a very simple online filemanger written in php with a disordered project structure, and it just offers some basic functions now. I create just for study and maybe I will improve it to meet my needs.

## I'll introduce the small project here

First, the structure of the small project may changes frequently for I am a beginner and am trying to improve it. If you are the same as me, this small project may give you some suggestions to meet yout needs.

ok, now it's the main part

1. The whole project has several pages and each page includes three files -- .php, .css and .js. 

   Php files are executed in server and can handle data from users and change pages dynamically with the data stored in server. Css files decide what pages look like and if you want to make your pages beautiful, they are very important. Js files can handle users' data in client and change the appearance of pages.

2. Index.(php,js,css) are the main page that display your files.

3. login.(php,js,css) are the page used for users to log in.

4. View.(php,js,css) are the page used to display the contents of files.

5. Edit(php,js,css) are the page used to modify the contents of text files

6. The folder named js is where js files are stored.

7. The folder named css is where css files are stored.

8. The folder named imgs is where different icons used as button or logos are stored.

9. The folder named photo is used to store  photos of users that represent them.(not to use yet)

10. The folder named music_picure stores pictures displayed while playing music in view.php.

11. The file data.php is used to handle all the data sended to the server.

12. The file kit.php included many fucntions that used in data.php and other files. 

## Writen in the end

If you want to test the small filemanager, you need to install LAMP or WAMP. At least you need apache2.4 , php7.0 and Mysql. Besides,  you need to change the arguements*(username and password)* of the functions used to connect to Mysql in function **login()**  in **kit.php**.

## OK, that's all, thank you.