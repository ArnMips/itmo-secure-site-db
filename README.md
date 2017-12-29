# itmo-secure-site-db

The project of the protected site with a database on the subject Information Security

## Some server settings

### Create database 

```mysql
CREATE TABLE users (
     user_id int(11) unsigned NOT NULL auto_increment,
     user_login varchar(30) NOT NULL,
     user_password varchar(32) NOT NULL,
     user_hash varchar(32) NOT NULL,
     PRIMARY KEY (user_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
```

```mysql
CREATE TABLE files (
     file_id int(11) unsigned NOT NULL auto_increment ,
     file_name varchar(60) NOT NULL,
     user_id int(11) unsigned NOT NULL,
     PRIMARY KEY(file_id),
     FOREIGN KEY (user_id)
     REFERENCES  users(user_id)
     ON DELETE CASCADE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
```

### How do I fix the error ‘Unable to find the wrapper “https” 
_– did you forget to enable it when you configured PHP?’_

To solve this error, you need to install the OpenSSL package for PHP on your webserver.

On a FreeBSD server, you may need to install the following package: `php53_openssl` and restart your webserver.

On a Windows server, open your 'php.ini' config file and simply uncomment the following line:

    ;extension=php_openssl

and restart the webserver. The error should be resolved.

### Protection against attacks
#### XSS
Используйте экранирование входных\выходных данных. Применяйте встроенные функции для очистки кода от вредоносных скриптов. К ним относятся такие функции как 'htmlspecialchar()', 'htmlentities()' и 'strip_tags()'.

Примеры использования: 
```php
$name = htmlentities($_POST['name'], ENT_QUOTES, "UTF-8"); 
```
htmlentities — Преобразует все возможные символы в соответствующие HTML-сущности
```php
$name = strip_tags($_POST['name']);
$name = htmlspecialchars($_POST['name'], ENT_QUOTES);
```
Кроме того, внесем в httpd.conf следующие строки (если такая настройка отключена в браузере пользователя)
```
Header set X-XSS-Protection “1; mode=block”
```
#### SQL Injection
Уязвимые места – формы регистрации и входа

Перед использованием данных из '$_POST' для полей имени и логина осуществляем их обработку по регулярному выражению
```php
preg_match("/^[a-zA-Z0-9]+$/",$_POST['login'])
preg_match("/^[a-zA-Z0-9]+$/",$name)
```
Так как пароли не представляются в явном виде, а используется их hash, то, дополнительной проверки не требуется. 
```php
$password = md5(md5(trim($_POST['password'])));
```
Для COOKIE тоже осуществляем валидацию. При поиске пользователя в БД на основе его id из COOKIE. 
```php
preg_match("/^[a-zA-Z0-9]+$/",$cookie_hash)
preg_match("/^[0-9]+$/",$cookie_id)
```
#### Information Leakage
Отключим отображение версии сервера в 'httpd.conf'

    ServerTokens Prod
    ServerSignature Off
    
Отключим отображение версии PHP в 'php.ini'

    expose_php = Off
    
Отключение отображения содержимого директорий в `httpd.conf`

    <Directory> Options None </Directory>
    
Заблокируем доступ к содержимому директорий, в которых хранятся файлы пользователей. Для этого добавим в каждую из таких директорий файл `.htaccess` со следующими строками

    Deny from all
    
Кроме того, запретим передачу Etag в `httpd.conf`

    FileETag None

#### Denial-of-service
Как один из способов уменьшить time-out Apache до 60 секунд (по умолчанию 300 сек.)

Добавить следующую запись в `httpd.conf`

    Timeout 60
    
Произведём тюнинг сервера. Включим в `httpd.conf`

    Include conf/extra/httpd-mpm.conf
    
Затем узнаем версию MPM. Для этого выполним 

    httpd -V

В строке Server MPM будет указана версия. Для данного сервера это WinNT

В `httpd-mpm.conf` настроем параметры для WinNT

```
WinNT MPM
ThreadsPerChild: constant number of worker threads in the server process
MaxRequestsPerChild: maximum  number of requests a server process serves
<IfModule mpm_winnt_module>
    ThreadsPerChild       10
    MaxRequestsPerChild    0
</IfModule> 
```
This Multi-Processing Module (MPM) is the default for the Windows NT operating systems. It uses a single control process which launches a single child process which in turn creates threads to handle requests
Capacity is configured using the ThreadsPerChild directive, which sets the maximum number of concurrent client connections. 

#### Дополнительные меры
Использовать __Content Security Policy__ (CSP). Это заголовок, который позволяет в явном виде объявить «белый список» источников, с которых можно подгружать различные данные, например, JS, CSS, изображения и пр. Даже если злоумышленнику удастся внедрить скрипт в веб-страницу, он не выполниться, если не будет соответствовать разрешенному списку источников.

Для того чтобы воспользоваться CSP, веб-приложение должно через HTTP-заголовок «Content-Security-Policy» посылать политику браузеру.

```php
<?php
$csp = "Content-Security-Policy: "
       . "default-src 'self';";
header($csp);
?>
```
К сожалению, reCapcha не поддерживает CSP, поэтому на странице регистрации и входе CSP не установлен.

__'Content-Security-Policy'__ — это официальный http-заголовок, утвержденный W3C, который поддерживается браузерами Chrome 26+, Firefox 24+ и Safari 7+. HTTP-заголовок «X-Content-Security-Policy» используется для Firefox 4-23 и для IE 10-11, заголовок «X-Webkit-CSP» – для Chrome 14-25, Safari 5.1-7.

